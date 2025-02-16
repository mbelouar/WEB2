
function generateProjectFields() {
    let projectCount = document.getElementById("project").value;
    let projectFields = document.getElementById("projectFields");

    // Clear previous inputs
    projectFields.innerHTML = "";

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
        input.setAttribute("name", "projectNames[]"); // Store as an array
        input.setAttribute("id", "projectName" + i);
        input.setAttribute("placeholder", "Entrez le nom du projet " + i);
        input.classList.add("form-control");

        div.appendChild(label);
        div.appendChild(input);
        projectFields.appendChild(div);
    }
}

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
