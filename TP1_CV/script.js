
function generateProjectFields() {
    let projectCount = document.getElementById("project").value;
    let projectFields = document.getElementById("projectFields");

    // Clear previous inputs
    projectFields.innerHTML = "";

    // Retrieve previously entered project names from the hidden input
    let savedProjects = JSON.parse(document.getElementById("savedProjects").value || "[]");

    // Generate input fields based on selected number
    for (let i = 1; i <= projectCount; i++) {
        let div = document.createElement("div");
        div.classList.add("mb-2");

        let label = document.createElement("label");
        label.setAttribute("for", "projectName" + i);
        label.classList.add("form-label");
        label.innerHTML = "Nom du projet " + i + " :";

        let input = document.createElement("input");
        input.setAttribute("type", "text");
        input.setAttribute("name", "projectNames[]");
        input.setAttribute("id", "projectName" + i);
        input.setAttribute("placeholder", "Entrez le nom du projet " + i);
        input.classList.add("form-control");

        // Restore previously entered values if available
        if (savedProjects[i - 1]) {
            input.value = savedProjects[i - 1];  // Fill input field
        }

        div.appendChild(label);
        div.appendChild(input);
        projectFields.appendChild(div);
    }
}

// Ensure fields are restored on reload
document.addEventListener("DOMContentLoaded", function () {
    generateProjectFields();
});



function validateCheckboxes() {
    const checkboxes = document.querySelectorAll('input[name="modules[]"]');
    const errorMessage = document.getElementById('error-message');
    
    let checked = false;
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            checked = true;
        }
    });

    if (!checked) {
        errorMessage.style.display = 'block';
        return false; // Prevent form submission
    } else {
        errorMessage.style.display = 'none';
        return true; // Allow form submission
    }
}
