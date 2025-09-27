/**
 * Equipment Management Module
 * Handles equipment selection, form validation, and integration with equipment types
 */
class EquipmentManager {
    constructor() {
        this.equipmentList = [];
        this.equipmentTypes = {};
        this.editIndex = null;
        this.init();
    }

    /**
     * Initialize equipment manager
     */
    async init() {
        await this.loadEquipmentTypes();
        await this.loadEquipmentFromDatabase();
        this.bindEvents();
        this.updateDisplay();
        this.toggleEquipmentFieldRequirements();
        console.log("Equipment Manager initialized");
    }

    /**
     * Load equipment types from API
     */
    async loadEquipmentTypes() {
        try {
            const response = await fetch("/api/equipment-types", {
                headers: { Accept: "application/json" },
            });
            if (response.ok) {
                const types = await response.json();
                this.equipmentTypes = {};
                types.forEach((type) => {
                    this.equipmentTypes[type.id] = type;
                    this.equipmentTypes[type.code] = type; // Also index by code for backwards compatibility
                });
                console.log("Loaded equipment types:", this.equipmentTypes);
            }
        } catch (error) {
            console.error("Error loading equipment types:", error);
        }
    }

    /**
     * Load equipment items from database
     */
    async loadEquipmentFromDatabase() {
        const select = document.getElementById("equipment_selection");
        if (!select) return;

        // Show loading state
        select.innerHTML = '<option value="">Loading equipment...</option>';

        const candidateUrls = ["/api/equipment", "/inspections/api/equipment"];

        let data = null;
        let lastError = null;

        // Try endpoints in order until one succeeds
        for (const url of candidateUrls) {
            try {
                const res = await fetch(url, {
                    headers: { Accept: "application/json" },
                });
                if (!res.ok) {
                    lastError = new Error(`HTTP ${res.status} for ${url}`);
                    continue;
                }
                data = await res.json();
                break; // success
            } catch (e) {
                lastError = e;
            }
        }

        // Parse into a uniform array regardless of backend shape
        let equipmentArray = [];
        if (Array.isArray(data)) {
            equipmentArray = data;
        } else if (data && Array.isArray(data.equipment)) {
            equipmentArray = data.equipment; // { success: true, equipment: [...] }
        } else if (data && Array.isArray(data.data)) {
            equipmentArray = data.data; // pagination shape fallback
        }

        // Render options
        select.innerHTML = '<option value="">-- Select Equipment --</option>';

        if (equipmentArray.length === 0) {
            // No data or failed to fetch
            if (lastError) {
                console.error("Error loading equipment:", lastError);
            }
            const emptyOpt = document.createElement("option");
            emptyOpt.disabled = true;
            emptyOpt.textContent = "No equipment found";
            select.appendChild(emptyOpt);
            this.showAlert(
                "No equipment found from database. Please contact an administrator.",
                "warning"
            );
            return;
        }

        equipmentArray.forEach((equipment) => {
            const option = document.createElement("option");
            option.value = JSON.stringify(equipment);
            const name =
                equipment.name || equipment.equipment_name || "Unknown";
            const type =
                equipment.equipment_type_name ||
                equipment.type ||
                equipment.equipment_type ||
                "N/A";
            const serial =
                equipment.serial_number || equipment.serial || "No S/N";
            option.textContent = `${name} - ${type} (${serial})`;
            select.appendChild(option);
        });
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Equipment selection change
        document.addEventListener("change", (e) => {
            if (e.target && e.target.id === "equipment_selection") {
                this.handleEquipmentSelection(e.target);
            }
        });

        // Add equipment button
        const addButton = document.getElementById("addEquipmentBtn");
        if (addButton && !addButton._bound) {
            addButton.addEventListener("click", (e) => {
                e.preventDefault();
                this.toggleInlineForm(true);
            });
            addButton._bound = true;
        }

        // Calibration date change for automatic due date calculation
        const calDateInput = document.getElementById("assignment_cal_date");
        if (calDateInput) {
            calDateInput.addEventListener("change", () => {
                this.calculateCalibrationDueDate();
            });
        }

        // Form submission
        const wizardForm = document.getElementById("wizardForm");
        if (wizardForm) {
            wizardForm.addEventListener("submit", () => {
                this.updateHiddenInputs();
            });
        }
    }

