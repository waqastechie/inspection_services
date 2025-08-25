// Inspection Form JavaScript Functionality

// Check if required libraries are loaded
function checkLibraries() {
    if (typeof $ === "undefined") {
        console.warn(
            "jQuery is not loaded. Some features may not work properly."
        );
        return false;
    }

    if (typeof $.fn.select2 === "undefined") {
        console.warn(
            "Select2 is not loaded. Dropdown search functionality will be disabled."
        );
        return false;
    }

    return true;
}

// Global variables
let personnelData = [];
let equipmentData = [];
let consumableData = [];
let selectedServices = [];

// Initialize form when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM loaded, initializing form...");
    initializeForm();
    updateFormProgress();

    // Add debug bypass function
    window.debugSubmitForm = function () {
        console.log("DEBUG: Forcing form submission");
        const form = document.getElementById("liftingInspectionForm");
        if (form) {
            // Remove all event listeners temporarily
            const newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);

            // Submit directly
            console.log("DEBUG: Submitting form directly");
            newForm.submit();
        }
    };

    // Add debug validation function
    window.debugFormValidation = function () {
        const form = document.getElementById("liftingInspectionForm");
        if (form) {
            console.log("Form validity:", form.checkValidity());
            console.log("Form action:", form.action);
            console.log("Form method:", form.method);

            const requiredFields = form.querySelectorAll("[required]");
            console.log("Required fields:", requiredFields.length);

            requiredFields.forEach((field, index) => {
                console.log(`Field ${index + 1}:`, {
                    name: field.name,
                    value: field.value,
                    valid: field.checkValidity(),
                    validationMessage: field.validationMessage,
                });
            });
        }
    };

    // Add simple form submission test
    window.testFormSubmission = function () {
        console.log("TESTING: Simple form submission");
        const form = document.getElementById("liftingInspectionForm");
        if (form) {
            // Fill required fields with test data
            const clientName = document.querySelector('[name="client_name"]');
            const location = document.querySelector('[name="location"]');
            const inspectionDate = document.querySelector(
                '[name="inspection_date"]'
            );

            if (clientName) clientName.value = "Test Client";
            if (location) location.value = "Test Location";
            if (inspectionDate) inspectionDate.value = "2025-08-19";

            console.log("Test data filled, submitting form...");
            form.submit();
        }
    };
});

// Initialize form functionality
function initializeForm() {
    console.log("=== Initializing Inspection Form ===");

    // Set current date/time
    const now = new Date();
    const dateTimeString = now.toISOString().slice(0, 16);

    const inspectionDateTime = document.getElementById("inspectionDateTime");
    if (inspectionDateTime && !inspectionDateTime.value) {
        inspectionDateTime.value = dateTimeString;
    }

    // Initialize auto-save functionality
    initializeAutoSave();

    // Initialize summary updates
    updateReportSummary();

    // Initialize client dropdown (for admin/super_admin users)
    console.log("Loading client dropdown...");
    if (window.isEditMode) {
        setTimeout(() => {
            loadClientData();
            handleClientSelection();
        }, 500);
    } else {
        loadClientData();
        handleClientSelection();
    }

    // Initialize inspector dropdown (for admin/super_admin users)
    console.log("Loading inspector dropdown...");

    // Add delay for edit mode to ensure DOM is ready
    if (window.isEditMode) {
        setTimeout(() => {
            loadInspectorData();
            handleInspectorSelection();
        }, 500);
    } else {
        loadInspectorData();
        handleInspectorSelection();
    }

    console.log("=== Form Initialization Complete ===");
}

// Update form progress - simplified version
function updateFormProgress() {
    const requiredFields = [
        { name: "client_name", label: "Client Name" },
        { name: "location", label: "Location" },
        { name: "inspection_date", label: "Inspection Date" },
        { name: "inspector_name", label: "Inspector Name" },
    ];

    const missingFields = [];
    let completedFields = 0;

    requiredFields.forEach((field) => {
        const input = document.querySelector(`[name="${field.name}"]`);
        if (!input || !input.value.trim()) {
            missingFields.push(field.label);
        } else {
            completedFields++;
        }
    });

    const completionPercentage = Math.round(
        (completedFields / requiredFields.length) * 100
    );

    // Update form validation display
    updateFormValidationDisplay(completionPercentage, missingFields);
}

