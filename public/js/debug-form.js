// Temporary debugging for form submission
console.log("Form submission debug - checking what data is being sent");

// Override the form submission temporarily
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("liftingInspectionForm");
    if (form) {
        // Add a test submit handler
        form.addEventListener("submit", function (e) {
            console.log("Form submission intercepted for debugging");

            // Get form data
            const formData = new FormData(form);
            const data = {};

            for (let [key, value] of formData.entries()) {
                data[key] = value;
                console.log(`Field: ${key} = "${value}"`);
            }

            // Check specifically for problematic fields
            if (data.applicable_standard !== undefined) {
                console.log(
                    "WARNING: applicable_standard is being sent:",
                    data.applicable_standard
                );
            }
            if (data.inspection_class !== undefined) {
                console.log(
                    "WARNING: inspection_class is being sent:",
                    data.inspection_class
                );
            }

            // Don't actually submit for now
            e.preventDefault();
            alert(
                "Form submission blocked for debugging. Check console for details."
            );
        });
    }
});
