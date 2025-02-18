
function generateProjectFields() {
    let projectCount = document.getElementById("project").value;
    let projectFields = document.getElementById("projectFields");

    // Clear previous inputs
    projectFields.innerHTML = "";

    // Retrieve previously entered project names and desc from the hidden input
    let savedProjects = JSON.parse(document.getElementById("savedProjects").value || "[]");
    let savedDescriptions = JSON.parse(document.getElementById("savedDescriptions").value || "[]");

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

        let descLabel = document.createElement("label");
        descLabel.setAttribute("for", "projectDesc" + i);
        descLabel.classList.add("form-label");
        descLabel.innerHTML = "Description du projet " + i + " :";
        descLabel.classList.add("mt-2");

        let descInput = document.createElement("textarea");
        descInput.setAttribute("name", "projectDescriptions[]");
        descInput.setAttribute("id", "projectDesc" + i);
        descInput.setAttribute("placeholder", "Entrez la description du projet " + i);
        descInput.classList.add("form-control");


        // Restore previously entered values if available
        if (savedProjects[i - 1]) {
            input.value = savedProjects[i - 1];  // Fill input field
        }

        if (savedDescriptions[i - 1]) {
            descInput.value = savedDescriptions[i - 1];  // Fill input field
        }

        div.appendChild(label);
        div.appendChild(input);

        div.appendChild(descLabel);
        div.appendChild(descInput);

        projectFields.appendChild(div);
    }
}

function generateStages() {
    let stageCount = document.getElementById("stage").value;
    let stageFields = document.getElementById("stageFields");

    // Clear previous inputs
    stageFields.innerHTML = "";

    // Retrieve previously entered stages names and desc from the hidden input
    let savedStages = JSON.parse(document.getElementById("savedStages").value || "[]");
    let savedDescriptions = JSON.parse(document.getElementById("savedStageDescriptions").value || "[]");

    // Generate input fields based on selected number
    for (let i = 1; i <= stageCount; i++) {
        let div = document.createElement("div");
        div.classList.add("mb-3");

        // Create Stage Name Label and Input
        let nameLabel = document.createElement("label");
        nameLabel.setAttribute("for", "stageName" + i);
        nameLabel.classList.add("form-label");
        nameLabel.innerHTML = "Nom du stage " + i + " :";

        let nameInput = document.createElement("input");
        nameInput.setAttribute("type", "text");
        nameInput.setAttribute("name", "stageNames[]");
        nameInput.setAttribute("id", "stageName" + i);
        nameInput.setAttribute("placeholder", "Entrez le nom du stage " + i);
        nameInput.classList.add("form-control");

        // Create Stage Description Label and Input
        let descLabel = document.createElement("label");
        descLabel.setAttribute("for", "stageDesc" + i);
        descLabel.classList.add("form-label");
        descLabel.innerHTML = "Description du stage " + i + " :";

        let descInput = document.createElement("textarea");
        descInput.setAttribute("name", "stageDescriptions[]");
        descInput.setAttribute("id", "stageDesc" + i);
        descInput.setAttribute("placeholder", "Entrez la description du stage " + i);
        descInput.classList.add("form-control");

        // Restore previously entered values if available
        if (savedStages[i - 1]) {
            nameInput.value = savedStages[i - 1];  // Fill input field
        }

        if (savedDescriptions[i - 1]) {
            descInput.value = savedDescriptions[i - 1];  // Fill input field
        }

        // Append name input fields
        div.appendChild(nameLabel);
        div.appendChild(nameInput);

        // Append description input fields
        div.appendChild(descLabel);
        div.appendChild(descInput);

        // Append the div to the stageFields container
        stageFields.appendChild(div);
    }
}

function generateExperience() {
    let experienceCount = document.getElementById("experience").value;
    let experienceFields = document.getElementById("experienceFields");

    // Clear previous inputs
    experienceFields.innerHTML = "";

    let savedExperiences = JSON.parse(document.getElementById("savedExperiences").value || "[]");
    let savedDescriptions = JSON.parse(document.getElementById("savedExperienceDescriptions").value || "[]");

    // Generate input fields based on selected number
    for (let i = 1; i <= experienceCount; i++) {
        let div = document.createElement("div");
        div.classList.add("mb-3");

        // Create Experience Name Label and Input
        let nameLabel = document.createElement("label");
        nameLabel.setAttribute("for", "experienceName" + i);
        nameLabel.classList.add("form-label");
        nameLabel.innerHTML = "Nom de l'expérience " + i + " :";

        let nameInput = document.createElement("input");
        nameInput.setAttribute("type", "text");
        nameInput.setAttribute("name", "experienceNames[]");
        nameInput.setAttribute("id", "experienceName" + i);
        nameInput.setAttribute("placeholder", "Entrez le nom de l'expérience " + i);
        nameInput.classList.add("form-control");

        // Create Experience Description Label and Input
        let descLabel = document.createElement("label");
        descLabel.setAttribute("for", "experienceDesc" + i);
        descLabel.classList.add("form-label");
        descLabel.innerHTML = "Description de l'expérience " + i + " :";

        let descInput = document.createElement("textarea");
        descInput.setAttribute("name", "experienceDescriptions[]");
        descInput.setAttribute("id", "experienceDesc" + i);
        descInput.setAttribute("placeholder", "Entrez la description de l'expérience " + i);
        descInput.classList.add("form-control");

        // Restore previously entered values if available
        if (savedExperiences[i - 1]) {
            nameInput.value = savedExperiences[i - 1];  // Fill input field
        }

        if (savedDescriptions[i - 1]) {
            descInput.value = savedDescriptions[i - 1];  // Fill input field
        }

        // Append name input fields
        div.appendChild(nameLabel);
        div.appendChild(nameInput);

        // Append description input fields
        div.appendChild(descLabel);
        div.appendChild(descInput);

        // Append the div to the experienceFields container
        experienceFields.appendChild(div);
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

// Ensure fields are restored on reload
document.addEventListener("DOMContentLoaded", function () {
    generateProjectFields();
    generateStages();
    generateExperience();
});


particlesJS.load('particles-js', 'assets/particles.json', function() {
    console.log('callback - particles.js config loaded');
});