// Update the form validation display
function updateFormValidationDisplay(completionPercentage, missingFields) {
    // Update progress bar
    const progressBar = document.getElementById("completionProgressBar");
    const progressText = document.getElementById("completionPercentage");

    if (progressBar) {
        progressBar.style.width = `${completionPercentage}%`;
        progressBar.setAttribute("aria-valuenow", completionPercentage);

        // Change color based on completion
        progressBar.className =
            completionPercentage === 100
                ? "progress-bar bg-success"
                : "progress-bar bg-primary";
    }

    if (progressText) {
        if (isSubmitted) {
            progressText.textContent = "Submitted";
        } else if (completionPercentage === 100) {
            progressText.textContent = "Ready to Submit";
        } else {
            progressText.textContent = `${completionPercentage}% Complete`;
        }
    }

    // Update status alert and missing fields
    const statusAlert = document.getElementById("exportSectionStatus");
    const missingFieldsList = document.getElementById("missingFieldsList");

    if (completionPercentage === 100) {
        // Form is complete
        if (statusAlert) {
            statusAlert.className = "alert alert-success";
            statusAlert.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">Ready to Submit</h6>
                        <p class="mb-0">All required fields completed! Your data is auto-saved. Click "Submit Report" to enable PDF export.</p>
                    </div>
                </div>
            `;
        }
    } else {
        // Form is incomplete
        if (statusAlert) {
            statusAlert.className = "alert alert-warning";
            statusAlert.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">Form Incomplete (${completionPercentage}%)</h6>
                        <p class="mb-2">Complete remaining fields for full validation. Your data is being auto-saved.</p>
                        <div id="missingFieldsContainer">
                            <strong>Missing required fields:</strong>
                            <ul class="mb-0 mt-1" id="missingFieldsList">
                                ${missingFields
                                    .map((field) => `<li>${field}</li>`)
                                    .join("")}
                            </ul>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    // Save button is always enabled now
    const saveBtn = document.getElementById("saveReportBtn");
    if (saveBtn && !isSubmitted) {
        saveBtn.disabled = false;
    }
}

// Update report summary
function updateReportSummary() {
    const summaryFields = [
        { id: "summaryReportId", name: "report_id" },
        { id: "summaryClientCompany", name: "client_company" },
        { id: "summaryJobLocation", name: "job_location" },
        { id: "summaryInspectionDate", name: "inspection_date_time" },
        { id: "summaryInspector", name: "inspector_name" },
        { id: "summaryOverallCondition", name: "overall_condition" },
        { id: "summaryApprovalStatus", name: "approval_status" },
    ];

    summaryFields.forEach((field) => {
        const element = document.getElementById(field.id);
        const input = document.querySelector(`[name="${field.name}"]`);

        if (element && input) {
            let value = input.value || "-";

            // Format date if it's the inspection date
            if (field.name === "inspection_date_time" && value !== "-") {
                const date = new Date(value);
                value = date.toLocaleString();
            }

            element.textContent = value;
        }
    });
}

// Personnel management functions
function addPersonnel() {
    const form = document.getElementById("personnelForm");
    const formData = new FormData(form);

    const personnel = {
        name: document.getElementById("personnelName").value,
        role: document.getElementById("personnelRole").value,
        certification: document.getElementById("personnelCertification").value,
        certNumber: document.getElementById("personnelCertNumber").value,
        certExpiry: document.getElementById("personnelCertExpiry").value,
        experience: document.getElementById("personnelExperience").value,
        notes: document.getElementById("personnelNotes").value,
    };

    if (!personnel.name || !personnel.role) {
        alert("Please fill in the required fields (Name and Role).");
        return;
    }

    personnelData.push(personnel);
    updatePersonnelTable();

    // Reset form and close modal
    form.reset();
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("personnelNewModal")
    );
    modal.hide();
}

function updatePersonnelTable() {
    const tbody = document.getElementById("personnelTableBody");
    if (!tbody) return;

    tbody.innerHTML = "";

    personnelData.forEach((person, index) => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${person.name}</td>
            <td>${person.role}</td>
            <td>${person.certification || "-"}</td>
            <td>${person.certNumber || "-"}</td>
            <td>${person.certExpiry || "-"}</td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePersonnel(${index})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
    });
}

function removePersonnel(index) {
    personnelData.splice(index, 1);
    updatePersonnelTable();
}

function clearPersonnelTable() {
    personnelData = [];
    updatePersonnelTable();
}

// Equipment management functions
function addEquipment() {
    const equipment = {
        type: document.getElementById("equipmentType").value,
        modelSerial: document.getElementById("equipmentModelSerial").value,
        capacity: document.getElementById("equipmentCapacity").value,
        manufacturer: document.getElementById("equipmentManufacturer").value,
        certDate: document.getElementById("equipmentCertDate").value,
        nextDue: document.getElementById("equipmentNextDue").value,
        status: document.getElementById("equipmentStatus").value,
        certNumber: document.getElementById("equipmentCertNumber").value,
        comments: document.getElementById("equipmentComments").value,
    };

    if (!equipment.type || !equipment.modelSerial) {
        alert("Please fill in the required fields (Type and Model/Serial).");
        return;
    }

    equipmentData.push(equipment);
    updateEquipmentTable();

    // Reset form and close modal
    document.getElementById("equipmentForm").reset();
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("equipmentModal")
    );
    modal.hide();
}

function updateEquipmentTable() {
    const tbody = document.getElementById("equipmentTableBody");
    if (!tbody) return;

    tbody.innerHTML = "";

    equipmentData.forEach((equipment, index) => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${equipment.type}</td>
            <td>${equipment.modelSerial}</td>
            <td>${equipment.capacity || "-"}</td>
            <td>${equipment.certDate || "-"}</td>
            <td>${equipment.nextDue || "-"}</td>
            <td>
                <span class="badge bg-${getStatusColor(equipment.status)}">${
            equipment.status
        }</span>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEquipment(${index})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
    });
}

function removeEquipment(index) {
    equipmentData.splice(index, 1);
    updateEquipmentTable();
}

function clearEquipmentTable() {
    equipmentData = [];
    updateEquipmentTable();
}

function getStatusColor(status) {
    switch (status) {
        case "Good":
            return "success";
        case "Satisfactory":
            return "warning";
        case "Needs Attention":
            return "danger";
        case "Out of Service":
            return "dark";
        default:
            return "secondary";
    }
}

// Consumables management (if needed)
function addConsumable() {
    // Implementation for consumables management
    console.log("Add consumable functionality");
}

// Form utility functions
function validateForm() {
    const requiredFields = [
        { name: "client_name", label: "Client Name" },
        { name: "location", label: "Location" },
        { name: "inspection_date", label: "Inspection Date" },
        { name: "inspector_name", label: "Inspector Name" },
    ];

    const missingFields = [];
    let completedFields = 0;

    requiredFields.forEach((field) => {
        const input = document.querySelector(`[name="${field.name}"]`);
        if (!input || !input.value.trim()) {
            missingFields.push(field.label);
        } else {
            completedFields++;
        }
    });

    const completionPercentage = Math.round(
        (completedFields / requiredFields.length) * 100
    );

    if (missingFields.length === 0) {
        alert(
            `✅ Form validation passed! All required fields are completed (${completionPercentage}%).`
        );
    } else {
        alert(
            `⚠️ Form validation: ${
                missingFields.length
            } required fields missing:\n\n• ${missingFields.join(
                "\n• "
            )}\n\nCompletion: ${completionPercentage}%`
        );
    }

    // Update the dynamic display
    updateFormValidationDisplay(completionPercentage, missingFields);
}

function resetForm() {
    if (
        confirm(
            "Are you sure you want to reset the entire form? This action cannot be undone."
        )
    ) {
        document.getElementById("liftingInspectionForm").reset();
        personnelData = [];
        equipmentData = [];
        consumableData = [];
        updatePersonnelTable();
        updateEquipmentTable();
        updateFormProgress();
        updateReportSummary();
    }
}

// PDF Export functionality
async function exportToPDF() {
    try {
        // Add loading state
        const exportBtn = document.getElementById("exportPDFBtn");
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin me-2"></i>Generating PDF...';
        exportBtn.disabled = true;

        // Collect form data
        const formData = collectFormData();

        // Generate PDF using jsPDF (basic implementation)
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF("p", "mm", "a4");

        // Add title
        pdf.setFontSize(20);
        pdf.text("Lifting Inspection Report", 20, 20);

        // Add form data
        let yPosition = 40;
        pdf.setFontSize(12);

        // Add basic form data to PDF
        const fields = [
            { label: "Client Company", value: formData.client_company },
            { label: "Job Location", value: formData.job_location },
            { label: "Inspector", value: formData.inspector_name },
            { label: "Inspection Date", value: formData.inspection_date_time },
            { label: "Overall Condition", value: formData.overall_condition },
            { label: "Approval Status", value: formData.approval_status },
        ];

        fields.forEach((field) => {
            if (field.value) {
                pdf.text(`${field.label}: ${field.value}`, 20, yPosition);
                yPosition += 10;
            }
        });

        // Add findings
        if (formData.inspection_findings) {
            yPosition += 10;
            pdf.text("Inspection Findings:", 20, yPosition);
            yPosition += 10;

            const findings = pdf.splitTextToSize(
                formData.inspection_findings,
                170
            );
            pdf.text(findings, 20, yPosition);
        }

        // Save the PDF
        const fileName = `Lifting_Inspection_Report_${
            formData.report_id || "LIR"
        }_${new Date().toISOString().slice(0, 10)}.pdf`;
        pdf.save(fileName);

        // Reset button
        exportBtn.innerHTML = originalText;
        exportBtn.disabled = false;
    } catch (error) {
        console.error("Error generating PDF:", error);
        alert("Error generating PDF. Please try again.");

        // Reset button
        const exportBtn = document.getElementById("exportPDFBtn");
        exportBtn.innerHTML =
            '<i class="fas fa-file-pdf me-2"></i>Generate PDF Report';
        exportBtn.disabled = false;
    }
}

// Collect form data for submission/export
function collectFormData() {
    const form = document.getElementById("liftingInspectionForm");
    const formData = new FormData(form);
    const data = {};

    // Convert FormData to object
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }

    // Add additional data
    data.personnel = personnelData;
    data.equipment = equipmentData;
    data.consumables = consumableData;

    return data;
}

// Force enable export (for testing)
function forceEnableExport() {
    const exportPDFBtn = document.getElementById("exportPDFBtn");
    const saveReportBtn = document.getElementById("saveReportBtn");
    if (exportPDFBtn) exportPDFBtn.disabled = false;
    if (saveReportBtn) saveReportBtn.disabled = false;
}

// Debug function to show missing fields
function showMissingFields() {
    const requiredFields = [
        "client_company",
        "job_location",
        "inspection_date_time",
        "inspector_name",
        "inspector_qualification",
        "weather_conditions",
        "lifting_operation_type",
        "inspection_findings",
    ];

    const missingFields = [];

    requiredFields.forEach((fieldName) => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field || field.value.trim() === "") {
            missingFields.push(fieldName);
        }
    });

    console.log("Missing fields:", missingFields);
    return missingFields;
}

// Prepare form submission by populating hidden JSON inputs
function prepareFormSubmission() {
    console.log("Preparing form submission...");

    try {
        // Collect selected services and update global variable
        selectedServices = [];
        const serviceCards = document.querySelectorAll(
            ".service-card.selected"
        );
        console.log("Found service cards:", serviceCards.length);

        serviceCards.forEach((card) => {
            const serviceType = card.dataset.service;
            const serviceName = card.querySelector("h6").textContent;
            const serviceDesc = card.querySelector("p")
                ? card.querySelector("p").textContent
                : "";

            selectedServices.push({
                type: serviceType,
                name: serviceName,
                description: serviceDesc,
            });
        });

        // Populate hidden inputs
        const selectedServicesInput = document.getElementById(
            "selectedServicesInput"
        );
        const personnelAssignmentsInput = document.getElementById(
            "personnelAssignmentsInput"
        );
        const equipmentAssignmentsInput = document.getElementById(
            "equipmentAssignmentsInput"
        );
        const consumableAssignmentsInput = document.getElementById(
            "consumableAssignmentsInput"
        );

        console.log("Hidden inputs found:", {
            selectedServicesInput: !!selectedServicesInput,
            personnelAssignmentsInput: !!personnelAssignmentsInput,
            equipmentAssignmentsInput: !!equipmentAssignmentsInput,
            consumableAssignmentsInput: !!consumableAssignmentsInput,
        });

        if (selectedServicesInput) {
            selectedServicesInput.value = JSON.stringify(selectedServices);
        }
        if (personnelAssignmentsInput) {
            personnelAssignmentsInput.value = JSON.stringify(
                window.modalPersonnel || []
            );
        }
        if (equipmentAssignmentsInput) {
            equipmentAssignmentsInput.value = JSON.stringify(
                window.modalAssets || []
            );
        }
        if (consumableAssignmentsInput) {
            consumableAssignmentsInput.value = JSON.stringify(
                window.modalConsumables || []
            );
        }

        console.log("Form submission prepared with:", {
            services: selectedServices,
            personnel: window.modalPersonnel || [],
            equipment: window.modalAssets || [],
            consumables: window.modalConsumables || [],
        });
    } catch (error) {
        console.error("Error in prepareFormSubmission:", error);
        // Don't let this error prevent form submission
        console.log(
            "Continuing with form submission despite preparation error"
        );
    }
}

// Auto-save functionality
let autoSaveTimeout;
let currentInspectionId = null;
let lastSaveTime = null;
let isSubmitted = false;

// Initialize auto-save
function initializeAutoSave() {
    console.log("Initializing auto-save...");
    const form = document.getElementById("liftingInspectionForm");
    console.log("Form found:", form);
    if (form) {
        // Add listeners for auto-save and progress tracking
        form.addEventListener("input", debounceAutoSave);
        form.addEventListener("change", debounceAutoSave);

        // Add form submission handler
        form.addEventListener("submit", function (e) {
            console.log("=== FORM SUBMIT EVENT TRIGGERED ===");
            console.log("Current URL:", window.location.href);
            console.log("Form action:", form.action);
            console.log("Form method:", form.method);

            try {
                // Simple validation check
                const clientName = document.querySelector(
                    '[name="client_name"]'
                );
                const location = document.querySelector('[name="location"]');
                const inspectionDate = document.querySelector(
                    '[name="inspection_date"]'
                );
                const inspectorName = document.querySelector(
                    '[name="lead_inspector_name"]'
                );

                console.log("Required field values:", {
                    client_name: clientName?.value,
                    location: location?.value,
                    inspection_date: inspectionDate?.value,
                    lead_inspector_name: inspectorName?.value,
                });

                // Check basic required fields
                const basicFieldsValid =
                    clientName?.value &&
                    location?.value &&
                    inspectionDate?.value;

                if (!basicFieldsValid) {
                    console.log(
                        "VALIDATION FAILED: Missing required basic fields"
                    );
                    // Let browser handle validation and show errors
                    return;
                }

                // Check inspector field if it exists
                if (inspectorName && !inspectorName.value) {
                    console.log(
                        "VALIDATION FAILED: Inspector name required but empty"
                    );
                    // Let browser handle validation and show errors
                    return;
                }

                console.log("VALIDATION PASSED: All required fields present");

                // Update button state to show submission in progress
                const saveBtn = document.getElementById("saveReportBtn");
                if (saveBtn) {
                    saveBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
                    saveBtn.disabled = true;
                }

                console.log("FORM SUBMISSION PROCEEDING NORMALLY");
                // Form will submit naturally - no preventDefault() called
            } catch (error) {
                console.error("ERROR in form submission handler:", error);
                // Don't prevent submission even if there's an error in our handler
                console.log("CONTINUING WITH SUBMISSION DESPITE ERROR");
            }
        });

        // PDF export buttons
        const exportPDFBtn = document.getElementById("exportPDFBtn");
        const previewPDFBtn = document.getElementById("previewPDFBtn");

        if (exportPDFBtn) {
            exportPDFBtn.addEventListener("click", exportToPDF);
        }

        if (previewPDFBtn) {
            previewPDFBtn.addEventListener("click", previewPDF);
        }

        // Check if submit button exists for debugging
        const saveBtn = document.getElementById("saveReportBtn");
        if (saveBtn) {
            console.log("Submit button found and will use form submit handler");
        } else {
            console.log("Submit button NOT found");
        }
    }

    // Update UI state
    updateUIState();
}

// Debounced auto-save (saves 2 seconds after user stops typing)
function debounceAutoSave() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(autoSave, 2000);

    // Update form progress immediately when user types
    updateFormProgress();
}

// Auto-save function
async function autoSave() {
    if (isSubmitted) return; // Don't auto-save if already submitted

    try {
        const formData = getFormData();
        const url = currentInspectionId
            ? `/inspections/update-draft/${currentInspectionId}`
            : "/inspections/save-draft";

        const method = currentInspectionId ? "PUT" : "POST";

        updateAutoSaveStatus("Saving...");

        const response = await fetch(url, {
            method: method,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
            body: JSON.stringify(formData),
        });

        const result = await response.json();

        if (result.success) {
            if (result.inspection_id && !currentInspectionId) {
                currentInspectionId = result.inspection_id;
            }

            lastSaveTime = new Date();
            updateAutoSaveStatus("Saved");
            updateDraftStatus(result.inspection_number || "AUTO-SAVED");
            updateUIState();

            console.log("Auto-save successful:", result);
        } else {
            updateAutoSaveStatus("Save failed");
            console.error("Auto-save failed:", result.message);
        }
    } catch (error) {
        updateAutoSaveStatus("Save error");
        console.error("Auto-save error:", error);
    }
}

// Get form data for saving
function getFormData() {
    const form = document.getElementById("liftingInspectionForm");
    const formData = {};

    // Define which fields are allowed in the database (Updated for PDF structure)
    const allowedFields = [
        // Client Information
        "client_name",
        "location",
        "area_of_examination",
        "services",
        "standards",

        // Job Details
        "job_ref",
        "inspection_date",
        "inspector_name",
        "weather_conditions",

        // Asset Details (arrays)
        "asset_ref",
        "asset_description",
        "asset_location",
        "capacity",
        "last_examined",
        "next_due",

        // Items Table (arrays)
        "item_ref",
        "item_description",
        "item_quantity",
        "item_condition",
        "item_result",
        "item_remarks",

        // Consumables (arrays)
        "consumable_description",
        "consumable_quantity",
        "consumable_unit",
        "consumable_batch_number",

        // Personnel (arrays)
        "personnel_name",
        "personnel_company",
        "personnel_qualification",
        "personnel_responsibilities",

        // Comments
        "inspector_comments",
        "recommendations",
        "defects_found",
        "overall_result",
        "next_inspection_date",

        // System fields
        "status",
    ];

    // Get all form inputs, including array fields
    const inputs = form.querySelectorAll("input, select, textarea");
    inputs.forEach((input) => {
        if (input.name && allowedFields.includes(input.name)) {
            // Handle array fields (multiple inputs with same name)
            if (
                input.name.includes("asset_") ||
                input.name.includes("item_") ||
                input.name.includes("consumable_") ||
                input.name.includes("personnel_")
            ) {
                if (!formData[input.name]) {
                    formData[input.name] = [];
                }

                if (input.value && input.value.trim() !== "") {
                    formData[input.name].push(input.value);
                }
            } else {
                // Single value fields
                if (input.value && input.value.trim() !== "") {
                    formData[input.name] = input.value;
                }
            }
        }
    });

    // Add dynamic data
    formData.selected_services = JSON.stringify(selectedServices || []);
    formData.personnel_assignments = JSON.stringify(personnelData || []);
    formData.equipment_assignments = JSON.stringify(equipmentData || []);
    formData.consumable_assignments = JSON.stringify(consumableData || []);

    return formData;
}

// Update auto-save status
function updateAutoSaveStatus(status) {
    const statusElement = document.getElementById("autoSaveStatus");
    if (statusElement) {
        const now = new Date();
        const timeString = now.toLocaleTimeString();

        switch (status) {
            case "Saving...":
                statusElement.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i>Saving...`;
                break;
            case "Saved":
                statusElement.innerHTML = `<i class="fas fa-check me-1 text-success"></i>Saved at ${timeString}`;
                break;
            case "Save failed":
                statusElement.innerHTML = `<i class="fas fa-exclamation-triangle me-1 text-warning"></i>Save failed`;
                break;
            case "Save error":
                statusElement.innerHTML = `<i class="fas fa-times me-1 text-danger"></i>Save error`;
                break;
            default:
                statusElement.innerHTML = `<i class="fas fa-clock me-1"></i>Auto-save enabled`;
        }
    }
}