    /**
     * Handle equipment selection from dropdown
     */
    handleEquipmentSelection(selectElement) {
        const selectedValue = selectElement.value;
        const details = document.getElementById("selectedEquipmentDetails");

        if (!selectedValue) {
            if (details) details.style.display = "none";
            this.clearSelectionDetails();
            return;
        }

        try {
            const equipment = JSON.parse(selectedValue);
            this.populateForm(equipment);
            if (details) details.style.display = "block";
        } catch (e) {
            console.error("Error parsing equipment data:", e);
            if (details) details.style.display = "none";
        }
    }

    /**
     * Populate form with selected equipment data
     */
    populateForm(equipment) {
        // Fill read-only DB fields
        document.getElementById("selected_equipment_name").value =
            equipment.name || equipment.equipment_name || "";
        document.getElementById("selected_equipment_type").value =
            equipment.equipment_type_name ||
            equipment.type ||
            equipment.equipment_type ||
            "";
        document.getElementById("selected_equipment_brand").value =
            equipment.brand || equipment.brand_model || "";
        document.getElementById("selected_equipment_serial").value =
            equipment.serial_number || "";

        // Pre-fill assignment fields from DB if available
        document.getElementById("assignment_condition").value =
            equipment.condition || "good";
        document.getElementById("assignment_cal_date").value =
            equipment.calibration_date || equipment.last_calibration || "";
        document.getElementById("assignment_cal_due").value =
            equipment.calibration_due || "";
        document.getElementById("assignment_notes").value =
            equipment.notes || "";

        // Update services based on equipment type
        this.updateServicesForEquipmentType(
            equipment.equipment_type_id || equipment.type
        );

        // Update calibration fields based on equipment type requirements
        this.updateCalibrationFields(
            equipment.equipment_type_id || equipment.type
        );
    }

    /**
     * Update available services based on equipment type
     */
    updateServicesForEquipmentType(equipmentTypeIdOrCode) {
        const servicesSelect = document.getElementById("assignment_services");
        if (!servicesSelect) return;

        // Find equipment type
        const equipmentType = this.equipmentTypes[equipmentTypeIdOrCode];
        if (!equipmentType) return;

        // Clear existing selections
        Array.from(servicesSelect.options).forEach((option) => {
            option.selected = false;
        });

        // Auto-select default services for this equipment type
        if (
            equipmentType.default_services &&
            Array.isArray(equipmentType.default_services)
        ) {
            equipmentType.default_services.forEach((service) => {
                const option = Array.from(servicesSelect.options).find(
                    (opt) => opt.value === service
                );
                if (option) {
                    option.selected = true;
                }
            });
        }

        // Show info about recommended services
        this.showServiceInfo(equipmentType, servicesSelect);
    }

    /**
     * Show service information for equipment type
     */
    showServiceInfo(equipmentType, servicesSelect) {
        const serviceInfo = document.createElement("div");
        serviceInfo.className = "alert alert-info mt-2";
        serviceInfo.innerHTML = `<small><i class="fas fa-info-circle"></i> Recommended services for ${
            equipmentType.name
        }: ${
            equipmentType.default_services?.join(", ") || "None specified"
        }</small>`;

        // Remove any existing info
        const existingInfo =
            servicesSelect.parentNode.querySelector(".alert-info");
        if (existingInfo) existingInfo.remove();

        servicesSelect.parentNode.appendChild(serviceInfo);
    }

