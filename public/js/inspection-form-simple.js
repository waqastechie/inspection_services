// Simplified inspection form without auto-save functionality
console.log("Loading simplified inspection form...");

// Global variables
let personnelData = [];
let equipmentData = [];
let consumableData = [];
// Initialize global selectedServices array on window object for consistency
if (typeof window.selectedServices === "undefined") {
    window.selectedServices = [];
}

// Provide safe global fallbacks for inline form handlers used by some views
// This prevents ReferenceError if a view includes onclick="toggleInlineForm(...)" but
// doesn't actually render the inline equipment form script on that page.
if (typeof window.toggleInlineForm !== "function") {
    window.toggleInlineForm = function () {
        // no-op fallback so pages without the inline form don't break
        try {
            console.debug("toggleInlineForm noop (clean view)");
        } catch {}
    };
}
if (typeof window.resetInlineForm !== "function") {
    window.resetInlineForm = function () {
        try {
            console.debug("resetInlineForm noop (clean view)");
        } catch {}
    };
}

// Build absolute URL respecting base path (works in XAMPP /public and artisan serve)
function apiUrl(path) {
    const base =
        typeof window.APP_BASE_URL === "string" && window.APP_BASE_URL.length
            ? window.APP_BASE_URL.replace(/\/$/, "")
            : "";
    const clean = String(path || "").replace(/^\/+/, "");
    return `${base}/${clean}`;
}

// Draft management variables (removed auto-save)
let lastSaveTime = null;
let currentDraftId = null;
let isSubmitted = false;

// Initialize form
function initializeForm() {
    console.log("=== Initializing inspection form ===");
    console.log(
        "Function check - loadPersonnelData:",
        typeof loadPersonnelData
    );
    console.log("Function check - loadClientData:", typeof loadClientData);

    // Initialize data loading
    console.log("Loading data...");
    loadPersonnelData();
    loadEquipmentData();
    loadConsumableData();

    // Load clients with a small delay to ensure DOM is ready
    setTimeout(() => {
        loadClientsForDropdown();
    }, 500);

    loadClientData();

    // Initialize services
    initializeServiceCheckboxes();

    // Initialize dynamic sections
    initializeDynamicSections();

    console.log("Form initialization completed");
}

// Initialize form when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    console.log("=== DOM loaded, initializing form ===");
    console.log("Current URL:", window.location.href);
    console.log("User agent:", navigator.userAgent);

    // Add a small delay to ensure all functions are properly loaded
    setTimeout(function () {
        console.log("=== Delayed initialization starting ===");

        // Setup basic form validation directly
        const form = document.getElementById("liftingInspectionForm");
        if (form) {
            console.log("Setting up form validation...");
            form.addEventListener("submit", function (e) {
                console.log("=== FORM SUBMIT EVENT TRIGGERED ===");

                if (!form.checkValidity()) {
                    console.log("Form validation failed");
                    e.preventDefault();

                    const invalidFields = form.querySelectorAll(":invalid");
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
            console.log("Form validation setup completed");
        }

        // Check function availability before calling
        console.log("Function availability check:");
        console.log("- initializeForm:", typeof initializeForm);
        console.log("- updateFormProgress:", typeof updateFormProgress);

        if (typeof initializeForm === "function") {
            try {
                initializeForm();
                console.log("initializeForm completed successfully");
            } catch (error) {
                console.error("Error in initializeForm:", error);
            }
        } else {
            console.error("initializeForm is not defined as a function");
        }

        if (typeof updateFormProgress === "function") {
            try {
                updateFormProgress();
                console.log("updateFormProgress completed successfully");
            } catch (error) {
                console.error("Error in updateFormProgress:", error);
            }
        } else {
            console.error("updateFormProgress is not defined as a function");
        }
    }, 100); // 100ms delay

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

            const invalidFields = form.querySelectorAll(":invalid");
            console.log("Invalid fields:", invalidFields);

            invalidFields.forEach((field) => {
                console.log(
                    `Invalid field: ${field.name} - ${field.validationMessage}`
                );
            });
        }
    };

    // Add debug function for client API
    window.debugClientAPI = function () {
        console.log("=== DEBUG: Testing Client API ===");
        fetch(apiUrl("/api/clients"))
            .then((response) => {
                console.log("Response status:", response.status);
                console.log("Response headers:", response.headers);
                return response.json();
            })
            .then((data) => {
                console.log("API Response:", data);
            })
            .catch((error) => {
                console.error("API Error:", error);
            });
    };

    // Add debug function for client selection
    window.debugClientSelection = function (clientName) {
        console.log("=== DEBUG: Testing Client Selection ===");
        const clientSelect = document.getElementById("clientSelect");
        const clientNameField = document.querySelector('[name="client_name"]');

        console.log("Client select dropdown:", clientSelect);
        console.log("Client name field:", clientNameField);

        if (clientSelect && clientName) {
            clientSelect.value = clientName;
            const event = new Event("change", { bubbles: true });
            clientSelect.dispatchEvent(event);
        }
    };

    console.log("=== Form initialization completed ===");
});