// Update draft status
function updateDraftStatus(inspectionNumber) {
    const container = document.getElementById("draftStatusContainer");
    const numberElement = document.getElementById("draftInspectionNumber");
    const timeElement = document.getElementById("lastSaveTime");

    if (container && numberElement && timeElement) {
        container.style.display = "block";
        numberElement.textContent = inspectionNumber;

        const timeAgo = lastSaveTime ? getTimeAgo(lastSaveTime) : "just now";
        timeElement.textContent = timeAgo;
    }
}

// Update UI state based on current status
function updateUIState() {
    const exportBtn = document.getElementById("exportPDFBtn");
    const previewBtn = document.getElementById("previewPDFBtn");

    // Update form progress first
    updateFormProgress();

    // PDF buttons only enabled if inspection is submitted
    if (exportBtn && previewBtn) {
        exportBtn.disabled = !isSubmitted;
        previewBtn.disabled = !isSubmitted;
    }
}

// Export to PDF
function exportToPDF() {
    if (!currentInspectionId) {
        alert("Please save the report first before generating PDF.");
        return;
    }

    window.location.href = `/inspections/${currentInspectionId}/pdf`;
}

// Preview PDF
function previewPDF() {
    if (!currentInspectionId) {
        alert("Please save the report first before previewing PDF.");
        return;
    }

    window.open(`/inspections/${currentInspectionId}/preview-pdf`, "_blank");
}

