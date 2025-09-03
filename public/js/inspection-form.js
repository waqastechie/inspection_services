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

// Auto-save and draft management variables
let autoSaveTimeout;
let localStorageTimeout;
let lastSaveTime = null;
let currentDraftId = null;
let isSubmitted = false;

// Local storage keys
const LOCAL_STORAGE_KEYS = {
    FORM_DATA: "inspection_form_data",
    SELECTED_SERVICES: "inspection_selected_services",
    PERSONNEL_ASSIGNMENTS: "inspection_personnel_assignments",
    EQUIPMENT_ASSIGNMENTS: "inspection_equipment_assignments",
    CONSUMABLE_ASSIGNMENTS: "inspection_consumable_assignments",
    IMAGES: "inspection_images",
    TIMESTAMP: "inspection_last_save",
    DRAFT_ID: "inspection_draft_id",
};

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
            loadInspectorData();
        }, 500);
    } else {
        loadClientData();
        handleClientSelection();
        loadInspectorData();
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
// Initialize auto-save and restoration
function initializeAutoSave() {
    console.log("Initializing auto-save...");

    // Try to restore data automatically
    restoreFormData();

    const form = document.getElementById("liftingInspectionForm");
    console.log("Form found:", form);
    if (form) {
        // Add listeners for auto-save and progress tracking
        form.addEventListener("input", debounceAutoSave);
        form.addEventListener("change", debounceAutoSave);

        // Add form submission handler with AJAX and loader
        form.addEventListener("submit", async function (e) {
            // Prevent default form submission
            e.preventDefault();

            console.log("=== FORM SUBMIT EVENT TRIGGERED ===");
            console.log("Current URL:", window.location.href);
            console.log("Form action:", form.action);
            console.log("Form method:", form.method);

            try {
                // Show loading state immediately
                showSubmissionLoader();

                // Validate required fields
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
                    hideSubmissionLoader();
                    showErrorMessage(
                        "Please fill in all required fields: Client Name, Location, and Inspection Date."
                    );
                    return;
                }

                console.log("VALIDATION PASSED: All required fields present");

                // Prepare form data
                const formData = new FormData(form);

                // Add any additional data from modals/dynamic sections
                addDynamicDataToFormData(formData);

                // Submit form via AJAX
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN":
                            document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute("content") || "",
                        "X-Requested-With": "XMLHttpRequest", // This helps Laravel detect AJAX requests
                    },
                });

                if (response.ok) {
                    // Check if response is JSON or HTML
                    const contentType = response.headers.get("content-type");

                    if (
                        contentType &&
                        contentType.includes("application/json")
                    ) {
                        const result = await response.json();

                        if (result.success) {
                            console.log("Form submitted successfully:", result);
                            showSuccessMessage(
                                "Inspection report saved successfully!"
                            );

                            // Mark as submitted and clear drafts
                            isSubmitted = true;
                            clearLocalStorage();

                            // Redirect after a short delay
                            setTimeout(() => {
                                if (result.redirect_url) {
                                    window.location.href = result.redirect_url;
                                } else {
                                    window.location.href = "/inspections";
                                }
                            }, 2000);
                        } else {
                            throw new Error(
                                result.message || "Submission failed"
                            );
                        }
                    } else {
                        // Handle HTML response (likely a redirect or success page)
                        console.log(
                            "Form submitted successfully - HTML response"
                        );
                        showSuccessMessage(
                            "Inspection report saved successfully!"
                        );

                        // Mark as submitted and clear drafts
                        isSubmitted = true;
                        clearLocalStorage();

                        // Get the response as text and check if it's a redirect
                        const responseText = await response.text();

                        // Create a temporary div to parse the response
                        const tempDiv = document.createElement("div");
                        tempDiv.innerHTML = responseText;

                        // Check if the response contains success indicators
                        const hasSuccess =
                            tempDiv.textContent
                                .toLowerCase()
                                .includes("success") ||
                            tempDiv.textContent
                                .toLowerCase()
                                .includes("saved") ||
                            response.status === 200;

                        if (hasSuccess) {
                            // Redirect after showing success
                            setTimeout(() => {
                                window.location.href = "/inspections";
                            }, 2000);
                        } else {
                            // If it's an error page, show the content
                            document.body.innerHTML = responseText;
                        }
                    }
                } else {
                    // Handle HTTP errors
                    const errorText = await response.text();
                    console.error("HTTP Error:", response.status, errorText);

                    if (response.status === 422) {
                        // Validation errors
                        try {
                            const errorData = JSON.parse(errorText);
                            if (errorData.errors) {
                                const errorMessages = Object.values(
                                    errorData.errors
                                ).flat();
                                showErrorMessage(
                                    "Validation errors:\n" +
                                        errorMessages.join("\n")
                                );
                            } else {
                                showErrorMessage(
                                    "Validation failed. Please check your input and try again."
                                );
                            }
                        } catch (e) {
                            showErrorMessage(
                                "Validation failed. Please check your input and try again."
                            );
                        }
                    } else {
                        showErrorMessage(
                            "Error saving inspection: " +
                                (response.statusText || "Unknown error")
                        );
                    }

                    hideSubmissionLoader();
                }
            } catch (error) {
                console.error("ERROR in form submission:", error);
                hideSubmissionLoader();

                // Show fallback option
                const fallbackMessage = `Error submitting form: ${error.message}\n\nWould you like to try submitting normally (page will refresh)?`;
                if (confirm(fallbackMessage)) {
                    // Submit form normally as fallback
                    form.removeEventListener("submit", arguments.callee);
                    form.submit();
                } else {
                    showErrorMessage(
                        "Form submission cancelled. Please try again or contact support if the problem persists."
                    );
                }
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

    // Add clear data button if there's saved data
    setTimeout(() => {
        addClearDataButton();
    }, 1000);
}

// Debounced auto-save (saves 2 seconds after user stops typing)
function debounceAutoSave() {
    clearTimeout(autoSaveTimeout);
    clearTimeout(localStorageTimeout);

    // Save to local storage immediately (faster)
    localStorageTimeout = setTimeout(() => {
        try {
            const formData = getFormData();
            saveToLocalStorage(formData);
        } catch (error) {
            console.error("Local storage save error:", error);
        }
    }, 500); // Save locally after 500ms

    // Save to server after longer delay
    autoSaveTimeout = setTimeout(autoSave, 2000);

    // Update form progress immediately when user types
    updateFormProgress();
}

// Auto-save function (enhanced with database storage)
async function autoSave() {
    if (isSubmitted) return; // Don't auto-save if already submitted

    try {
        // Get comprehensive form data
        const formData = getFormData();
        const selectedServicesData = getAllSelectedServices();
        const personnelAssignments = getAllPersonnelAssignments();
        const equipmentAssignments = getAllEquipmentAssignments();
        const consumableAssignments = getAllConsumableAssignments();
        const uploadedImages = getAllUploadedImages();
        const serviceSectionsData = getAllServiceSectionsData();

        // Always save to local storage as backup
        saveToLocalStorage(formData);

        // Prepare data for database
        const draftData = {
            draft_id: currentDraftId,
            form_data: formData,
            selected_services: selectedServicesData,
            personnel_assignments: personnelAssignments,
            equipment_assignments: equipmentAssignments,
            consumable_assignments: consumableAssignments,
            uploaded_images: uploadedImages,
            service_sections_data: serviceSectionsData,
        };

        updateAutoSaveStatus("Saving...");

        const response = await fetch("/inspections/save-draft", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
            body: JSON.stringify(draftData),
        });

        const result = await response.json();

        if (result.success) {
            if (result.draft_id && !currentDraftId) {
                currentDraftId = result.draft_id;
                // Save the draft ID to local storage
                localStorage.setItem(
                    LOCAL_STORAGE_KEYS.DRAFT_ID,
                    currentDraftId
                );
            }

            lastSaveTime = new Date();
            updateAutoSaveStatus("Saved");
            updateDraftStatus(`Draft: ${currentDraftId}`);
            updateUIState();

            console.log("Auto-save successful:", result);
        } else {
            updateAutoSaveStatus("Save failed - Using local backup");
            console.error("Auto-save failed:", result.message);
        }
    } catch (error) {
        updateAutoSaveStatus("Server error - Using local backup");
        console.error("Auto-save error:", error);

        // If server save fails, at least save to local storage
        try {
            const formData = getFormData();
            saveToLocalStorage(formData);
        } catch (localError) {
            console.error("Local storage save also failed:", localError);
        }
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

// ==================== LOCAL STORAGE FUNCTIONS ====================

// Save form data to local storage
function saveToLocalStorage(formData) {
    try {
        // Save basic form data
        localStorage.setItem(
            LOCAL_STORAGE_KEYS.FORM_DATA,
            JSON.stringify(formData)
        );

        // Save selected services
        localStorage.setItem(
            LOCAL_STORAGE_KEYS.SELECTED_SERVICES,
            JSON.stringify(selectedServices)
        );

        // Save personnel assignments
        if (window.personnelAssignments) {
            localStorage.setItem(
                LOCAL_STORAGE_KEYS.PERSONNEL_ASSIGNMENTS,
                JSON.stringify(window.personnelAssignments)
            );
        }

        // Save equipment assignments
        if (window.equipmentAssignments) {
            localStorage.setItem(
                LOCAL_STORAGE_KEYS.EQUIPMENT_ASSIGNMENTS,
                JSON.stringify(window.equipmentAssignments)
            );
        }

        // Save consumable assignments
        if (window.consumableAssignments) {
            localStorage.setItem(
                LOCAL_STORAGE_KEYS.CONSUMABLE_ASSIGNMENTS,
                JSON.stringify(window.consumableAssignments)
            );
        }

        // Save uploaded images
        if (window.uploadedImages) {
            localStorage.setItem(
                LOCAL_STORAGE_KEYS.IMAGES,
                JSON.stringify(window.uploadedImages)
            );
        }

        // Save timestamp
        localStorage.setItem(
            LOCAL_STORAGE_KEYS.TIMESTAMP,
            new Date().toISOString()
        );

        console.log("Form data saved to local storage successfully");

        // Show notification
        showLocalStorageNotification("Auto-saved locally", "success");
    } catch (error) {
        console.error("Failed to save to local storage:", error);
        showLocalStorageNotification("Local save failed", "error");
    }
}

// Automatic form data restoration (database first, then local storage fallback)
async function restoreFormData() {
    try {
        console.log("Attempting to restore form data...");

        // First, check for saved draft ID in local storage
        const savedDraftId = localStorage.getItem(LOCAL_STORAGE_KEYS.DRAFT_ID);

        if (savedDraftId) {
            console.log("Found draft ID in local storage:", savedDraftId);

            // Try to restore from database
            const restored = await restoreFromDatabase(savedDraftId);
            if (restored) {
                console.log("Successfully restored from database");
                return true;
            } else {
                console.log(
                    "Database restoration failed, trying local storage..."
                );
            }
        }

        // Fallback to local storage
        const localRestored = restoreFromLocalStorage();
        if (localRestored) {
            console.log("Successfully restored from local storage");
            return true;
        }

        console.log("No saved data found to restore");
        return false;
    } catch (error) {
        console.error("Error during restoration:", error);
        // Try local storage as final fallback
        return restoreFromLocalStorage();
    }
}

// Restore form data from database
async function restoreFromDatabase(draftId) {
    try {
        const response = await fetch(`/inspections/get-draft/${draftId}`, {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
        });

        if (!response.ok) {
            console.log("Draft not found in database");
            return false;
        }

        const result = await response.json();

        if (result.success && result.draft) {
            const draft = result.draft;
            currentDraftId = draft.draft_id;

            // Restore form fields
            if (draft.form_data) {
                restoreFormFields(draft.form_data);
            }

            // Restore selected services
            if (draft.selected_services && draft.selected_services.length > 0) {
                selectedServices = draft.selected_services;
                restoreSelectedServices();
            }

            // Restore personnel assignments
            if (
                draft.personnel_assignments &&
                draft.personnel_assignments.length > 0
            ) {
                window.personnelAssignments = draft.personnel_assignments;
                if (typeof updatePersonnelDisplay === "function") {
                    updatePersonnelDisplay();
                }
            }

            // Restore equipment assignments
            if (
                draft.equipment_assignments &&
                draft.equipment_assignments.length > 0
            ) {
                window.equipmentAssignments = draft.equipment_assignments;
                if (typeof updateEquipmentDisplay === "function") {
                    updateEquipmentDisplay();
                }
            }

            // Restore consumable assignments
            if (
                draft.consumable_assignments &&
                draft.consumable_assignments.length > 0
            ) {
                window.consumableAssignments = draft.consumable_assignments;
                if (typeof updateConsumableDisplay === "function") {
                    updateConsumableDisplay();
                }
            }

            // Restore uploaded images
            if (draft.uploaded_images && draft.uploaded_images.length > 0) {
                window.uploadedImages = draft.uploaded_images;
                if (typeof updateImageDisplay === "function") {
                    updateImageDisplay();
                }
            }

            // Restore service sections data (MPI, etc.)
            if (draft.service_sections_data) {
                restoreServiceSectionsData(draft.service_sections_data);
            }

            console.log("Form data restored from database successfully");
            showLocalStorageNotification(
                "Previous work restored automatically!",
                "success"
            );

            // Update form progress after restoration
            setTimeout(() => {
                updateFormProgress();
            }, 1000);

            return true;
        }

        return false;
    } catch (error) {
        console.error("Error restoring from database:", error);
        return false;
    }
}

// Restore form data from local storage (fallback)
function restoreFromLocalStorage() {
    try {
        // Check if we have saved data
        const savedData = localStorage.getItem(LOCAL_STORAGE_KEYS.FORM_DATA);
        const savedTimestamp = localStorage.getItem(
            LOCAL_STORAGE_KEYS.TIMESTAMP
        );

        if (!savedData || !savedTimestamp) {
            console.log("No saved form data found in local storage");
            return false;
        }

        // Check if the saved data is not too old (limit to 7 days)
        const saveDate = new Date(savedTimestamp);
        const now = new Date();
        const daysDiff = (now - saveDate) / (1000 * 60 * 60 * 24);

        if (daysDiff > 7) {
            console.log("Saved data is too old, clearing local storage");
            clearLocalStorage();
            return false;
        }

        // Automatically restore without prompting
        console.log("Automatically restoring from local storage...");

        // Parse and restore form data
        const formData = JSON.parse(savedData);
        restoreFormFields(formData);

        // Restore selected services
        const savedServices = localStorage.getItem(
            LOCAL_STORAGE_KEYS.SELECTED_SERVICES
        );
        if (savedServices) {
            selectedServices = JSON.parse(savedServices);
            restoreSelectedServices();
        }

        // Restore personnel assignments
        const savedPersonnel = localStorage.getItem(
            LOCAL_STORAGE_KEYS.PERSONNEL_ASSIGNMENTS
        );
        if (savedPersonnel) {
            window.personnelAssignments = JSON.parse(savedPersonnel);
            if (typeof updatePersonnelDisplay === "function") {
                updatePersonnelDisplay();
            }
        }

        // Restore equipment assignments
        const savedEquipment = localStorage.getItem(
            LOCAL_STORAGE_KEYS.EQUIPMENT_ASSIGNMENTS
        );
        if (savedEquipment) {
            window.equipmentAssignments = JSON.parse(savedEquipment);
            if (typeof updateEquipmentDisplay === "function") {
                updateEquipmentDisplay();
            }
        }

        // Restore consumable assignments
        const savedConsumables = localStorage.getItem(
            LOCAL_STORAGE_KEYS.CONSUMABLE_ASSIGNMENTS
        );
        if (savedConsumables) {
            window.consumableAssignments = JSON.parse(savedConsumables);
            if (typeof updateConsumableDisplay === "function") {
                updateConsumableDisplay();
            }
        }

        // Restore uploaded images
        const savedImages = localStorage.getItem(LOCAL_STORAGE_KEYS.IMAGES);
        if (savedImages) {
            window.uploadedImages = JSON.parse(savedImages);
            if (typeof updateImageDisplay === "function") {
                updateImageDisplay();
            }
        }

        // Restore draft ID if available
        const savedDraftId = localStorage.getItem(LOCAL_STORAGE_KEYS.DRAFT_ID);
        if (savedDraftId) {
            currentDraftId = savedDraftId;
        }

        console.log("Form data restored from local storage successfully");
        showLocalStorageNotification(
            "Previous work restored from local backup!",
            "info"
        );

        // Update form progress after restoration
        setTimeout(() => {
            updateFormProgress();
        }, 1000);

        return true;
    } catch (error) {
        console.error("Failed to restore from local storage:", error);
        showLocalStorageNotification("Failed to restore saved data", "error");
        return false;
    }
}

// Restore form fields from saved data
function restoreFormFields(formData) {
    const form = document.getElementById("liftingInspectionForm");
    if (!form) return;

    console.log("Restoring form fields:", formData);

    Object.keys(formData).forEach((fieldName) => {
        const value = formData[fieldName];

        // Skip empty values
        if (value === null || value === undefined || value === "") return;

        // Handle array fields (multiple inputs with same name)
        const fields = form.querySelectorAll(`[name="${fieldName}"]`);

        if (fields.length > 1) {
            // Multiple fields with same name (like checkboxes or radio buttons)
            fields.forEach((field, index) => {
                if (Array.isArray(value)) {
                    if (index < value.length) {
                        setFieldValue(field, value[index]);
                    }
                } else {
                    if (field.type === "checkbox" || field.type === "radio") {
                        field.checked = field.value === value;
                    }
                }
            });
        } else if (fields.length === 1) {
            // Single field
            setFieldValue(fields[0], value);
        } else {
            // Try to find field with different selectors
            const alternativeField =
                form.querySelector(`#${fieldName}`) ||
                form.querySelector(`[data-field="${fieldName}"]`);
            if (alternativeField) {
                setFieldValue(alternativeField, value);
            }
        }
    });

    console.log("Form fields restoration completed");
}

// Helper function to set field value based on field type
function setFieldValue(field, value) {
    try {
        if (field.type === "checkbox") {
            field.checked = Boolean(value);
        } else if (field.type === "radio") {
            field.checked = field.value === value;
        } else if (field.tagName.toLowerCase() === "select") {
            field.value = value;
            // Trigger change event for any dependent fields
            field.dispatchEvent(new Event("change", { bubbles: true }));
        } else {
            field.value = value;
        }

        // Trigger input event for auto-save detection
        field.dispatchEvent(new Event("input", { bubbles: true }));

        console.log(`Restored field ${field.name || field.id} = ${value}`);
    } catch (error) {
        console.warn(
            `Failed to restore field ${field.name || field.id}:`,
            error
        );
    }
}

// Restore selected services
function restoreSelectedServices() {
    if (!selectedServices || selectedServices.length === 0) return;

    selectedServices.forEach((serviceType) => {
        // Find service checkbox by value
        const checkbox = document.querySelector(
            `input[name="selected_services[]"][value="${serviceType}"]`
        );
        if (checkbox) {
            checkbox.checked = true;
            console.log(`Restored service: ${serviceType}`);

            // Trigger change event to show service sections
            checkbox.dispatchEvent(new Event("change", { bubbles: true }));
        } else {
            console.warn(`Service checkbox not found for: ${serviceType}`);
        }
    });

    // Update services display after a delay to ensure DOM is ready
    setTimeout(() => {
        if (typeof updateServiceSections === "function") {
            updateServiceSections();
        }

        // Try to trigger any service-related updates
        const serviceCheckboxes = document.querySelectorAll(
            'input[name="selected_services[]"]:checked'
        );
        serviceCheckboxes.forEach((checkbox) => {
            checkbox.dispatchEvent(new Event("change", { bubbles: true }));
        });
    }, 500);
}

// Clear all local storage data
function clearLocalStorage() {
    Object.values(LOCAL_STORAGE_KEYS).forEach((key) => {
        localStorage.removeItem(key);
    });
    console.log("Local storage cleared");
}

// Show local storage notification
function showLocalStorageNotification(message, type = "info") {
    // Update auto-save status with local storage message
    const statusElement = document.getElementById("autoSaveStatus");
    if (statusElement) {
        const now = new Date();
        const timeString = now.toLocaleTimeString();

        if (type === "success") {
            statusElement.innerHTML = `<i class="fas fa-check-circle text-success me-1"></i>${message} (${timeString})`;
        } else if (type === "error") {
            statusElement.innerHTML = `<i class="fas fa-exclamation-triangle text-warning me-1"></i>${message} (${timeString})`;
        } else {
            statusElement.innerHTML = `<i class="fas fa-info-circle text-info me-1"></i>${message} (${timeString})`;
        }

        // Reset to normal after 3 seconds
        setTimeout(() => {
            statusElement.innerHTML =
                '<i class="fas fa-clock me-1"></i>Auto-save enabled';
        }, 3000);
    }

    // Try to use existing toast system if available
    if (typeof showToast === "function") {
        showToast(message, type);
        return;
    }

    // Log to console
    if (type === "error") {
        console.error(message);
    } else {
        console.log(message);
    }
}

// Add event listener for before page unload to save data
window.addEventListener("beforeunload", function (e) {
    if (!isSubmitted) {
        try {
            const formData = getFormData();
            saveToLocalStorage(formData);
        } catch (error) {
            console.error("Failed to save data before page unload:", error);
        }
    }
});

// Add button to manually clear saved data
function addClearDataButton() {
    const form = document.getElementById("liftingInspectionForm");
    if (!form) return;

    // Check if there's saved data
    const hasSavedData = localStorage.getItem(LOCAL_STORAGE_KEYS.FORM_DATA);
    if (!hasSavedData) return;

    // Create clear data button
    const clearButton = document.createElement("button");
    clearButton.type = "button";
    clearButton.className = "btn btn-outline-warning btn-sm";
    clearButton.innerHTML =
        '<i class="fas fa-trash-alt me-2"></i>Clear Saved Data';
    clearButton.title = "Clear locally saved form data";

    clearButton.addEventListener("click", function () {
        if (
            confirm(
                "Are you sure you want to clear all locally saved form data? This cannot be undone."
            )
        ) {
            clearLocalStorage();
            showLocalStorageNotification(
                "Saved data cleared successfully",
                "success"
            );
            this.remove(); // Remove the button
        }
    });

    // Add button to form header or auto-save status area
    const statusElement = document.getElementById("autoSaveStatus");
    if (statusElement && statusElement.parentNode) {
        const buttonContainer = document.createElement("div");
        buttonContainer.className = "mt-2";
        buttonContainer.appendChild(clearButton);
        statusElement.parentNode.appendChild(buttonContainer);
    }
}

// ==================== END LOCAL STORAGE FUNCTIONS ====================

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
                console.log("Added personnel option:", person.name, person);
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

                        // Add Select2 event handlers for personnel selection
                        $personnelSelect.on("select2:select", function (e) {
                            const selectedData = e.params.data;
                            const selectedOption =
                                personnelSelect.querySelector(
                                    `option[value="${selectedData.id}"]`
                                );
                            if (
                                selectedOption &&
                                selectedOption.dataset.person
                            ) {
                                const person = JSON.parse(
                                    selectedOption.dataset.person
                                );
                                console.log(
                                    "Personnel selected via Select2:",
                                    person
                                );
                                populatePersonnelFields(person);
                            }
                        });

                        $personnelSelect.on("select2:clear", function (e) {
                            console.log("Personnel selection cleared");
                            clearPersonnelFields();
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

// Load inspector data for service sections
async function loadInspectorData() {
    console.log("Loading inspector data for service sections...");

    const inspectorSelects = [
        "liftingInspector",
        "loadTestInspector",
        "mpiInspector",
        "thoroughExamInspector",
        "visualInspector",
    ];

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

        // Populate all inspector dropdowns
        inspectorSelects.forEach((selectId) => {
            const inspectorSelect = document.getElementById(selectId);
            if (!inspectorSelect) {
                console.warn(`Inspector select element ${selectId} not found`);
                return;
            }

            inspectorSelect.innerHTML =
                '<option value="">Select Inspector...</option>';

            if (Array.isArray(inspectors) && inspectors.length > 0) {
                inspectors.forEach((inspector) => {
                    const option = document.createElement("option");
                    option.value = inspector.id;
                    option.textContent = `${inspector.name} - ${inspector.role}`;
                    option.dataset.inspector = JSON.stringify(inspector);
                    inspectorSelect.appendChild(option);
                });
                console.log(
                    `Loaded ${inspectors.length} inspectors for ${selectId}`
                );
            } else {
                inspectorSelect.innerHTML =
                    '<option value="">No inspectors found</option>';
                console.warn("No inspector data available");
            }
        });
    } catch (error) {
        console.error("Error loading inspector data:", error);
        inspectorSelects.forEach((selectId) => {
            const inspectorSelect = document.getElementById(selectId);
            if (inspectorSelect) {
                inspectorSelect.innerHTML =
                    '<option value="">Error loading inspectors</option>';
            }
        });
    }
}

// Handle personnel selection from dropdown
function handlePersonnelSelection(event) {
    const selectedOption = event.target.selectedOptions[0];
    if (!selectedOption || !selectedOption.dataset.person) {
        clearPersonnelFields();
        return;
    }

    const person = JSON.parse(selectedOption.dataset.person);
    console.log("Personnel selected via standard change:", person);
    populatePersonnelFields(person);
}

// Populate personnel form fields with selected data
function populatePersonnelFields(person) {
    const fields = {
        personnel_name: person.name || "",
        personnel_position: person.position || "",
        personnel_department: person.department || "",
        personnel_contact: person.contact || person.phone || person.email || "",
        personnel_qualification:
            person.qualifications || person.qualification || "",
        personnel_certifications:
            person.certifications || person.certification || "",
    };

    console.log("Populating personnel fields:", fields);

    Object.entries(fields).forEach(([fieldId, value]) => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.value = value;
            console.log(`Set ${fieldId} to: ${value}`);
        } else {
            console.warn(`Field ${fieldId} not found`);
        }
    });
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
            console.log(`✓ Loaded ${clients.length} client records`);

            // Show success message to user
            const clientSelectContainer = clientSelect.closest(".mb-3");
            if (clientSelectContainer) {
                const existingMessage = clientSelectContainer.querySelector(
                    ".client-load-status"
                );
                if (existingMessage) existingMessage.remove();

                const statusMessage = document.createElement("small");
                statusMessage.className = "client-load-status text-success";
                statusMessage.innerHTML = `<i class="fas fa-check me-1"></i>${clients.length} clients loaded successfully`;
                clientSelectContainer.appendChild(statusMessage);

                setTimeout(() => {
                    if (statusMessage.parentNode) statusMessage.remove();
                }, 3000);
            }

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

                        // Bind Select2 change event to trigger client population
                        $clientSelect.on("select2:select", function (e) {
                            console.log(
                                "Select2 client selected:",
                                e.params.data
                            );
                            const option = this.options[this.selectedIndex];
                            if (option && option.dataset.client) {
                                try {
                                    const client = JSON.parse(
                                        option.dataset.client
                                    );
                                    populateClientFields(client);
                                    updateFormProgress();
                                } catch (error) {
                                    console.error(
                                        "Error in Select2 change handler:",
                                        error
                                    );
                                }
                            }
                        });

                        $clientSelect.on("select2:clear", function (e) {
                            console.log("Select2 client cleared");
                            clearClientFields();
                        });

                        // Ensure the pre-selected value is maintained
                        if (currentValue) {
                            $clientSelect.val(currentValue).trigger("change");

                            // Also trigger manual population for pre-selected values
                            const preSelectedOption =
                                clientSelect.options[
                                    clientSelect.selectedIndex
                                ];
                            if (
                                preSelectedOption &&
                                preSelectedOption.dataset.client
                            ) {
                                try {
                                    const client = JSON.parse(
                                        preSelectedOption.dataset.client
                                    );
                                    populateClientFields(client);
                                } catch (error) {
                                    console.error(
                                        "Error populating pre-selected client:",
                                        error
                                    );
                                }
                            }
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
        console.log("Client selection changed, value:", this.value);
        const selectedOption = this.options[this.selectedIndex];

        if (selectedOption && selectedOption.dataset.client) {
            try {
                const client = JSON.parse(selectedOption.dataset.client);
                console.log("Selected client data:", client);

                // Auto-populate client-related fields
                populateClientFields(client);

                // Update form progress after populating fields
                updateFormProgress();
            } catch (error) {
                console.error("Error parsing client data:", error);
            }
        } else {
            // Clear fields if no client selected
            clearClientFields();
            console.log("Client selection cleared");
        }
    });

    // Also handle direct value setting (for programmatic changes)
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (
                mutation.type === "attributes" &&
                mutation.attributeName === "value"
            ) {
                const selectedOption =
                    clientSelect.options[clientSelect.selectedIndex];
                if (selectedOption && selectedOption.dataset.client) {
                    try {
                        const client = JSON.parse(
                            selectedOption.dataset.client
                        );
                        populateClientFields(client);
                    } catch (error) {
                        console.error("Error in mutation observer:", error);
                    }
                }
            }
        });
    });

    observer.observe(clientSelect, { attributes: true });
}