// Personnel management functions
function loadPersonnelData() {
    console.log("Loading personnel data...");

    fetch(apiUrl("/inspections/api/personnel"))
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Response is not JSON");
            }
            return response.json();
        })
        .then((data) => {
            if (data.success) {
                personnelData = data.personnel;
                console.log(
                    "Personnel data loaded:",
                    personnelData.length,
                    "records"
                );
            } else {
                console.error("Failed to load personnel data:", data.message);
            }
        })
        .catch((error) => {
            console.warn("Could not load personnel data:", error.message);
            personnelData = [];
        });
}

// Equipment management functions
function loadEquipmentData() {
    console.log("=== EQUIPMENT DEBUG START ===");
    console.log("Loading equipment data...");
    console.log("Base URL:", window.APP_BASE_URL);
    console.log("Full API URL:", apiUrl("/inspections/api/equipment"));

    fetch(apiUrl("/inspections/api/equipment"))
        .then((response) => {
            console.log("Equipment API Response:", response);
            console.log("Equipment API Response status:", response.status);
            console.log("Equipment API Response OK:", response.ok);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Response is not JSON");
            }
            return response.json();
        })
        .then((data) => {
            console.log("Equipment API Data:", data);
            if (data.success) {
                equipmentData = data.equipment;
                console.log(
                    "Equipment data loaded:",
                    equipmentData.length,
                    "records"
                );
            } else {
                console.error("Failed to load equipment data:", data.message);
            }
        })
        .catch((error) => {
            console.warn("Could not load equipment data:", error.message);
            equipmentData = [];
        });
}

// Consumable management functions
function loadConsumableData() {
    console.log("Loading consumable data...");

    fetch(apiUrl("/inspections/api/consumables"))
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            // Check if response is actually JSON
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Response is not JSON");
            }
            return response.json();
        })
        .then((data) => {
            if (data.success) {
                consumableData = data.consumables;
                console.log(
                    "Consumable data loaded:",
                    consumableData.length,
                    "records"
                );
            } else {
                console.error("Failed to load consumable data:", data.message);
            }
        })
        .catch((error) => {
            console.warn("Could not load consumable data:", error.message);
            // Continue without consumable data
            consumableData = [];
        });
}

// Client management functions
let clientsData = []; // Store all clients for searching