// Clear draft
function clearDraft() {
    if (
        confirm(
            "Are you sure you want to clear the current form? This action cannot be undone."
        )
    ) {
        document.getElementById("liftingInspectionForm").reset();
        currentInspectionId = null;
        lastSaveTime = null;
        isSubmitted = false;

        const draftContainer = document.getElementById("draftStatusContainer");
        if (draftContainer) {
            draftContainer.style.display = "none";
        }

        updateAutoSaveStatus("Auto-save enabled");
        updateUIState();
    }
}

// Dynamic table row management functions

// Add asset row
function addAssetRow() {
    const tableBody = document.querySelector("#asset-details-table tbody");
    const newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td><input type="text" name="asset_ref[]" class="form-control" placeholder="Asset reference"></td>
        <td><input type="text" name="asset_description[]" class="form-control" placeholder="Description"></td>
        <td><input type="text" name="asset_location[]" class="form-control" placeholder="Location"></td>
        <td><input type="text" name="capacity[]" class="form-control" placeholder="Capacity"></td>
        <td><input type="date" name="last_examined[]" class="form-control"></td>
        <td><input type="date" name="next_due[]" class="form-control"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeAssetRow(this)">Remove</button></td>
    `;
    tableBody.appendChild(newRow);
}

// Remove asset row
function removeAssetRow(button) {
    button.closest("tr").remove();
}

// Add item row
function addItemRow() {
    const tableBody = document.querySelector("#items-table tbody");
    const newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td><input type="text" name="item_ref[]" class="form-control" placeholder="Item reference"></td>
        <td><input type="text" name="item_description[]" class="form-control" placeholder="Description"></td>
        <td><input type="number" name="item_quantity[]" class="form-control" placeholder="Qty"></td>
        <td>
            <select name="item_condition[]" class="form-select">
                <option value="">Select...</option>
                <option value="excellent">Excellent</option>
                <option value="good">Good</option>
                <option value="fair">Fair</option>
                <option value="poor">Poor</option>
                <option value="defective">Defective</option>
            </select>
        </td>
        <td>
            <select name="item_result[]" class="form-select">
                <option value="">Select...</option>
                <option value="pass">Pass</option>
                <option value="fail">Fail</option>
                <option value="conditional">Conditional</option>
                <option value="not_tested">Not Tested</option>
            </select>
        </td>
        <td><input type="text" name="item_remarks[]" class="form-control" placeholder="Remarks"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeItemRow(this)">Remove</button></td>
    `;
    tableBody.appendChild(newRow);
}

// Remove item row
function removeItemRow(button) {
    button.closest("tr").remove();
}

// Add consumable row
function addConsumableRow() {
    const tableBody = document.querySelector("#consumables-table tbody");
    const newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td><input type="text" name="consumable_description[]" class="form-control" placeholder="Description"></td>
        <td><input type="number" name="consumable_quantity[]" class="form-control" placeholder="Qty"></td>
        <td><input type="text" name="consumable_unit[]" class="form-control" placeholder="Unit"></td>
        <td><input type="text" name="consumable_batch_number[]" class="form-control" placeholder="Batch no."></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeConsumableRow(this)">Remove</button></td>
    `;
    tableBody.appendChild(newRow);
}

// Remove consumable row
function removeConsumableRow(button) {
    button.closest("tr").remove();
}

// Add personnel row
function addPersonnelRow() {
    const tableBody = document.querySelector("#personnel-table tbody");
    const newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td><input type="text" name="personnel_name[]" class="form-control" placeholder="Name"></td>
        <td><input type="text" name="personnel_company[]" class="form-control" placeholder="Company"></td>
        <td><input type="text" name="personnel_qualification[]" class="form-control" placeholder="Qualification"></td>
        <td><input type="text" name="personnel_responsibilities[]" class="form-control" placeholder="Responsibilities"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removePersonnelRow(this)">Remove</button></td>
    `;
    tableBody.appendChild(newRow);
}

// Remove personnel row
function removePersonnelRow(button) {
    button.closest("tr").remove();
}

// Utility function to get time ago
function getTimeAgo(date) {
    const seconds = Math.floor((new Date() - date) / 1000);

    if (seconds < 60) return "just now";

    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes} minute${minutes !== 1 ? "s" : ""} ago`;

    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours} hour${hours !== 1 ? "s" : ""} ago`;

    return date.toLocaleDateString();
}

// Override form submission to finalize the report
document.addEventListener("DOMContentLoaded", function () {});

// Modal Data Management Functions

// Global arrays to store modal-entered data
let modalAssets = [];
let modalItems = [];
let modalPersonnel = [];
let modalConsumables = [];

// Asset Modal Functions
function saveAsset() {
    const modal = document.getElementById("assetModal");
    const form = modal.querySelector("form");

    // Get form data
    const formData = new FormData(form);
    const asset = {
        id: Date.now(), // Simple ID generation
        reference: formData.get("asset_ref"),
        description: formData.get("asset_description"),
        location: formData.get("asset_location"),
        capacity: formData.get("capacity"),
        lastExamined: formData.get("last_examined"),
        nextDue: formData.get("next_due"),
    };

    // Validation
    if (!asset.reference) {
        showAlert("Please fill in required fields (Reference)", "warning");
        return false;
    }

    // Add to array
    modalAssets.push(asset);

    // Update display
    updateAssetDisplay();

    // Clear form and close modal
    form.reset();
    const bsModal = bootstrap.Modal.getInstance(modal);
    bsModal.hide();

    showAlert("Asset added successfully!", "success");
    return true;
}