    /**
     * Update calibration fields based on equipment type requirements
     */
    updateCalibrationFields(equipmentTypeIdOrCode) {
        const equipmentType = this.equipmentTypes[equipmentTypeIdOrCode];
        if (!equipmentType) return;

        const calStatus = document.getElementById("assignment_cal_status");
        const calDate = document.getElementById("assignment_cal_date");
        const calDue = document.getElementById("assignment_cal_due");

        if (equipmentType.requires_calibration) {
            // Make calibration fields required and show them
            calStatus.required = true;
            calDate.required = true;
            calDue.required = true;

            // Add visual indicators
            this.updateFieldLabel(calStatus, "Calibration Status", true);
            this.updateFieldLabel(calDate, "Last Calibration", true);
            this.updateFieldLabel(calDue, "Calibration Due", true);

            // Calculate due date if last calibration is set and equipment type has frequency
            if (equipmentType.calibration_frequency_months && calDate.value) {
                const lastCal = new Date(calDate.value);
                const dueDate = new Date(lastCal);
                dueDate.setMonth(
                    dueDate.getMonth() +
                        equipmentType.calibration_frequency_months
                );
                calDue.value = dueDate.toISOString().split("T")[0];
            }
        } else {
            // Make calibration fields optional
            calStatus.required = false;
            calDate.required = false;
            calDue.required = false;
            calStatus.value = "not_required";

            // Update labels
            this.updateFieldLabel(calStatus, "Calibration Status", false);
            this.updateFieldLabel(calDate, "Last Calibration", false);
            this.updateFieldLabel(calDue, "Calibration Due", false);
        }
    }

    /**
     * Update field label with required indicator
     */
    updateFieldLabel(field, baseText, required) {
        const label = field.parentNode.querySelector("label");
        if (label) {
            label.innerHTML = required
                ? `${baseText} <span class="text-danger">*</span>`
                : baseText;
        }
    }

    /**
     * Calculate calibration due date based on last calibration and equipment type frequency
     */
    calculateCalibrationDueDate() {
        const calDate = document.getElementById("assignment_cal_date");
        const calDue = document.getElementById("assignment_cal_due");
        const equipmentSelect = document.getElementById("equipment_selection");

        if (!calDate.value || !equipmentSelect.value) return;

        try {
            const equipment = JSON.parse(equipmentSelect.value);
            const equipmentType =
                this.equipmentTypes[
                    equipment.equipment_type_id || equipment.type
                ];

            if (equipmentType && equipmentType.calibration_frequency_months) {
                const lastCal = new Date(calDate.value);
                const dueDate = new Date(lastCal);
                dueDate.setMonth(
                    dueDate.getMonth() +
                        equipmentType.calibration_frequency_months
                );
                calDue.value = dueDate.toISOString().split("T")[0];

                // Update calibration status based on due date
                const today = new Date();
                const daysDiff = Math.ceil(
                    (dueDate - today) / (1000 * 60 * 60 * 24)
                );
                const calStatus = document.getElementById(
                    "assignment_cal_status"
                );

                if (daysDiff < 0) {
                    calStatus.value = "expired";
                } else if (daysDiff <= 30) {
                    calStatus.value = "due_soon";
                } else {
                    calStatus.value = "current";
                }
            }
        } catch (error) {
            console.error("Error calculating calibration due date:", error);
        }
    }

    /**
     * Toggle inline form visibility
     */
    toggleInlineForm(show) {
        const form = document.getElementById("equipmentInlineForm");
        if (!form) return;

        if (show) {
            form.style.display = "block";
            document.getElementById("equipmentFormTitle").innerHTML =
                '<i class="fas fa-plus-circle me-2"></i>Add Equipment';
            this.clearEquipmentForm();
        } else {
            form.style.display = "none";
            this.editIndex = null;
        }
    }