// Simple client dropdown loader
function loadClientsForDropdown() {
    console.log("=== Loading clients for simple dropdown ===");

    const clientSelect = document.getElementById("client_id");
    if (!clientSelect) {
        // Not all pages use the simple #client_id dropdown (wizard uses searchable input).
        // Silently skip without logging as error to keep console clean.
        return;
    }

    // Check if clients are already loaded server-side (wizard mode)
    const existingOptions = clientSelect.querySelectorAll('option[value!=""]');
    if (existingOptions.length > 0) {
        console.log(
            `Clients already loaded server-side (${existingOptions.length} options found), skipping AJAX load`
        );
        // Still add the change event listener for functionality
        clientSelect.addEventListener("change", handleClientSelection);
        return;
    }

    console.log("Found client select element:", clientSelect);

    // Add loading indicator
    clientSelect.innerHTML = '<option value="">Loading clients...</option>';
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

    fetch(apiUrl("/api/clients"), {
        method: "GET",
        headers: headers,
        credentials: "same-origin",
    })
        .then((response) => {
            console.log("Client API Response status:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((clients) => {
            console.log("Loaded clients:", clients);

            // Re-enable dropdown and clear loading
            clientSelect.disabled = false;
            clientSelect.innerHTML =
                '<option value="">Choose a client...</option>';

            // If no clients or invalid response, use demo fallback like loadClientData()
            if (!Array.isArray(clients) || clients.length === 0) {
                console.warn(
                    "No clients received or invalid data format - using demo fallback"
                );

                const demoClients = [
                    {
                        id: 1,
                        client_name: "Saipem Trechville",
                        client_code: "ST",
                    },
                    {
                        id: 2,
                        client_name: "TotalEnergies Offshore",
                        client_code: "TEO",
                    },
                    {
                        id: 3,
                        client_name: "ENI Exploration",
                        client_code: "ENI",
                    },
                    {
                        id: 4,
                        client_name: "Petroci Holding",
                        client_code: "PCH",
                    },
                    {
                        id: 5,
                        client_name: "FOXTROT International",
                        client_code: "FI",
                    },
                ];

                demoClients.forEach((client) => {
                    const option = document.createElement("option");
                    option.value = client.id;
                    option.textContent = client.client_code
                        ? `${client.client_name} (${client.client_code})`
                        : client.client_name;
                    option.dataset.clientData = JSON.stringify(client);
                    clientSelect.appendChild(option);
                });

                showErrorMessage(
                    "No clients in database. Using demo clients; please add real clients."
                );

                // Add change event listener
                clientSelect.addEventListener("change", handleClientSelection);
                return;
            }

            // Add client options
            clients.forEach((client) => {
                const option = document.createElement("option");
                option.value = client.id;
                option.textContent = client.client_code
                    ? `${client.client_name} (${client.client_code})`
                    : client.client_name;
                option.dataset.clientData = JSON.stringify(client);
                clientSelect.appendChild(option);
                console.log("Added client option:", option.textContent);
            });

            console.log(
                `Successfully loaded ${clients.length} clients into dropdown`
            );

            // Add change event listener
            clientSelect.addEventListener("change", handleClientSelection);
        })
        .catch((error) => {
            console.error("Failed to load clients:", error);
            clientSelect.disabled = false;
            clientSelect.innerHTML =
                '<option value="">Failed to load clients - Check console</option>';
        });
}

// Handle client selection and auto-fill related fields
function handleClientSelection() {
    const clientSelect = document.getElementById("client_id");
    if (!clientSelect) {
        console.error("Client select element not found!");
        return;
    }

    const selectedOption = clientSelect.options[clientSelect.selectedIndex];
    console.log("Selected option:", selectedOption);
    console.log("Selected value:", clientSelect.value);

    if (!selectedOption || !selectedOption.value) {
        console.log("No client selected, clearing fields");
        clearClientFields();
        return;
    }

    try {
        // Get client data from the option's dataset
        const clientDataStr = selectedOption.dataset.clientData;
        console.log("Raw client data string:", clientDataStr);

        if (!clientDataStr) {
            console.error("No client data found in selected option");
            return;
        }

        const clientData = JSON.parse(clientDataStr);
        console.log("Parsed client data:", clientData);

        // Auto-fill client-related fields
        autoFillClientFields(clientData);
    } catch (error) {
        console.error("Error parsing client data:", error);
    }
}

// Auto-fill fields based on selected client
function autoFillClientFields(client) {
    console.log("Auto-filling fields for client:", client.client_name);

    // Fill hidden client fields
    setFieldValue("client_name", client.client_name);
    setFieldValue("client_code", client.client_code);
    setFieldValue("client_contact_person", client.contact_person);
    setFieldValue("client_contact_email", client.contact_email);
    setFieldValue("client_phone", client.phone);

    // Auto-fill project name (always update)
    const projectField = document.querySelector('[name="project_name"]');
    if (projectField) {
        const projectName = `${client.client_name} Inspection Project`;
        projectField.value = projectName;
        projectField.dispatchEvent(new Event("input", { bubbles: true }));
        console.log("Auto-filled project name:", projectName);
    }

    // Auto-fill location (always update)
    const locationField = document.querySelector('[name="location"]');
    if (locationField) {
        let location = "";
        if (client.city) {
            location = client.city;
            if (client.state) {
                location += `, ${client.state}`;
            }
        } else if (client.address) {
            location = client.address;
        } else {
            // Fallback to a generic location based on client
            location = `${client.client_name} Site`;
        }

        if (location) {
            locationField.value = location;
            locationField.dispatchEvent(new Event("input", { bubbles: true }));
            console.log("Auto-filled location:", location);
        }
    }

    // Note: Serial number field is not auto-filled as it's equipment-specific

    showSuccessMessage(
        `Client "${client.client_name}" selected and related fields auto-filled.`
    );
}

// Clear client-related fields
function clearClientFields() {
    setFieldValue("client_name", "");
    setFieldValue("client_code", "");
    setFieldValue("client_contact_person", "");
    setFieldValue("client_contact_email", "");
    setFieldValue("client_phone", "");
}

// Helper function to set field value
function setFieldValue(fieldName, value) {
    const field =
        document.getElementById(fieldName) ||
        document.querySelector(`[name="${fieldName}"]`);
    if (field) {
        field.value = value || "";
    }
}

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

    fetch(apiUrl("/api/clients"), {
        method: "GET",
        headers: headers,
        credentials: "same-origin",
    })
        .then((response) => {
            console.log("API Response status:", response.status);
            console.log("API Response headers:", [
                ...response.headers.entries(),
            ]);

            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error("Authentication required - please log in");
                } else if (response.status === 403) {
                    throw new Error(
                        "Access denied - admin privileges required"
                    );
                } else if (response.status === 404) {
                    throw new Error("API endpoint not found");
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            }
            return response.text();
        })
        .then((text) => {
            console.log("Raw API response:", text);
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error("Invalid JSON response: " + text);
            }
        })
        .then((response) => {
            console.log("Parsed client data:", response);

            // Reset the input
            clientSelect.disabled = false;
            clientSelect.placeholder = "Start typing to search clients...";

            // Extract clients from API response
            const clients = response.success
                ? response.clients
                : Array.isArray(response)
                ? response
                : [];

            if (Array.isArray(clients) && clients.length > 0) {
                // Store clients data for searching
                clientsData = clients;

                console.log(
                    `Successfully loaded ${clients.length} clients from database`
                );
                showSuccessMessage(
                    `Loaded ${clients.length} clients from database`
                );

                // Initialize searchable functionality
                initializeSearchableDropdown();
            } else {
                console.warn("No clients found in database response");
                if (clientSelect.tagName === "SELECT") {
                    clientSelect.innerHTML =
                        '<option value="">No clients found - Please add clients first</option>';
                } else {
                    clientSelect.placeholder =
                        "No clients found - Please add clients first";
                }
                showErrorMessage(
                    "No clients found in database. Please add clients first."
                );
            }
        })
        .catch((error) => {
            console.error("Failed to load clients from database:", error);

            // Reset input to original state
            clientSelect.disabled = false;
            if (clientSelect.tagName === "SELECT") {
                clientSelect.innerHTML =
                    '<option value="">Error loading clients</option>';
            } else {
                clientSelect.placeholder = "Start typing to search clients...";
            }

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

            showErrorMessage(
                `Database clients unavailable: ${error.message}. Using demo clients for testing.`
            );
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
        "Initializing searchable dropdown with",
        clientsData.length,
        "clients"
    );

    let selectedIndex = -1; // Track keyboard selection

    // Input event for search
    clientSelect.addEventListener("input", function () {
        const searchTerm = this.value.toLowerCase();
        console.log("Searching for:", searchTerm);

        selectedIndex = -1; // Reset selection

        // Clear any previous selected client data
        delete this.dataset.selectedClient;

        // Clear the client name field if input is being changed manually
        const clientNameField = document.querySelector('[name="client_name"]');
        if (clientNameField && !searchTerm) {
            clientNameField.value = "";
            // Trigger validation if Alpine.js is available
            const event = new Event("input", { bubbles: true });
            clientNameField.dispatchEvent(event);
        }

        if (searchTerm.length < 1) {
            clientDropdown.style.display = "none";
            return;
        }

        // Filter clients based on search term
        const filteredClients = clientsData.filter(
            (client) =>
                client.client_name.toLowerCase().includes(searchTerm) ||
                (client.client_code &&
                    client.client_code.toLowerCase().includes(searchTerm))
        );

        console.log("Filtered results:", filteredClients.length);

        // Populate dropdown
        populateDropdown(filteredClients);
    });

    // Keyboard navigation
    clientSelect.addEventListener("keydown", function (e) {
        const dropdownItems = clientDropdown.querySelectorAll(
            ".custom-dropdown-item:not(.muted)"
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
                clearAllSelections();
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

    // Click outside to hide dropdown and clear selections
    document.addEventListener("click", function (e) {
        if (
            !clientSelect.contains(e.target) &&
            !clientDropdown.contains(e.target)
        ) {
            // Clear all selections when closing dropdown
            clearAllSelections();
            clientDropdown.style.display = "none";
            selectedIndex = -1;
        }
    });
}

function highlightItem(items, index) {
    // Remove previous highlight from all items
    items.forEach((item) => {
        if (!item.classList.contains("muted")) {
            item.className = "custom-dropdown-item";
            item.style.backgroundColor = "white";
            item.style.color = "#333";
        }
    });

    // Add highlight to current item
    if (index >= 0 && items[index]) {
        const currentItem = items[index];
        if (!currentItem.classList.contains("muted")) {
            currentItem.style.backgroundColor = "#e9ecef";
            currentItem.style.color = "#333";
            currentItem.scrollIntoView({ block: "nearest" });
        }
    }
}

function populateDropdown(clients) {
    const clientDropdown = document.getElementById("clientDropdown");

    if (clients.length === 0) {
        clientDropdown.innerHTML =
            '<div class="custom-dropdown-item muted">No clients found</div>';
        clientDropdown.style.display = "block";
        return;
    }

    // Clear dropdown completely
    clientDropdown.innerHTML = "";

    clients.forEach((client) => {
        const item = document.createElement("div");
        item.className = "custom-dropdown-item";

        const displayText = client.client_code
            ? `${client.client_name} (${client.client_code})`
            : client.client_name;

        item.textContent = displayText;
        item.dataset.clientData = JSON.stringify(client);

        item.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            console.log("Client clicked:", client.client_name);

            // Clear all selections first
            clearAllSelections();

            // Mark this item as selected
            this.className = "custom-dropdown-item selected";

            // Select the client
            selectClient(client);
        });

        item.addEventListener("mouseenter", function () {
            if (!this.classList.contains("selected")) {
                this.style.backgroundColor = "#e9ecef";
            }
        });

        item.addEventListener("mouseleave", function () {
            if (!this.classList.contains("selected")) {
                this.style.backgroundColor = "white";
            }
        });

        clientDropdown.appendChild(item);
    });

    clientDropdown.style.display = "block";
}

function clearAllSelections() {
    const clientDropdown = document.getElementById("clientDropdown");
    const allItems = clientDropdown.querySelectorAll(".custom-dropdown-item");
    allItems.forEach((item) => {
        if (!item.classList.contains("muted")) {
            item.className = "custom-dropdown-item";
            item.style.backgroundColor = "white";
            item.style.color = "#333";
        }
    });
}

function selectClient(client) {
    console.log("selectClient called with:", client);

    const clientSelect = document.getElementById("clientSelect");
    const clientDropdown = document.getElementById("clientDropdown");

    if (!clientSelect || !clientDropdown) {
        console.error("Required elements not found!");
        return;
    }

    // Set the search input value
    const displayText = client.client_code
        ? `${client.client_name} (${client.client_code})`
        : client.client_name;
    clientSelect.value = displayText;
    console.log("Set clientSelect value to:", displayText);

    // Hide dropdown immediately
    clientDropdown.style.display = "none";
    console.log("Hidden dropdown");

    // Auto-populate all client-related fields
    console.log("About to call populateClientFields with:", client);
    populateClientFields(client);

    // Store selected client data
    clientSelect.dataset.selectedClient = JSON.stringify(client);
    console.log("Stored selected client data");
}

function populateClientFields(clientData) {
    console.log("populateClientFields called with:", clientData);

    // Helper function to populate field and trigger validation
    function populateField(fieldName, value, onlyIfEmpty = true) {
        console.log(`Attempting to populate ${fieldName} with "${value}"`);
        const field = document.querySelector(`[name="${fieldName}"]`);
        console.log(`Field ${fieldName} found:`, field);

        if (field && value) {
            // Only populate if field is empty (unless onlyIfEmpty is false)
            if (!onlyIfEmpty || !field.value.trim()) {
                field.value = value;
                // Trigger validation for this field
                const event = new Event("input", { bubbles: true });
                field.dispatchEvent(event);
                console.log(
                    `Successfully populated ${fieldName} with: ${value}`
                );
                return true;
            } else {
                console.log(
                    `Skipped ${fieldName} - field not empty (value: "${field.value}")`
                );
            }
        } else {
            if (!field) console.log(`Field ${fieldName} not found in DOM`);
            if (!value) console.log(`No value provided for ${fieldName}`);
        }
        return false;
    }

    // Track what was populated for success message
    let populatedFields = [];

    // Core client information (always populate)
    if (populateField("client_name", clientData.client_name, false)) {
        populatedFields.push("Client Name");
    }

    // Smart auto-population for empty fields only

    // Project name with client pattern (only if empty)
    if (populateField("project_name", `${clientData.client_name} Project`)) {
        populatedFields.push("Project Name");
    }

    // Serial number based on client address (only if empty)
    if (clientData.city) {
        const locationValue = `${clientData.city}${
            clientData.state ? ", " + clientData.state : ""
        }`;
        if (populateField("location", locationValue)) {
            populatedFields.push("Serial Number");
        }
    }

    // Client job reference with client code (only if empty)
    if (clientData.client_code) {
        if (
            populateField(
                "client_job_reference",
                `${clientData.client_code}-${new Date().getFullYear()}`
            )
        ) {
            populatedFields.push("Client Job Reference");
        }
    }

    // Hidden/metadata fields for client details (always populate)
    populateField("client_id", clientData.id, false);
    populateField("client_code", clientData.client_code, false);

    // Additional client metadata (if form has these fields)
    populateField("client_email", clientData.email);
    populateField("client_phone", clientData.phone);
    populateField("client_address", clientData.address);
    populateField("client_contact_person", clientData.contact_person);
    populateField("client_contact_email", clientData.contact_email);
    populateField("client_contact_phone", clientData.contact_phone);
    populateField("client_company_type", clientData.company_type);
    populateField("client_industry", clientData.industry);

    // Show success message with details of what was populated
    const message =
        populatedFields.length > 0
            ? `Client "${
                  clientData.client_name
              }" selected. Auto-populated: ${populatedFields.join(", ")}.`
            : `Client "${clientData.client_name}" selected successfully.`;

    console.log("Calling showSuccessMessage with:", message);
    showSuccessMessage(message);

    console.log(
        "Client fields population completed. Populated fields:",
        populatedFields
    );
}

// Debug function to test client auto-population
window.testClientPopulation = function (clientIndex = 0) {
    console.log("=== Testing Client Auto-Population ===");
    if (clientsData && clientsData.length > clientIndex) {
        const testClient = clientsData[clientIndex];
        console.log("Testing with client:", testClient);
        populateClientFields(testClient);
    } else {
        console.log(
            "No client data available or invalid index. Available clients:",
            clientsData?.length || 0
        );
    }
};

// Debug function to show available clients
window.showAvailableClients = function () {
    console.log("=== Available Clients ===");
    if (clientsData && clientsData.length > 0) {
        clientsData.forEach((client, index) => {
            console.log(
                `${index}: ${client.client_name} (${
                    client.client_code || "No Code"
                })`
            );
        });
    } else {
        console.log("No clients loaded yet");
    }
};

// Form utility functions
function initializeServiceCheckboxes() {
    console.log("Initializing service checkboxes...");

    const serviceCheckboxes = document.querySelectorAll(
        'input[name="selected_services[]"]'
    );
    serviceCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            const serviceType = this.value;
            const isChecked = this.checked;

            if (isChecked) {
                if (
                    !window.selectedServices.find((s) => s.type === serviceType)
                ) {
                    window.selectedServices.push({
                        type: serviceType,
                        name: serviceType
                            .replace(/[-_]/g, " ")
                            .replace(/\b\w/g, (l) => l.toUpperCase()),
                        description: `${serviceType} inspection service`,
                    });
                }
                showServiceSection(serviceType);
            } else {
                window.selectedServices = window.selectedServices.filter(
                    (s) => s.type !== serviceType
                );
                hideServiceSection(serviceType);
            }

            console.log("Selected services updated:", window.selectedServices);
        });
    });
}