function updateAssetDisplay() {
    const noAssetMessage = document.getElementById("noAssetMessage");
    const assetList = document.getElementById("assetList");
    const assetCards = document.getElementById("assetCards");
    const assetInputsContainer = document.getElementById(
        "assetInputsContainer"
    );

    if (modalAssets.length === 0) {
        if (noAssetMessage) noAssetMessage.style.display = "block";
        if (assetList) assetList.style.display = "none";
        if (assetInputsContainer) assetInputsContainer.innerHTML = "";
        return;
    }

    if (noAssetMessage) noAssetMessage.style.display = "none";
    if (assetList) assetList.style.display = "block";

    // Update asset cards
    if (assetCards) {
        assetCards.innerHTML = modalAssets
            .map(
                (asset, index) => `
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-cube me-2"></i>
                            ${asset.reference}
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Description:</strong> ${
                            asset.description || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Location:</strong> ${
                            asset.location || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Capacity:</strong> ${
                            asset.capacity || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Last Examined:</strong> ${
                            asset.lastExamined || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Next Due:</strong> ${
                            asset.nextDue || "Not specified"
                        }</p>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="editAsset(${index})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAsset(${index})">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `
            )
            .join("");
    }

    // Update hidden inputs
    if (assetInputsContainer) {
        assetInputsContainer.innerHTML = modalAssets
            .map(
                (asset, index) => `
            <input type="hidden" name="assets[${index}][reference]" value="${
                    asset.reference
                }">
            <input type="hidden" name="assets[${index}][description]" value="${
                    asset.description || ""
                }">
            <input type="hidden" name="assets[${index}][location]" value="${
                    asset.location || ""
                }">
            <input type="hidden" name="assets[${index}][capacity]" value="${
                    asset.capacity || ""
                }">
            <input type="hidden" name="assets[${index}][last_examined]" value="${
                    asset.lastExamined || ""
                }">
            <input type="hidden" name="assets[${index}][next_due]" value="${
                    asset.nextDue || ""
                }">
        `
            )
            .join("");
    }
}