// Populate form fields with client data
function populateClientFields(client) {
    console.log("Populating client fields:", client);

    // Basic client information - ALWAYS populate client name when selected
    const clientNameField = document.getElementById("clientName");
    if (clientNameField) {
        // Add visual feedback before populating
        clientNameField.style.backgroundColor = "#e3f2fd";
        clientNameField.style.transition = "background-color 0.3s ease";

        clientNameField.value = client.client_name || "";
        console.log("Client name auto-populated:", client.client_name);

        // Show a brief success feedback
        setTimeout(() => {
            clientNameField.style.backgroundColor = "#e8f5e8";
        }, 100);

        setTimeout(() => {
            clientNameField.style.backgroundColor = "";
        }, 1500);

        // Show a small success notification
        showClientPopulatedNotification(client.client_name);

        // Trigger change event to update any listeners
        clientNameField.dispatchEvent(new Event("change"));
        clientNameField.dispatchEvent(new Event("input"));
    }

    // Also populate the project name if it exists in client data
    const projectNameField = document.getElementById("projectName");
    if (
        projectNameField &&
        client.default_project_name &&
        !projectNameField.value.trim()
    ) {
        projectNameField.value = client.default_project_name;
    }

    // If location field should be populated from client address
    const locationField = document.getElementById("location");
    if (locationField && client.full_address) {
        // Only populate if field is empty
        if (!locationField.value.trim()) {
            locationField.value = client.full_address;
            console.log("Location auto-populated:", client.full_address);
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

// Show notification when client name is auto-populated
function showClientPopulatedNotification(clientName) {
    // Create notification element
    const notification = document.createElement("div");
    notification.className =
        "alert alert-success alert-dismissible fade show position-fixed";
    notification.style.cssText =
        "top: 20px; right: 20px; z-index: 9999; min-width: 350px; max-width: 500px;";
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div>
                <strong>Client Auto-populated!</strong><br>
                <small>Client name "${clientName}" has been automatically filled in.</small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto-remove after 4 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 4000);
}

// Helper functions for comprehensive data collection

// Get all selected services
function getAllSelectedServices() {
    return selectedServices || [];
}

// Get all personnel assignments
function getAllPersonnelAssignments() {
    return window.personnelAssignments || [];
}

// Get all equipment assignments
function getAllEquipmentAssignments() {
    return window.equipmentAssignments || [];
}

// Get all consumable assignments
function getAllConsumableAssignments() {
    return window.consumableAssignments || [];
}

// Get all uploaded images
function getAllUploadedImages() {
    return window.uploadedImages || [];
}

// Get all service sections data (MPI, etc.)
function getAllServiceSectionsData() {
    const serviceSectionsData = {};

    // Collect MPI data if available
    const mpiData = document.getElementById("mpiDataContainer");
    if (mpiData) {
        serviceSectionsData.mpi = collectMpiData();
    }

    // Collect other service sections as they are implemented
    // Example: NDT, Load Testing, etc.

    return serviceSectionsData;
}

// Collect MPI specific data
function collectMpiData() {
    const mpiData = {};

    // Get all MPI form fields
    const mpiFields = document.querySelectorAll(
        "#mpiDataContainer input, #mpiDataContainer select, #mpiDataContainer textarea"
    );

    mpiFields.forEach((field) => {
        if (field.name) {
            if (field.type === "checkbox") {
                mpiData[field.name] = field.checked;
            } else if (field.type === "radio") {
                if (field.checked) {
                    mpiData[field.name] = field.value;
                }
            } else {
                mpiData[field.name] = field.value;
            }
        }
    });

    return mpiData;
}

// Restore service sections data
function restoreServiceSectionsData(serviceSectionsData) {
    if (!serviceSectionsData) return;

    // Restore MPI data
    if (serviceSectionsData.mpi) {
        restoreMpiData(serviceSectionsData.mpi);
    }

    // Restore other service sections as they are implemented
}

// Restore MPI specific data
function restoreMpiData(mpiData) {
    if (!mpiData) return;

    Object.keys(mpiData).forEach((fieldName) => {
        const field = document.querySelector(
            `#mpiDataContainer [name="${fieldName}"]`
        );
        if (field) {
            if (field.type === "checkbox") {
                field.checked = mpiData[fieldName];
            } else if (field.type === "radio") {
                if (field.value === mpiData[fieldName]) {
                    field.checked = true;
                }
            } else {
                field.value = mpiData[fieldName];
            }
        }
    });
}

// Enhanced clear local storage (also clear draft ID)
function clearLocalStorage() {
    Object.values(LOCAL_STORAGE_KEYS).forEach((key) => {
        localStorage.removeItem(key);
    });

    // Also delete the draft from database if we have a draft ID
    if (currentDraftId) {
        deleteDraftFromDatabase(currentDraftId);
        currentDraftId = null;
    }

    console.log("Local storage and database draft cleared");
}

// Delete draft from database
async function deleteDraftFromDatabase(draftId) {
    try {
        const response = await fetch(`/inspections/delete-draft/${draftId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
        });

        const result = await response.json();

        if (result.success) {
            console.log("Draft deleted from database successfully");
        } else {
            console.error(
                "Failed to delete draft from database:",
                result.message
            );
        }
    } catch (error) {
        console.error("Error deleting draft from database:", error);
    }
}

// Loader and notification functions for form submission

// Show submission loader overlay
function showSubmissionLoader() {
    // Remove existing loader if any
    hideSubmissionLoader();

    // Create loader overlay
    const loaderOverlay = document.createElement("div");
    loaderOverlay.id = "submission-loader-overlay";
    loaderOverlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    `;

    // Create loader content
    const loaderContent = document.createElement("div");
    loaderContent.style.cssText = `
        background: white;
        padding: 40px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 90%;
    `;

    loaderContent.innerHTML = `
        <div style="margin-bottom: 20px;">
            <div style="
                width: 60px;
                height: 60px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #007bff;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 20px auto;
            "></div>
            <style>
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            </style>
        </div>
        <h3 style="margin: 0 0 10px 0; color: #333;">Saving Inspection Report</h3>
        <p style="margin: 0; color: #666; line-height: 1.5;">
            Please wait while we securely save your inspection data to the database.<br>
            <strong>Do not close this window.</strong>
        </p>
        <div style="margin-top: 20px;">
            <div style="
                background: #e9ecef;
                border-radius: 10px;
                height: 6px;
                overflow: hidden;
            ">
                <div style="
                    background: linear-gradient(90deg, #007bff, #28a745);
                    height: 100%;
                    width: 0%;
                    animation: progress 3s ease-in-out infinite;
                "></div>
            </div>
            <style>
                @keyframes progress {
                    0% { width: 0%; }
                    50% { width: 70%; }
                    100% { width: 100%; }
                }
            </style>
        </div>
    `;

    loaderOverlay.appendChild(loaderContent);
    document.body.appendChild(loaderOverlay);

    // Disable scrolling
    document.body.style.overflow = "hidden";

    // Update submit button
    const saveBtn = document.getElementById("saveReportBtn");
    if (saveBtn) {
        saveBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        saveBtn.disabled = true;
    }
}

// Hide submission loader
function hideSubmissionLoader() {
    const existingLoader = document.getElementById("submission-loader-overlay");
    if (existingLoader) {
        existingLoader.remove();
    }

    // Re-enable scrolling
    document.body.style.overflow = "";

    // Reset submit button
    const saveBtn = document.getElementById("saveReportBtn");
    if (saveBtn) {
        saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Report';
        saveBtn.disabled = false;
    }
}

// Show success message
function showSuccessMessage(message) {
    hideSubmissionLoader();

    // Create success notification
    const successNotification = document.createElement("div");
    successNotification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 20px 25px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        z-index: 10000;
        max-width: 400px;
        animation: slideInRight 0.5s ease-out;
    `;

    successNotification.innerHTML = `
        <style>
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        </style>
        <div style="display: flex; align-items: center;">
            <i class="fas fa-check-circle" style="font-size: 24px; margin-right: 15px;"></i>
            <div>
                <strong style="display: block; margin-bottom: 5px;">Success!</strong>
                <span style="font-size: 14px; opacity: 0.9;">${message}</span>
            </div>
        </div>
    `;

    document.body.appendChild(successNotification);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (successNotification.parentNode) {
            successNotification.style.animation =
                "slideInRight 0.5s ease-out reverse";
            setTimeout(() => successNotification.remove(), 500);
        }
    }, 5000);
}

// Show error message
function showErrorMessage(message) {
    hideSubmissionLoader();

    // Create error notification
    const errorNotification = document.createElement("div");
    errorNotification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #dc3545, #e74c3c);
        color: white;
        padding: 20px 25px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        z-index: 10000;
        max-width: 400px;
        animation: slideInRight 0.5s ease-out;
    `;

    errorNotification.innerHTML = `
        <style>
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        </style>
        <div style="display: flex; align-items: flex-start;">
            <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-right: 15px; margin-top: 2px;"></i>
            <div>
                <strong style="display: block; margin-bottom: 5px;">Error</strong>
                <span style="font-size: 14px; opacity: 0.9; white-space: pre-line;">${message}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" style="
                background: none;
                border: none;
                color: white;
                font-size: 18px;
                cursor: pointer;
                margin-left: 10px;
                opacity: 0.7;
            ">&times;</button>
        </div>
    `;

    document.body.appendChild(errorNotification);

    // Auto-remove after 10 seconds
    setTimeout(() => {
        if (errorNotification.parentNode) {
            errorNotification.style.animation =
                "slideInRight 0.5s ease-out reverse";
            setTimeout(() => errorNotification.remove(), 500);
        }
    }, 10000);
}

// Add dynamic data to form data (personnel, equipment, etc.)
function addDynamicDataToFormData(formData) {
    try {
        // Add selected services
        if (selectedServices && selectedServices.length > 0) {
            selectedServices.forEach((service, index) => {
                formData.append(
                    `selected_services[${index}]`,
                    JSON.stringify(service)
                );
            });
        }

        // Add personnel assignments
        if (
            window.personnelAssignments &&
            window.personnelAssignments.length > 0
        ) {
            window.personnelAssignments.forEach((assignment, index) => {
                Object.keys(assignment).forEach((key) => {
                    formData.append(
                        `personnel_assignments[${index}][${key}]`,
                        assignment[key]
                    );
                });
            });
        }

        // Add equipment assignments
        if (
            window.equipmentAssignments &&
            window.equipmentAssignments.length > 0
        ) {
            window.equipmentAssignments.forEach((assignment, index) => {
                Object.keys(assignment).forEach((key) => {
                    formData.append(
                        `equipment_assignments[${index}][${key}]`,
                        assignment[key]
                    );
                });
            });
        }

        // Add consumable assignments
        if (
            window.consumableAssignments &&
            window.consumableAssignments.length > 0
        ) {
            window.consumableAssignments.forEach((assignment, index) => {
                Object.keys(assignment).forEach((key) => {
                    formData.append(
                        `consumable_assignments[${index}][${key}]`,
                        assignment[key]
                    );
                });
            });
        }

        // Add uploaded images
        if (window.uploadedImages && window.uploadedImages.length > 0) {
            window.uploadedImages.forEach((image, index) => {
                formData.append(
                    `uploaded_images[${index}]`,
                    JSON.stringify(image)
                );
            });
        }

        console.log("Dynamic data added to form submission");
    } catch (error) {
        console.error("Error adding dynamic data to form:", error);
    }
}