function showServiceSection(serviceType) {
    const section = document.getElementById(
        `section-${serviceType.replace("_", "-")}`
    );
    if (section) {
        section.style.display = "block";
        // Re-enable required fields inside the now-visible section
        section
            .querySelectorAll("[data-required-original='true']")
            .forEach((el) => {
                el.setAttribute("required", "required");
                el.removeAttribute("data-required-original");
            });
        console.log(`Showing section for ${serviceType}`);
    }
}

function hideServiceSection(serviceType) {
    const section = document.getElementById(
        `section-${serviceType.replace("_", "-")}`
    );
    if (section) {
        // Disable required fields while hidden to avoid 'invalid form control is not focusable'
        section.querySelectorAll("[required]").forEach((el) => {
            el.removeAttribute("required");
            el.setAttribute("data-required-original", "true");
        });
        section.style.display = "none";
        console.log(`Hiding section for ${serviceType}`);
    }
}

function initializeDynamicSections() {
    console.log("Initializing dynamic sections...");

    // Initialize any dynamic table functionality
    initializeDynamicTables();

    // Initialize modals
    initializeModals();
}

function initializeDynamicTables() {
    // Add functionality for dynamic table row addition/removal
    const addButtons = document.querySelectorAll(".add-row-btn");
    addButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const tableId = this.dataset.table;
            addTableRow(tableId);
        });
    });
}