function editAsset(index) {
    const asset = modalAssets[index];
    const modal = document.getElementById("assetModal");
    const form = modal.querySelector("form");

    // Populate form
    form.asset_ref.value = asset.reference;
    form.asset_description.value = asset.description || "";
    form.asset_location.value = asset.location || "";
    form.capacity.value = asset.capacity || "";
    form.last_examined.value = asset.lastExamined || "";
    form.next_due.value = asset.nextDue || "";

    // Remove from array (will be re-added when saved)
    modalAssets.splice(index, 1);
    updateAssetDisplay();

    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

function removeAsset(index) {
    if (confirm("Are you sure you want to remove this asset?")) {
        modalAssets.splice(index, 1);
        updateAssetDisplay();
        showAlert("Asset removed successfully", "info");
    }
}

// Item Modal Functions
function saveItem() {
    const modal = document.getElementById("itemModal");
    const form = modal.querySelector("form");

    // Get form data
    const formData = new FormData(form);
    const item = {
        id: Date.now(),
        reference: formData.get("item_ref"),
        description: formData.get("item_description"),
        quantity: formData.get("item_quantity"),
        condition: formData.get("item_condition"),
        result: formData.get("item_result"),
        remarks: formData.get("item_remarks"),
    };

    // Validation
    if (!item.reference) {
        showAlert("Please fill in required fields (Reference)", "warning");
        return false;
    }

    // Add to array
    modalItems.push(item);

    // Update display
    updateItemDisplay();

    // Clear form and close modal
    form.reset();
    const bsModal = bootstrap.Modal.getInstance(modal);
    bsModal.hide();

    showAlert("Item added successfully!", "success");
    return true;
}

function updateItemDisplay() {
    const noItemMessage = document.getElementById("noItemMessage");
    const itemList = document.getElementById("itemList");
    const itemCards = document.getElementById("itemCards");
    const itemInputsContainer = document.getElementById("itemInputsContainer");

    if (modalItems.length === 0) {
        if (noItemMessage) noItemMessage.style.display = "block";
        if (itemList) itemList.style.display = "none";
        if (itemInputsContainer) itemInputsContainer.innerHTML = "";
        return;
    }

    if (noItemMessage) noItemMessage.style.display = "none";
    if (itemList) itemList.style.display = "block";

    // Update item cards
    if (itemCards) {
        itemCards.innerHTML = modalItems
            .map(
                (item, index) => `
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-box me-2"></i>
                            ${item.reference}
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Description:</strong> ${
                            item.description || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Quantity:</strong> ${
                            item.quantity || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Condition:</strong> <span class="badge bg-${getConditionColor(
                            item.condition
                        )}">${item.condition || "Not specified"}</span></p>
                        <p class="mb-2"><strong>Result:</strong> <span class="badge bg-${getResultColor(
                            item.result
                        )}">${item.result || "Not specified"}</span></p>
                        ${
                            item.remarks
                                ? `<p class="mb-0"><strong>Remarks:</strong> ${item.remarks}</p>`
                                : ""
                        }
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="editItem(${index})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `
            )
            .join("");
    }

    // Update hidden inputs
    if (itemInputsContainer) {
        itemInputsContainer.innerHTML = modalItems
            .map(
                (item, index) => `
            <input type="hidden" name="items[${index}][reference]" value="${
                    item.reference
                }">
            <input type="hidden" name="items[${index}][description]" value="${
                    item.description || ""
                }">
            <input type="hidden" name="items[${index}][quantity]" value="${
                    item.quantity || ""
                }">
            <input type="hidden" name="items[${index}][condition]" value="${
                    item.condition || ""
                }">
            <input type="hidden" name="items[${index}][result]" value="${
                    item.result || ""
                }">
            <input type="hidden" name="items[${index}][remarks]" value="${
                    item.remarks || ""
                }">
        `
            )
            .join("");
    }
}

function editItem(index) {
    const item = modalItems[index];
    const modal = document.getElementById("itemModal");
    const form = modal.querySelector("form");

    // Populate form
    form.item_ref.value = item.reference;
    form.item_description.value = item.description || "";
    form.item_quantity.value = item.quantity || "";
    form.item_condition.value = item.condition || "";
    form.item_result.value = item.result || "";
    form.item_remarks.value = item.remarks || "";

    // Remove from array (will be re-added when saved)
    modalItems.splice(index, 1);
    updateItemDisplay();

    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

function removeItem(index) {
    if (confirm("Are you sure you want to remove this item?")) {
        modalItems.splice(index, 1);
        updateItemDisplay();
        showAlert("Item removed successfully", "info");
    }
}

// Personnel Modal Functions
function savePersonnel() {
    const modal = document.getElementById("personnelNewModal");
    const form = modal.querySelector("form");

    // Get form data
    const formData = new FormData(form);
    const personnelId = formData.get("personnel_id");

    // Get selected personnel data from global variable
    const selectedPersonnel = window.personnelData
        ? window.personnelData.find((p) => p.id == personnelId)
        : null;

    let personnel;

    if (selectedPersonnel) {
        // Using data from selected personnel dropdown
        console.log("Using selected personnel data:", selectedPersonnel);

        // Check for duplicates
        const isDuplicate = modalPersonnel.some(
            (p) => p.personnelId == personnelId
        );
        if (isDuplicate) {
            showAlert("This personnel member is already assigned", "warning");
            return false;
        }

        personnel = {
            id: Date.now(),
            personnelId: personnelId,
            name: selectedPersonnel.name,
            position: selectedPersonnel.position,
            department: selectedPersonnel.department,
            qualifications: selectedPersonnel.qualifications,
            certifications: selectedPersonnel.certifications,
            contact: selectedPersonnel.contact,
            role: formData.get("personnel_role") || selectedPersonnel.position,
            responsibilities: formData.get("personnel_responsibilities") || "",
            notes: formData.get("personnel_notes") || "",
        };
    } else {
        // Using manually entered data
        console.log("Using manually entered personnel data");

        const manualName = formData.get("personnel_name");
        const manualRole = formData.get("personnel_role");

        if (!manualName || !manualRole) {
            showAlert(
                "Please provide at least Name and Role for the personnel",
                "warning"
            );
            return false;
        }

        personnel = {
            id: Date.now(),
            personnelId: null, // No dropdown selection
            name: manualName,
            position: formData.get("personnel_position") || "",
            department: formData.get("personnel_department") || "",
            qualifications: formData.get("personnel_qualification") || "",
            certifications: formData.get("personnel_certifications") || "",
            contact: formData.get("personnel_contact") || "",
            role: manualRole,
            responsibilities: formData.get("personnel_responsibilities") || "",
            notes: formData.get("personnel_notes") || "",
        };
    }

    // Add to array
    modalPersonnel.push(personnel);

    // Update display
    updatePersonnelDisplay();

    // Clear form and close modal
    form.reset();
    const bsModal = bootstrap.Modal.getInstance(modal);
    bsModal.hide();

    showAlert("Personnel added successfully!", "success");
    return true;
}

function updatePersonnelDisplay() {
    const noPersonnelMessage = document.getElementById("noPersonnelMessage");
    const personnelList = document.getElementById("personnelList");
    const personnelCards = document.getElementById("personnelCards");
    const personnelInputsContainer = document.getElementById(
        "personnelInputsContainer"
    );

    if (modalPersonnel.length === 0) {
        if (noPersonnelMessage) noPersonnelMessage.style.display = "block";
        if (personnelList) personnelList.style.display = "none";
        if (personnelInputsContainer) personnelInputsContainer.innerHTML = "";
        return;
    }

    if (noPersonnelMessage) noPersonnelMessage.style.display = "none";
    if (personnelList) personnelList.style.display = "block";

    // Update personnel cards
    if (personnelCards) {
        personnelCards.innerHTML = modalPersonnel
            .map(
                (person, index) => `
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            ${person.name}
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Company:</strong> ${
                            person.company || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Role:</strong> ${
                            person.role || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Qualification:</strong> ${
                            person.qualification || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Certificate:</strong> ${
                            person.certification || "Not specified"
                        }</p>
                        ${
                            person.certExpiry
                                ? `<p class="mb-2"><strong>Cert. Expiry:</strong> ${person.certExpiry}</p>`
                                : ""
                        }
                        <p class="mb-2"><strong>Responsibilities:</strong> ${
                            person.responsibilities || "Not specified"
                        }</p>
                        ${
                            person.contact
                                ? `<p class="mb-2"><strong>Contact:</strong> ${person.contact}</p>`
                                : ""
                        }
                        ${
                            person.notes
                                ? `<p class="mb-0"><strong>Notes:</strong> ${person.notes}</p>`
                                : ""
                        }
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="editPersonnel(${index})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePersonnel(${index})">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `
            )
            .join("");
    }

    // Update hidden inputs
    if (personnelInputsContainer) {
        personnelInputsContainer.innerHTML = modalPersonnel
            .map(
                (person, index) => `
            <input type="hidden" name="personnel[${index}][name]" value="${
                    person.name
                }">
            <input type="hidden" name="personnel[${index}][company]" value="${
                    person.company || ""
                }">
            <input type="hidden" name="personnel[${index}][qualification]" value="${
                    person.qualification || ""
                }">
            <input type="hidden" name="personnel[${index}][certification]" value="${
                    person.certification || ""
                }">
            <input type="hidden" name="personnel[${index}][cert_expiry]" value="${
                    person.certExpiry || ""
                }">
            <input type="hidden" name="personnel[${index}][role]" value="${
                    person.role || ""
                }">
            <input type="hidden" name="personnel[${index}][responsibilities]" value="${
                    person.responsibilities || ""
                }">
            <input type="hidden" name="personnel[${index}][contact]" value="${
                    person.contact || ""
                }">
            <input type="hidden" name="personnel[${index}][notes]" value="${
                    person.notes || ""
                }">
        `
            )
            .join("");
    }
}

function editPersonnel(index) {
    const person = modalPersonnel[index];
    const modal = document.getElementById("personnelNewModal");
    const form = modal.querySelector("form");

    // Populate form
    form.personnel_name.value = person.name;
    form.personnel_company.value = person.company || "";
    form.personnel_qualification.value = person.qualification || "";
    form.personnel_cert_number.value = person.certification || "";
    form.personnel_cert_expiry.value = person.certExpiry || "";
    form.personnel_role.value = person.role || "";
    form.personnel_responsibilities.value = person.responsibilities || "";
    form.personnel_contact.value = person.contact || "";
    form.personnel_notes.value = person.notes || "";

    // Remove from array (will be re-added when saved)
    modalPersonnel.splice(index, 1);
    updatePersonnelDisplay();

    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

function removePersonnel(index) {
    if (confirm("Are you sure you want to remove this personnel?")) {
        modalPersonnel.splice(index, 1);
        updatePersonnelDisplay();
        showAlert("Personnel removed successfully", "info");
    }
}

// Consumable Modal Functions
function saveConsumable() {
    const modal = document.getElementById("consumableModal");
    const form = modal.querySelector("form");

    // Get form data
    const formData = new FormData(form);
    const consumable = {
        id: Date.now(),
        description: formData.get("consumable_description"),
        quantity: formData.get("consumable_quantity"),
        unit: formData.get("consumable_unit"),
        usedQuantity: formData.get("consumable_used_quantity"),
        remainingQuantity: formData.get("consumable_remaining_quantity"),
        batchNumber: formData.get("consumable_batch_number"),
        manufacturer: formData.get("consumable_manufacturer"),
        productCode: formData.get("consumable_product_code"),
        expiryDate: formData.get("consumable_expiry_date"),
        condition: formData.get("consumable_condition"),
        notes: formData.get("consumable_notes"),
    };

    // Validation
    if (!consumable.description) {
        showAlert("Please fill in the consumable description", "warning");
        return false;
    }

    // Add to array
    modalConsumables.push(consumable);

    // Update display
    updateConsumableDisplay();

    // Clear form and close modal
    form.reset();
    const bsModal = bootstrap.Modal.getInstance(modal);
    bsModal.hide();

    showAlert("Consumable added successfully!", "success");
    return true;
}

function updateConsumableDisplay() {
    const noConsumableMessage = document.getElementById("noConsumableMessage");
    const consumableList = document.getElementById("consumableList");
    const consumableCards = document.getElementById("consumableCards");
    const consumableInputsContainer = document.getElementById(
        "consumableInputsContainer"
    );

    if (modalConsumables.length === 0) {
        if (noConsumableMessage) noConsumableMessage.style.display = "block";
        if (consumableList) consumableList.style.display = "none";
        if (consumableInputsContainer) consumableInputsContainer.innerHTML = "";
        return;
    }

    if (noConsumableMessage) noConsumableMessage.style.display = "none";
    if (consumableList) consumableList.style.display = "block";

    // Update consumable cards
    if (consumableCards) {
        consumableCards.innerHTML = modalConsumables
            .map(
                (consumable, index) => `
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-flask me-2"></i>
                            ${consumable.description}
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Quantity:</strong> ${
                            consumable.quantity || "Not specified"
                        } ${consumable.unit || ""}</p>
                        <p class="mb-2"><strong>Used:</strong> ${
                            consumable.usedQuantity || "0"
                        } ${consumable.unit || ""}</p>
                        <p class="mb-2"><strong>Remaining:</strong> ${
                            consumable.remainingQuantity || "0"
                        } ${consumable.unit || ""}</p>
                        <p class="mb-2"><strong>Batch No:</strong> ${
                            consumable.batchNumber || "Not specified"
                        }</p>
                        <p class="mb-2"><strong>Manufacturer:</strong> ${
                            consumable.manufacturer || "Not specified"
                        }</p>
                        ${
                            consumable.productCode
                                ? `<p class="mb-2"><strong>Product Code:</strong> ${consumable.productCode}</p>`
                                : ""
                        }
                        ${
                            consumable.expiryDate
                                ? `<p class="mb-2"><strong>Expiry:</strong> ${consumable.expiryDate}</p>`
                                : ""
                        }
                        <p class="mb-2"><strong>Condition:</strong> <span class="badge bg-${getConditionColor(
                            consumable.condition
                        )}">${
                    consumable.condition || "Not specified"
                }</span></p>
                        ${
                            consumable.notes
                                ? `<p class="mb-0"><strong>Notes:</strong> ${consumable.notes}</p>`
                                : ""
                        }
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="editConsumable(${index})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeConsumable(${index})">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `
            )
            .join("");
    }

    // Update hidden inputs
    if (consumableInputsContainer) {
        consumableInputsContainer.innerHTML = modalConsumables
            .map(
                (consumable, index) => `
            <input type="hidden" name="consumables[${index}][description]" value="${
                    consumable.description
                }">
            <input type="hidden" name="consumables[${index}][quantity]" value="${
                    consumable.quantity || ""
                }">
            <input type="hidden" name="consumables[${index}][unit]" value="${
                    consumable.unit || ""
                }">
            <input type="hidden" name="consumables[${index}][used_quantity]" value="${
                    consumable.usedQuantity || ""
                }">
            <input type="hidden" name="consumables[${index}][remaining_quantity]" value="${
                    consumable.remainingQuantity || ""
                }">
            <input type="hidden" name="consumables[${index}][batch_number]" value="${
                    consumable.batchNumber || ""
                }">
            <input type="hidden" name="consumables[${index}][manufacturer]" value="${
                    consumable.manufacturer || ""
                }">
            <input type="hidden" name="consumables[${index}][product_code]" value="${
                    consumable.productCode || ""
                }">
            <input type="hidden" name="consumables[${index}][expiry_date]" value="${
                    consumable.expiryDate || ""
                }">
            <input type="hidden" name="consumables[${index}][condition]" value="${
                    consumable.condition || ""
                }">
            <input type="hidden" name="consumables[${index}][notes]" value="${
                    consumable.notes || ""
                }">
        `
            )
            .join("");
    }
}

function editConsumable(index) {
    const consumable = modalConsumables[index];
    const modal = document.getElementById("consumableModal");
    const form = modal.querySelector("form");

    // Populate form
    form.consumable_description.value = consumable.description;
    form.consumable_quantity.value = consumable.quantity || "";
    form.consumable_unit.value = consumable.unit || "";
    form.consumable_used_quantity.value = consumable.usedQuantity || "";
    form.consumable_remaining_quantity.value =
        consumable.remainingQuantity || "";
    form.consumable_batch_number.value = consumable.batchNumber || "";
    form.consumable_manufacturer.value = consumable.manufacturer || "";
    form.consumable_product_code.value = consumable.productCode || "";
    form.consumable_expiry_date.value = consumable.expiryDate || "";
    form.consumable_condition.value = consumable.condition || "";
    form.consumable_notes.value = consumable.notes || "";

    // Remove from array (will be re-added when saved)
    modalConsumables.splice(index, 1);
    updateConsumableDisplay();

    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

function removeConsumable(index) {
    if (confirm("Are you sure you want to remove this consumable?")) {
        modalConsumables.splice(index, 1);
        updateConsumableDisplay();
        showAlert("Consumable removed successfully", "info");
    }
}

// Utility Functions
function getConditionColor(condition) {
    switch (condition?.toLowerCase()) {
        case "excellent":
            return "success";
        case "good":
            return "primary";
        case "fair":
            return "warning";
        case "poor":
            return "danger";
        case "satisfactory":
            return "info";
        default:
            return "secondary";
    }
}

function getResultColor(result) {
    switch (result?.toLowerCase()) {
        case "pass":
        case "passed":
        case "acceptable":
            return "success";
        case "fail":
        case "failed":
        case "rejected":
            return "danger";
        case "conditional":
        case "requires attention":
            return "warning";
        default:
            return "secondary";
    }
}

function showAlert(message, type = "info") {
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText =
        "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Initialize modal event listeners when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    // Asset Modal Save Button
    const assetSaveBtn = document.getElementById("saveAssetBtn");
    if (assetSaveBtn) {
        assetSaveBtn.addEventListener("click", saveAsset);
    }

    // Item Modal Save Button
    const itemSaveBtn = document.getElementById("saveItemBtn");
    if (itemSaveBtn) {
        itemSaveBtn.addEventListener("click", saveItem);
    }

    // Personnel Modal Save Button
    const personnelSaveBtn = document.getElementById("savePersonnelBtn");
    if (personnelSaveBtn) {
        personnelSaveBtn.addEventListener("click", savePersonnel);
    }

    // Consumable Modal Save Button
    const consumableSaveBtn = document.getElementById("saveConsumableBtn");
    if (consumableSaveBtn) {
        consumableSaveBtn.addEventListener("click", saveConsumable);
    }

    // Load personnel data when personnel modal is opened
    const personnelModal = document.getElementById("personnelNewModal");
    if (personnelModal) {
        personnelModal.addEventListener("show.bs.modal", function () {
            // Safely destroy existing Select2 if it exists
            if (checkLibraries()) {
                const $personnelSelect = $("#personnel_id");
                if (
                    $personnelSelect.length &&
                    $personnelSelect.hasClass("select2-hidden-accessible")
                ) {
                    $personnelSelect.select2("destroy");
                }
            }
            loadPersonnelData();
        });

        personnelModal.addEventListener("hidden.bs.modal", function () {
            // Clean up Select2 when modal is closed
            if (checkLibraries()) {
                const $personnelSelect = $("#personnel_id");
                if (
                    $personnelSelect.length &&
                    $personnelSelect.hasClass("select2-hidden-accessible")
                ) {
                    $personnelSelect.select2("destroy");
                }
            }
        });

        // Add change event listener for personnel selection
        const personnelSelect = document.getElementById("personnel_id");
        if (personnelSelect) {
            personnelSelect.addEventListener(
                "change",
                handlePersonnelSelection
            );
        }
    }
});

async function loadPersonnelData() {
    console.log("Loading personnel data...");
    const personnelSelect = document.getElementById("personnel_id");
    if (!personnelSelect) {
        console.error("Personnel select element not found");
        return;
    }

    try {
        console.log("Fetching from /inspections/api/personnel");
        const response = await fetch("/inspections/api/personnel", {
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                Accept: "application/json",
            },
        });
        console.log("Response status:", response.status);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const personnel = await response.json();
        console.log("Personnel data received:", personnel);

        personnelSelect.innerHTML =
            '<option value="">Select personnel...</option>';

        if (Array.isArray(personnel) && personnel.length > 0) {
            // Store personnel data globally for use in save function
            window.personnelData = personnel;

            personnel.forEach((person) => {
                const option = document.createElement("option");
                option.value = person.id;
                option.textContent = `${person.name} - ${person.position}`;
                option.dataset.person = JSON.stringify(person);
                personnelSelect.appendChild(option);
            });
            console.log(`Loaded ${personnel.length} personnel records`);

            // Initialize Select2 for searchable functionality
            setTimeout(() => {
                if (checkLibraries()) {
                    const $personnelSelect = $("#personnel_id");
                    if ($personnelSelect.length) {
                        // Safely destroy existing Select2 if it exists
                        if (
                            $personnelSelect.hasClass(
                                "select2-hidden-accessible"
                            )
                        ) {
                            $personnelSelect.select2("destroy");
                        }

                        $personnelSelect.select2({
                            theme: "bootstrap-5",
                            placeholder: "Search and select personnel...",
                            allowClear: true,
                            width: "100%",
                            dropdownParent: $("#personnelNewModal"),
                        });
                        console.log("Personnel Select2 initialized");
                    }
                } else {
                    console.log(
                        "Personnel dropdown will use standard select (Select2 not available)"
                    );
                }
            }, 100);
        } else {
            personnelSelect.innerHTML =
                '<option value="">No personnel found</option>';
            console.warn("No personnel data available");
        }
    } catch (error) {
        console.error("Error loading personnel data:", error);
        personnelSelect.innerHTML =
            '<option value="">Error loading personnel</option>';
    }
}

// Load inspector data for searchable dropdown
async function loadInspectorData() {
    console.log("Loading inspector data...");
    const inspectorSelect = document.getElementById("leadInspectorName");
    if (!inspectorSelect) {
        console.log(
            "Inspector select element not found - probably not admin user"
        );
        return;
    }

    try {
        console.log("Fetching from /inspections/api/inspectors");
        const response = await fetch("/inspections/api/inspectors", {
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                Accept: "application/json",
            },
        });
        console.log("Response status:", response.status);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const inspectors = await response.json();
        console.log("Inspector data received:", inspectors);

        // Store current selected value if any
        let currentValue = inspectorSelect.value;

        // In edit mode, use the global variable if select doesn't have value yet
        if (window.isEditMode && window.currentInspector && !currentValue) {
            currentValue = window.currentInspector;
        }

        console.log("Current inspector value:", currentValue);
        console.log("Edit mode:", window.isEditMode);

        // Clear existing options
        inspectorSelect.innerHTML =
            '<option value="">Select Inspector...</option>';

        if (Array.isArray(inspectors) && inspectors.length > 0) {
            inspectors.forEach((inspector) => {
                const option = document.createElement("option");
                option.value = inspector.name;
                option.textContent = inspector.display_name;
                option.dataset.inspector = JSON.stringify(inspector);

                // Pre-select if this matches the current value
                if (currentValue && inspector.name === currentValue) {
                    option.selected = true;
                    console.log(
                        "Pre-selected inspector:",
                        inspector.display_name
                    );
                }

                inspectorSelect.appendChild(option);
            });
            console.log(`Loaded ${inspectors.length} inspector records`);

            // Initialize Select2 for searchable functionality
            setTimeout(() => {
                if (checkLibraries()) {
                    const $inspectorSelect = $("#leadInspectorName");
                    if ($inspectorSelect.length) {
                        // Safely destroy existing Select2 if it exists
                        if (
                            $inspectorSelect.hasClass(
                                "select2-hidden-accessible"
                            )
                        ) {
                            $inspectorSelect.select2("destroy");
                        }

                        $inspectorSelect.select2({
                            theme: "bootstrap-5",
                            placeholder: "Search and select inspector...",
                            allowClear: true,
                            width: "100%",
                        });

                        // Ensure the pre-selected value is maintained
                        if (currentValue) {
                            $inspectorSelect
                                .val(currentValue)
                                .trigger("change");

                            // Also populate certification in edit mode
                            if (
                                window.isEditMode &&
                                window.currentInspectorCertification
                            ) {
                                const qualificationField =
                                    document.getElementById(
                                        "leadInspectorCertification"
                                    );
                                if (
                                    qualificationField &&
                                    !qualificationField.value
                                ) {
                                    qualificationField.value =
                                        window.currentInspectorCertification;
                                }
                            }
                        }

                        console.log(
                            "Inspector Select2 initialized with value:",
                            $inspectorSelect.val()
                        );
                    }
                } else {
                    console.log(
                        "Inspector dropdown will use standard select (Select2 not available)"
                    );
                }
            }, 100);
        } else {
            inspectorSelect.innerHTML =
                '<option value="">No inspectors found</option>';
            console.warn("No inspector data available");
        }
    } catch (error) {
        console.error("Error loading inspector data:", error);
        inspectorSelect.innerHTML =
            '<option value="">Error loading inspectors</option>';
    }
}

// Handle inspector selection and auto-populate qualification
function handleInspectorSelection() {
    const inspectorSelect = document.getElementById("leadInspectorName");
    const qualificationField = document.getElementById(
        "leadInspectorCertification"
    );

    if (!inspectorSelect || !qualificationField) {
        return;
    }

    inspectorSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.inspector) {
            try {
                const inspector = JSON.parse(selectedOption.dataset.inspector);
                qualificationField.value = inspector.certification || "";
                console.log(
                    "Auto-populated qualification for:",
                    inspector.name
                );
            } catch (error) {
                console.error("Error parsing inspector data:", error);
                qualificationField.value = "";
            }
        } else {
            qualificationField.value = "";
        }
    });
}