    /**
     * Add equipment to list
     */
    addEquipment() {
        let equipment = null;
        const select = document.getElementById("equipment_selection");
        let selected = null;

        if (select && select.value) {
            try {
                selected = JSON.parse(select.value);
            } catch (e) {
                selected = null;
            }
        } else if (
            this.editIndex !== null &&
            this.equipmentList[this.editIndex] &&
            this.equipmentList[this.editIndex].id
        ) {
            // Fallback when editing and dropdown isn't populated yet
            const cur = this.equipmentList[this.editIndex];
            selected = {
                id: cur.id,
                name: cur.name,
                equipment_name: cur.name,
                type: cur.type,
                equipment_type: cur.type,
                brand_model: cur.brand,
                serial_number: cur.serial,
                condition: cur.condition,
                last_calibration: cur.cal_date || cur.calibration,
                calibration_due: cur.cal_due,
                notes: cur.notes,
            };
        } else {
            this.showAlert(
                "Please select equipment from the dropdown",
                "danger"
            );
            return;
        }

        const selectedId = selected
            ? selected.id || selected.equipment_id || selected.uuid
            : null;
        if (!selected || !selectedId) {
            this.showAlert("Invalid equipment selection", "danger");
            return;
        }

        // Prevent duplicates by equipment ID
        if (
            this.equipmentList.some(
                (eq, idx) =>
                    (this.editIndex === null || idx !== this.editIndex) &&
                    eq.id === selectedId
            )
        ) {
            this.showAlert("This equipment is already added", "warning");
            return;
        }

        // Assignment overrides from UI
        const assignCondition = document.getElementById(
            "assignment_condition"
        ).value;
        const assignCalDate = document.getElementById(
            "assignment_cal_date"
        ).value;
        const assignCalDue =
            document.getElementById("assignment_cal_due").value;
        const assignNotes = document
            .getElementById("assignment_notes")
            .value.trim();

        equipment = {
            id: selectedId,
            name:
                selected.name || selected.equipment_name || "Unknown Equipment",
            type: selected.type || selected.equipment_type || "general",
            equipment_type_id: selected.equipment_type_id || null,
            brand: selected.brand_model || selected.brand || "",
            serial: selected.serial_number || "",
            condition: assignCondition || selected.condition || "good",
            calibration:
                assignCalDate ||
                selected.last_calibration ||
                selected.calibration_date ||
                "",
            cal_date:
                assignCalDate ||
                selected.calibration_date ||
                selected.last_calibration ||
                "",
            cal_due: assignCalDue || selected.calibration_due || "",
            notes: assignNotes || selected.notes || "",
        };

        // Assigned services (required)
        const servicesSelect = document.getElementById("assignment_services");
        const assignedServices = Array.from(
            servicesSelect?.selectedOptions || []
        ).map((o) => o.value);
        if (!assignedServices.length) {
            this.showAlert(
                "Please select at least one Assigned Service",
                "danger"
            );
            return;
        }

        // Calibration status with default
        const calStatus =
            document.getElementById("assignment_cal_status")?.value ||
            "current";

        // Attach new fields with guaranteed values
        equipment.services =
            assignedServices.length > 0
                ? assignedServices
                : ["visual_inspection"];
        equipment.calibration_status = calStatus;

        if (this.editIndex !== null) {
            this.equipmentList[this.editIndex] = equipment;
        } else {
            this.equipmentList.push(equipment);
        }

        this.updateDisplay();
        this.updateHiddenInputs();
        this.markEquipmentSectionComplete();
        this.toggleEquipmentFieldRequirements();
        this.toggleInlineForm(false);

        const verb = this.editIndex !== null ? "updated" : "added";
        this.showAlert(
            `Equipment "${equipment.name}" ${verb} successfully`,
            "success"
        );
        this.editIndex = null;
    }

    /**
     * Clear equipment form
     */
    clearEquipmentForm() {
        const select = document.getElementById("equipment_selection");
        if (select) select.value = "";
        const details = document.getElementById("selectedEquipmentDetails");
        if (details) details.style.display = "none";
        this.clearSelectionDetails();

        // Clear assignment extras
        const services = document.getElementById("assignment_services");
        if (services)
            Array.from(services.options).forEach((o) => (o.selected = false));
        const calStatus = document.getElementById("assignment_cal_status");
        if (calStatus) calStatus.value = "current";
    }