function addTableRow(tableId) {
    const table = document.getElementById(tableId);
    if (table) {
        const tbody = table.querySelector("tbody");
        if (tbody) {
            const rowCount = tbody.rows.length;
            const newRow = tbody.insertRow();

            // Add basic row structure - this would be customized per table
            newRow.innerHTML = `
                <td><input type="text" name="item_${rowCount}_name" class="form-control"></td>
                <td><input type="text" name="item_${rowCount}_description" class="form-control"></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row-btn">Remove</button></td>
            `;

            // Add remove functionality
            const removeBtn = newRow.querySelector(".remove-row-btn");
            removeBtn.addEventListener("click", function () {
                newRow.remove();
            });
        }
    }
}

function initializeModals() {
    // Initialize any modal functionality
    console.log("Initializing modals...");
}

// Form progress tracking
function updateFormProgress() {
    const form = document.getElementById("liftingInspectionForm");
    if (!form) return;

    const requiredFields = form.querySelectorAll("[required]");
    const filledFields = Array.from(requiredFields).filter((field) => {
        if (field.type === "checkbox" || field.type === "radio") {
            return field.checked;
        }
        return field.value && field.value.trim() !== "";
    });

    const progress =
        requiredFields.length > 0
            ? (filledFields.length / requiredFields.length) * 100
            : 0;

    // Update progress display
    updateProgressDisplay(progress, filledFields.length, requiredFields.length);
}