// Handle personnel selection from dropdown
function handlePersonnelSelection(event) {
    const selectedOption = event.target.selectedOptions[0];
    if (!selectedOption || !selectedOption.dataset.person) {
        clearPersonnelFields();
        return;
    }

    const person = JSON.parse(selectedOption.dataset.person);

    // Populate form fields with selected personnel data
    document.getElementById("personnel_name").value = person.name;
    document.getElementById("personnel_position").value = person.position || "";
    document.getElementById("personnel_department").value =
        person.department || "";
    document.getElementById("personnel_contact").value = person.contact || "";
    document.getElementById("personnel_qualification").value =
        person.qualifications || "";
    document.getElementById("personnel_certifications").value =
        person.certifications || "";
}

// Clear personnel form fields
function clearPersonnelFields() {
    document.getElementById("personnel_name").value = "";
    document.getElementById("personnel_position").value = "";
    document.getElementById("personnel_department").value = "";
    document.getElementById("personnel_contact").value = "";
    document.getElementById("personnel_qualification").value = "";
    document.getElementById("personnel_certifications").value = "";
}

// ==================== CLIENT MANAGEMENT FUNCTIONS ====================

// Load client data for dropdown
async function loadClientData() {
    console.log("Loading client data...");
    const clientSelect = document.getElementById("clientSelect");
    if (!clientSelect) {
        console.log(
            "Client select element not found - probably not admin user"
        );
        return;
    }

    try {
        console.log("Fetching from /inspections/api/clients");
        const response = await fetch("/inspections/api/clients", {
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                Accept: "application/json",
            },
        });
        console.log("Response status:", response.status);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const clients = await response.json();
        console.log("Client data received:", clients);

        // Store current selected value if any
        let currentValue = clientSelect.value;

        // In edit mode, use the global variable if select doesn't have value yet
        if (window.isEditMode && window.currentClient && !currentValue) {
            currentValue = window.currentClient;
        }

        console.log("Current client value:", currentValue);

        // Clear existing options
        clientSelect.innerHTML =
            '<option value="">Search and select client...</option>';

        if (Array.isArray(clients) && clients.length > 0) {
            clients.forEach((client) => {
                const option = document.createElement("option");
                option.value = client.client_name;
                option.textContent = client.display_name;
                option.dataset.client = JSON.stringify(client);

                // Pre-select if this matches the current value
                if (currentValue && client.client_name === currentValue) {
                    option.selected = true;
                    console.log("Pre-selected client:", client.display_name);
                }

                clientSelect.appendChild(option);
            });
            console.log(`Loaded ${clients.length} client records`);

            // Initialize Select2 for searchable functionality
            setTimeout(() => {
                if (checkLibraries()) {
                    const $clientSelect = $("#clientSelect");
                    if ($clientSelect.length) {
                        // Safely destroy existing Select2 if it exists
                        if (
                            $clientSelect.hasClass("select2-hidden-accessible")
                        ) {
                            $clientSelect.select2("destroy");
                        }

                        $clientSelect.select2({
                            theme: "bootstrap-5",
                            placeholder: "Search and select client...",
                            allowClear: true,
                            width: "100%",
                        });

                        // Ensure the pre-selected value is maintained
                        if (currentValue) {
                            $clientSelect.val(currentValue).trigger("change");
                        }

                        console.log(
                            "Client Select2 initialized with value:",
                            $clientSelect.val()
                        );
                    }
                } else {
                    console.log(
                        "Client dropdown will use standard select (Select2 not available)"
                    );
                }
            }, 100);
        } else {
            clientSelect.innerHTML =
                '<option value="">No clients found</option>';
            console.warn("No client data available");
        }
    } catch (error) {
        console.error("Error loading client data:", error);
        clientSelect.innerHTML =
            '<option value="">Error loading clients</option>';
    }
}

