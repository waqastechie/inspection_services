// Simplified inspection form - Clean Version
console.log("Loading simplified inspection form...");

// Global variables
let personnelData = [];
let equipmentData = [];
let consumableData = [];
let selectedServices = [];
let clientsData = [];

// Draft management variables (removed auto-save)
let lastSaveTime = null;
let currentDraftId = null;
let isSubmitted = false;

// Initialize form validation function
function initializeFormValidation() {
    console.log("Initializing form validation...");

    const form = document.getElementById("liftingInspectionForm");
    if (form) {
        form.addEventListener("submit", function (e) {
            console.log("=== FORM SUBMIT EVENT TRIGGERED ===");
            console.log("Form submitting with action:", form.action);

            if (!form.checkValidity()) {
                console.log("Form validation failed");
                e.preventDefault();

                const invalidFields = form.querySelectorAll(":invalid");
                console.log("Invalid fields:", invalidFields);

                if (invalidFields.length > 0) {
                    invalidFields[0].focus();
                    invalidFields[0].scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
                return false;
            }

            console.log("Form is valid, submitting...");
            isSubmitted = true;
        });

        console.log("Form validation initialized");
    } else {
        console.error("Form not found!");
    }

    // Update form progress periodically
    if (typeof updateFormProgress === "function") {
        setInterval(updateFormProgress, 5000);
    }
}

// Initialize form function
function initializeForm() {
    console.log("=== Initializing inspection form ===");

    // Initialize basic form functionality
    console.log("About to call initializeFormValidation...");
    initializeFormValidation();
    console.log("initializeFormValidation completed");

    // Initialize data loading
    console.log("Loading data...");
    if (typeof loadPersonnelData === "function") loadPersonnelData();
    if (typeof loadEquipmentData === "function") loadEquipmentData();
    if (typeof loadConsumableData === "function") loadConsumableData();
    if (typeof loadClientData === "function") loadClientData();

    // Initialize services
    if (typeof initializeServiceCheckboxes === "function")
        initializeServiceCheckboxes();

    // Initialize dynamic sections
    if (typeof initializeDynamicSections === "function")
        initializeDynamicSections();

    console.log("Form initialization completed");
}

// Personnel management functions
function loadPersonnelData() {
    console.log("Loading personnel data...");
    // Personnel loading logic here
}

// Equipment management functions
function loadEquipmentData() {
    console.log("Loading equipment data...");
    // Equipment loading logic here
}

// Consumable management functions
function loadConsumableData() {
    console.log("Loading consumable data...");
    // Consumable loading logic here
}

// Client management functions
function loadClientData() {
    console.log("=== Loading client data from database ===");

    const clientSelect = document.getElementById("clientSelect");
    const clientDropdown = document.getElementById("clientDropdown");

    if (!clientSelect) {
        console.log("Client select input not found - user might not be admin");
        return;
    }

    // Show loading state
    clientSelect.placeholder = "Loading clients...";
    clientSelect.disabled = true;

    // Add CSRF token for Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const headers = {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
        "Content-Type": "application/json",
    };

    if (csrfToken) {
        headers["X-CSRF-TOKEN"] = csrfToken.getAttribute("content");
    }

    fetch("/api/clients", {
        method: "GET",
        headers: headers,
        credentials: "same-origin",
    })
        .then((response) => {
            console.log("API Response status:", response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((clients) => {
            console.log("Parsed client data:", clients);

            clientSelect.disabled = false;
            clientSelect.placeholder = "Start typing to search clients...";

            if (Array.isArray(clients) && clients.length > 0) {
                clientsData = clients;
                console.log(
                    `Successfully loaded ${clients.length} clients from database`
                );
                initializeSearchableDropdown();
            } else {
                console.warn("No clients found in database response");
                clientSelect.placeholder =
                    "No clients found - Please add clients first";
            }
        })
        .catch((error) => {
            console.error("Failed to load clients from database:", error);

            clientSelect.disabled = false;
            clientSelect.placeholder = "Start typing to search clients...";

            // Add some fallback demo clients for testing
            const demoClients = [
                { id: 1, client_name: "Saipem Trechville", client_code: "ST" },
                {
                    id: 2,
                    client_name: "TotalEnergies Offshore",
                    client_code: "TEO",
                },
                { id: 3, client_name: "ENI Exploration", client_code: "ENI" },
                { id: 4, client_name: "Petroci Holding", client_code: "PCH" },
                {
                    id: 5,
                    client_name: "FOXTROT International",
                    client_code: "FI",
                },
            ];

            console.log("Adding demo clients as fallback");
            clientsData = demoClients;
            initializeSearchableDropdown();
        });
}

function initializeSearchableDropdown() {
    const clientSelect = document.getElementById("clientSelect");
    const clientDropdown = document.getElementById("clientDropdown");

    if (!clientSelect || !clientDropdown) {
        console.error("Client search elements not found");
        return;
    }

    console.log(
        `Initializing searchable dropdown with ${clientsData.length} clients`
    );

    let selectedIndex = -1;

    // Input event for search
    clientSelect.addEventListener("input", function () {
        const searchTerm = this.value.toLowerCase();
        console.log(`Searching for: "${searchTerm}"`);

        selectedIndex = -1;

        if (searchTerm.length < 1) {
            clientDropdown.style.display = "none";
            return;
        }

        const filteredClients = clientsData.filter(
            (client) =>
                client.client_name.toLowerCase().includes(searchTerm) ||
                (client.client_code &&
                    client.client_code.toLowerCase().includes(searchTerm))
        );

        console.log(`Filtered results: ${filteredClients.length} clients`);
        populateDropdown(filteredClients);
    });

    // Keyboard navigation
    clientSelect.addEventListener("keydown", function (e) {
        const dropdownItems = clientDropdown.querySelectorAll(
            ".dropdown-item:not(.text-muted)"
        );

        if (dropdownItems.length === 0) return;

        switch (e.key) {
            case "ArrowDown":
                e.preventDefault();
                selectedIndex = Math.min(
                    selectedIndex + 1,
                    dropdownItems.length - 1
                );
                highlightItem(dropdownItems, selectedIndex);
                break;

            case "ArrowUp":
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, 0);
                highlightItem(dropdownItems, selectedIndex);
                break;

            case "Enter":
                e.preventDefault();
                if (selectedIndex >= 0 && dropdownItems[selectedIndex]) {
                    dropdownItems[selectedIndex].click();
                }
                break;

            case "Escape":
                clientDropdown.style.display = "none";
                selectedIndex = -1;
                break;
        }
    });

    // Focus event to show all clients
    clientSelect.addEventListener("focus", function () {
        if (this.value.length === 0) {
            populateDropdown(clientsData);
        }
    });

    // Click outside to hide dropdown
    document.addEventListener("click", function (e) {
        if (
            !clientSelect.contains(e.target) &&
            !clientDropdown.contains(e.target)
        ) {
            clientDropdown.style.display = "none";
            selectedIndex = -1;
        }
    });

    console.log("Searchable dropdown initialized successfully");
}

function highlightItem(items, index) {
    items.forEach((item) => item.classList.remove("active"));

    if (index >= 0 && items[index]) {
        items[index].classList.add("active");
        items[index].scrollIntoView({ block: "nearest" });
    }
}

function populateDropdown(clients) {
    const clientDropdown = document.getElementById("clientDropdown");

    if (clients.length === 0) {
        clientDropdown.innerHTML =
            '<div class="dropdown-item text-muted">No clients found</div>';
        clientDropdown.style.display = "block";
        return;
    }

    clientDropdown.innerHTML = "";

    clients.forEach((client) => {
        const item = document.createElement("div");
        item.className = "dropdown-item";
        item.style.cursor = "pointer";

        const displayText = client.client_code
            ? `${client.client_name} (${client.client_code})`
            : client.client_name;

        item.textContent = displayText;
        item.dataset.clientData = JSON.stringify(client);

        item.addEventListener("click", function () {
            selectClient(client);
        });

        clientDropdown.appendChild(item);
    });

    clientDropdown.style.display = "block";
}

function selectClient(client) {
    console.log(`Client selected: ${client.client_name}`);

    const clientSelect = document.getElementById("clientSelect");
    const clientDropdown = document.getElementById("clientDropdown");

    const displayText = client.client_code
        ? `${client.client_name} (${client.client_code})`
        : client.client_name;
    clientSelect.value = displayText;

    clientDropdown.style.display = "none";

    const clientNameField = document.querySelector('[name="client_name"]');

    if (clientNameField) {
        clientNameField.value = client.client_name;
        const event = new Event("input", { bubbles: true });
        clientNameField.dispatchEvent(event);
        console.log(`Client name field populated with: ${client.client_name}`);
    } else {
        console.error("Client name field not found!");
    }

    clientSelect.dataset.selectedClient = JSON.stringify(client);
}

// Service management functions
function initializeServiceCheckboxes() {
    console.log("Initializing service checkboxes...");
    // Service checkbox logic here
}

// Dynamic sections functions
function initializeDynamicSections() {
    console.log("Initializing dynamic sections...");
    // Dynamic sections logic here
}

// Form progress functions
function updateFormProgress() {
    console.log("Updating form progress...");
    // Progress update logic here
}

// Utility functions for messaging
function showSuccessMessage(message) {
    console.log("SUCCESS:", message);
}

function showErrorMessage(message) {
    console.error("ERROR:", message);
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    console.log("=== DOM loaded, initializing form ===");

    // Small delay to ensure everything is ready
    setTimeout(function () {
        console.log("=== Starting initialization ===");

        try {
            if (typeof initializeForm === "function") {
                initializeForm();
                console.log("Form initialization completed successfully");
            } else {
                console.error("initializeForm function not found");
            }
        } catch (error) {
            console.error("Error during form initialization:", error);
        }

        try {
            if (typeof updateFormProgress === "function") {
                updateFormProgress();
                console.log("Progress update completed");
            }
        } catch (error) {
            console.error("Error updating progress:", error);
        }
    }, 100);

    // Debug functions
    window.debugSubmitForm = function () {
        console.log("DEBUG: Forcing form submission");
        const form = document.getElementById("liftingInspectionForm");
        if (form) {
            const newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);
            console.log("DEBUG: Submitting form directly");
            newForm.submit();
        }
    };

    window.debugFormValidation = function () {
        const form = document.getElementById("liftingInspectionForm");
        if (form) {
            console.log("Form validity:", form.checkValidity());
            console.log("Form action:", form.action);
            console.log("Form method:", form.method);

            const invalidFields = form.querySelectorAll(":invalid");
            console.log("Invalid fields:", invalidFields);

            invalidFields.forEach((field) => {
                console.log(
                    `Invalid field: ${field.name} - ${field.validationMessage}`
                );
            });
        }
    };

    window.debugClientAPI = function () {
        console.log("=== DEBUG: Testing Client API ===");
        fetch("/api/clients")
            .then((response) => {
                console.log("Response status:", response.status);
                return response.json();
            })
            .then((data) => {
                console.log("API Response:", data);
            })
            .catch((error) => {
                console.error("API Error:", error);
            });
    };

    console.log("=== Form initialization setup completed ===");
});

console.log("Simplified inspection form script loaded successfully");