    /**
     * Clear selection details form fields
     */
    clearSelectionDetails() {
        const ids = [
            "selected_equipment_name",
            "selected_equipment_type",
            "selected_equipment_brand",
            "selected_equipment_serial",
            "assignment_cal_date",
            "assignment_cal_due",
            "assignment_notes",
        ];
        ids.forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.value = "";
        });
        const cond = document.getElementById("assignment_condition");
        if (cond) cond.value = "good";
    }

    /**
     * Update equipment display table
     */
    updateDisplay() {
        const tableWrapper = document.getElementById("equipmentTableWrapper");
        const noEquipmentMessage =
            document.getElementById("noEquipmentMessage");
        const tableBody = document.getElementById("equipmentTableBody");
        const equipmentCount = document.getElementById("equipmentCount");

        if (equipmentCount) {
            equipmentCount.textContent = this.equipmentList.length;
        }

        if (this.equipmentList.length === 0) {
            if (tableWrapper) tableWrapper.style.display = "none";
            if (noEquipmentMessage) noEquipmentMessage.style.display = "block";
        } else {
            if (tableWrapper) tableWrapper.style.display = "block";
            if (noEquipmentMessage) noEquipmentMessage.style.display = "none";

            if (tableBody) {
                tableBody.innerHTML = this.equipmentList
                    .map(
                        (eq, index) => `
                    <tr>
                        <td>${eq.name || "Unknown"}</td>
                        <td>${eq.type || "N/A"}</td>
                        <td>${eq.brand || "N/A"}</td>
                        <td>${eq.serial || "N/A"}</td>
                        <td><span class="badge bg-${this.getConditionColor(
                            eq.condition
                        )}">${eq.condition}</span></td>
                        <td>${eq.cal_date || "N/A"}</td>
                        <td>${eq.cal_due || "N/A"}</td>
                        <td><span class="badge bg-info">Database</span></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="equipmentManager.editEquipment(${index})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="equipmentManager.removeEquipment(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `
                    )
                    .join("");
            }
        }
    }

    /**
     * Get condition badge color
     */
    getConditionColor(condition) {
        const colorMap = {
            excellent: "success",
            good: "success",
            fair: "warning",
            poor: "danger",
            needs_repair: "danger",
        };
        return colorMap[condition] || "secondary";
    }

    /**
     * Edit equipment item
     */
    editEquipment(index) {
        const equipment = this.equipmentList[index];
        if (!equipment) return;

        this.editIndex = index;
        this.toggleInlineForm(true);

        // Update form title
        document.getElementById("equipmentFormTitle").innerHTML =
            '<i class="fas fa-edit me-2"></i>Edit Equipment';

        // Populate form with equipment data
        this.populateFormForEdit(equipment);
    }

    /**
     * Populate form for editing
     */
    populateFormForEdit(equipment) {
        // Try to find and select the equipment in the dropdown
        const select = document.getElementById("equipment_selection");
        if (select) {
            const options = Array.from(select.options);
            const matchingOption = options.find((option) => {
                try {
                    const data = JSON.parse(option.value);
                    return data.id === equipment.id;
                } catch (e) {
                    return false;
                }
            });

            if (matchingOption) {
                select.value = matchingOption.value;
                this.handleEquipmentSelection(select);
            }
        }

        // Populate assignment fields
        document.getElementById("assignment_condition").value =
            equipment.condition || "good";
        document.getElementById("assignment_cal_date").value =
            equipment.cal_date || "";
        document.getElementById("assignment_cal_due").value =
            equipment.cal_due || "";
        document.getElementById("assignment_notes").value =
            equipment.notes || "";

        // Select services
        const servicesSelect = document.getElementById("assignment_services");
        if (servicesSelect && equipment.services) {
            Array.from(servicesSelect.options).forEach((option) => {
                option.selected = equipment.services.includes(option.value);
            });
        }
    }

    /**
     * Remove equipment item
     */
    removeEquipment(index) {
        if (index >= 0 && index < this.equipmentList.length) {
            const equipment = this.equipmentList[index];
            if (
                confirm(`Are you sure you want to remove "${equipment.name}"?`)
            ) {
                this.equipmentList.splice(index, 1);
                this.updateDisplay();
                this.updateHiddenInputs();
                this.toggleEquipmentFieldRequirements();
                this.showAlert(
                    `Equipment "${equipment.name}" removed successfully`,
                    "info"
                );
            }
        }
    }

    /**
     * Clear all equipment
     */
    clearAllEquipment() {
        if (this.equipmentList.length === 0) return;

        if (confirm("Are you sure you want to remove all equipment?")) {
            this.equipmentList = [];
            this.updateDisplay();
            this.updateHiddenInputs();
            this.toggleEquipmentFieldRequirements();
            this.showAlert("All equipment cleared", "info");
        }
    }

    /**
     * Update hidden form inputs
     */
    updateHiddenInputs() {
        const wizardForm =
            document.getElementById("wizardForm") ||
            document.querySelector("form");
        if (!wizardForm) return;

        // Remove existing hidden inputs
        const toRemove = wizardForm.querySelectorAll(
            'input[name^="equipment_list"], input[name="equipment_assignments"]'
        );
        toRemove.forEach((input) => input.remove());

        // Add a single JSON payload for assignments
        const payload = this.equipmentList.map((eq) => ({
            // Backend-expected keys - all required fields must have values
            equipment_id: String(eq.id || "manual_" + Date.now()), // Ensure string and not null
            equipment_name: eq.name || "Unknown Equipment",
            equipment_type: eq.type || "general",
            equipment_type_id: eq.equipment_type_id || null,
            make_model: eq.brand || null,
            serial_number: eq.serial || null,
            condition: eq.condition || "good",
            calibration_status: eq.calibration_status || "current",
            last_calibration_date: eq.cal_date || eq.calibration || null,
            next_calibration_date: eq.cal_due || null,
            notes: eq.notes || null,
            assigned_services: Array.isArray(eq.services)
                ? eq.services
                : eq.services
                ? [eq.services]
                : ["visual_inspection"],
            // Also include original keys for flexibility
            id: eq.id || null,
            name: eq.name || null,
            type: eq.type || null,
            brand: eq.brand || null,
            serial: eq.serial || null,
            cal_date: eq.cal_date || eq.calibration || null,
            cal_due: eq.cal_due || null,
        }));

        const hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "equipment_assignments";
        hidden.value = JSON.stringify(payload);
        wizardForm.appendChild(hidden);

        console.log("Equipment assignments payload:", payload);
    }

    /**
     * Mark equipment section as complete
     */
    markEquipmentSectionComplete() {
        const mainForm =
            document.getElementById("wizardForm") ||
            document.querySelector("form");
        if (!mainForm) return;

        let marker = mainForm.querySelector(
            'input[name="equipment_section_complete"]'
        );
        if (!marker) {
            marker = document.createElement("input");
            marker.type = "hidden";
            marker.name = "equipment_section_complete";
            mainForm.appendChild(marker);
        }
        marker.value = this.equipmentList.length > 0 ? "1" : "0";
    }

    /**
     * Toggle equipment field requirements
     */
    toggleEquipmentFieldRequirements() {
        // This could be used for dynamic form validation based on equipment presence
        console.log("Equipment field requirements updated");
    }

    /**
     * Show alert message
     */
    showAlert(message, type = "info") {
        // Create or update an alert element
        let alertContainer = document.getElementById("equipment-alerts");
        if (!alertContainer) {
            alertContainer = document.createElement("div");
            alertContainer.id = "equipment-alerts";
            alertContainer.className = "position-fixed top-0 end-0 p-3";
            alertContainer.style.zIndex = "1050";
            document.body.appendChild(alertContainer);
        }

        const alertElement = document.createElement("div");
        alertElement.className = `alert alert-${type} alert-dismissible fade show`;
        alertElement.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        alertContainer.appendChild(alertElement);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertElement.parentNode) {
                alertElement.remove();
            }
        }, 5000);
    }
}

// Initialize equipment manager when DOM is ready
let equipmentManager;
document.addEventListener("DOMContentLoaded", function () {
    equipmentManager = new EquipmentManager();
});

// Legacy function support for inline onclick handlers
function toggleInlineForm(show) {
    if (equipmentManager) {
        equipmentManager.toggleInlineForm(show);
    }
}

function addEquipment() {
    if (equipmentManager) {
        equipmentManager.addEquipment();
    }
}

function resetInlineForm() {
    if (equipmentManager) {
        equipmentManager.clearEquipmentForm();
    }
}

function clearAllEquipment() {
    if (equipmentManager) {
        equipmentManager.clearAllEquipment();
    }
}

function updateHiddenInputs() {
    if (equipmentManager) {
        equipmentManager.updateHiddenInputs();
    }
}

function toggleEquipmentFieldRequirements() {
    if (equipmentManager) {
        equipmentManager.toggleEquipmentFieldRequirements();
    }
}

function showAlert(message, type) {
    if (equipmentManager) {
        equipmentManager.showAlert(message, type);
    }
}