// Handle client selection and auto-populate fields
function handleClientSelection() {
    const clientSelect = document.getElementById("clientSelect");
    if (!clientSelect) {
        return;
    }

    clientSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.client) {
            try {
                const client = JSON.parse(selectedOption.dataset.client);
                console.log("Selected client:", client);

                // Auto-populate client-related fields
                populateClientFields(client);
            } catch (error) {
                console.error("Error parsing client data:", error);
            }
        } else {
            // Clear fields if no client selected
            clearClientFields();
        }
    });
}

// Populate form fields with client data
function populateClientFields(client) {
    console.log("Populating client fields:", client);

    // Basic client information
    const clientNameField = document.getElementById("clientName");
    if (clientNameField) {
        clientNameField.value = client.client_name || "";
    }

    // If location field should be populated from client address
    const locationField = document.getElementById("location");
    if (locationField && client.full_address) {
        // Only populate if field is empty
        if (!locationField.value.trim()) {
            locationField.value = client.full_address;
        }
    }

    // Additional fields that might be added to the form later
    const fields = [
        { fieldId: "client_phone", clientField: "phone" },
        { fieldId: "client_email", clientField: "email" },
        { fieldId: "client_contact_person", clientField: "contact_person" },
        { fieldId: "client_contact_email", clientField: "contact_email" },
        { fieldId: "client_address", clientField: "full_address" },
    ];

    fields.forEach(({ fieldId, clientField }) => {
        const field = document.getElementById(fieldId);
        if (field && client[clientField]) {
            field.value = client[clientField];
        }
    });

    console.log("Client fields populated successfully");
}

// Clear client-related fields
function clearClientFields() {
    console.log("Clearing client fields");

    const fieldsToKeep = ["clientName"]; // Fields that shouldn't be cleared
    const fieldsToClear = [
        "client_phone",
        "client_email",
        "client_contact_person",
        "client_contact_email",
        "client_address",
    ];

    fieldsToClear.forEach((fieldId) => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.value = "";
        }
    });
}