function updateProgressDisplay(progress, filled, total) {
    const progressBar = document.querySelector(".progress-bar");
    const progressText = document.querySelector(".progress-text");

    if (progressBar) {
        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute("aria-valuenow", progress);
    }

    if (progressText) {
        progressText.textContent = `${filled}/${total} required fields completed`;
    }

    // Update submit button state
    updateSubmitButtonState(progress);
}

function updateSubmitButtonState(progress) {
    const submitBtn = document.getElementById("submitBtn");
    if (submitBtn) {
        if (progress >= 100) {
            submitBtn.disabled = false;
            submitBtn.classList.remove("btn-secondary");
            submitBtn.classList.add("btn-success");
            submitBtn.innerHTML =
                '<i class="fas fa-check me-2"></i>Submit Report';
        } else {
            submitBtn.disabled = false; // Allow submission even if not 100% complete
            submitBtn.classList.remove("btn-success");
            submitBtn.classList.add("btn-primary");
            submitBtn.innerHTML =
                '<i class="fas fa-paper-plane me-2"></i>Submit Report';
        }
    }
}

// Utility functions for UI updates
function updateUIState() {
    // Update any UI state that needs to be refreshed
    updateFormProgress();
}

// Error and success message functions
function showErrorMessage(message) {
    showNotification(message, "error");
}

function showSuccessMessage(message) {
    showNotification(message, "success");
}

function showNotification(message, type = "info") {
    // Create or update notification area
    let notificationArea = document.querySelector(".notification-area");
    if (!notificationArea) {
        notificationArea = document.createElement("div");
        notificationArea.className =
            "notification-area position-fixed top-0 end-0 p-3";
        notificationArea.style.zIndex = "9999";
        document.body.appendChild(notificationArea);
    }

    const alertClass =
        type === "error"
            ? "alert-danger"
            : type === "success"
            ? "alert-success"
            : "alert-info";
    const icon =
        type === "error"
            ? "fa-exclamation-triangle"
            : type === "success"
            ? "fa-check"
            : "fa-info-circle";

    const notification = document.createElement("div");
    notification.className = `alert ${alertClass} alert-dismissible fade show`;
    notification.innerHTML = `
        <i class="fas ${icon} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    notificationArea.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Helper functions for data collection (simplified versions)
function getAllSelectedServices() {
    return window.selectedServices;
}

function getAllPersonnelAssignments() {
    return window.personnelAssignments || [];
}

function getAllEquipmentAssignments() {
    return window.equipmentAssignments || [];
}

function getAllConsumableAssignments() {
    return window.consumableAssignments || [];
}

function getAllUploadedImages() {
    return window.uploadedImages || [];
}

function getAllServiceSectionsData() {
    return {};
}

// Clear form function (simplified)
function clearForm() {
    if (
        confirm(
            "Are you sure you want to clear all form data? This action cannot be undone."
        )
    ) {
        const form = document.getElementById("liftingInspectionForm");
        if (form) {
            form.reset();
            window.selectedServices = [];
            updateFormProgress();
            showSuccessMessage("Form has been cleared.");
        }
    }
}

// Form validation function
function validateForm() {
    const form = document.getElementById("liftingInspectionForm");
    if (form) {
        const isValid = form.checkValidity();
        if (isValid) {
            showSuccessMessage(
                "Form validation passed! All required fields are completed."
            );
        } else {
            const invalidFields = form.querySelectorAll(":invalid");
            showErrorMessage(
                `Form validation failed. ${invalidFields.length} required field(s) need to be completed.`
            );

            // Focus on first invalid field
            if (invalidFields.length > 0) {
                invalidFields[0].focus();
                invalidFields[0].scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
            }
        }
    }
}

// Prepare form data for submission (simplified)
async function prepareComprehensiveFormData() {
    console.log("Preparing form data for submission...");

    try {
        // Add selected services as hidden inputs
        const form = document.getElementById("liftingInspectionForm");
        if (form && window.selectedServices.length > 0) {
            // Remove existing selected services inputs
            const existingInputs = form.querySelectorAll(
                'input[name="selected_services_data"]'
            );
            existingInputs.forEach((input) => input.remove());

            // Add new input with selected services
            const servicesInput = document.createElement("input");
            servicesInput.type = "hidden";
            servicesInput.name = "selected_services_data";
            servicesInput.value = JSON.stringify(window.selectedServices);
            form.appendChild(servicesInput);
        }

        console.log("Form data preparation completed");
    } catch (error) {
        console.error("Error preparing form data:", error);
    }
}

console.log("Simplified inspection form script loaded successfully");
