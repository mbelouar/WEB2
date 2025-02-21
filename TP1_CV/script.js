
function generateProjectFields() {
    let projectCount = document.getElementById("project").value;
    let projectFields = document.getElementById("projectFields");

    // Clear previous inputs
    projectFields.innerHTML = "";

    // Retrieve previously entered project names and desc from the hidden input
    let savedProjects = JSON.parse(document.getElementById("savedProjects").value || "[]");
    let savedDescriptions = JSON.parse(document.getElementById("savedDescriptions").value || "[]");
    let savedStartDate = JSON.parse(document.getElementById("savedStartDate").value || "[]");
    let savedEndDate = JSON.parse(document.getElementById("savedEndDate").value || "[]");

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

        let startDateLabel = document.createElement("label");
        startDateLabel.setAttribute("for", "startDate" + i);
        startDateLabel.classList.add("form-label");
        startDateLabel.innerHTML = "Date de début du projet " + i + " :";
        startDateLabel.classList.add("mt-2");

        let startDateInput = document.createElement("input");
        startDateInput.setAttribute("type", "date");
        startDateInput.setAttribute("name", "projectStartDates[]");
        startDateInput.setAttribute("id", "projectStartDates" + i);
        startDateInput.classList.add("form-control");

        let endDateLabel = document.createElement("label");
        endDateLabel.setAttribute("for", "endDate" + i);
        endDateLabel.classList.add("form-label");
        endDateLabel.innerHTML = "Date de fin du projet " + i + " :";
        endDateLabel.classList.add("mt-2");

        let endDateInput = document.createElement("input");
        endDateInput.setAttribute("type", "date");
        endDateInput.setAttribute("name", "projectEndDates[]");
        endDateInput.setAttribute("id", "projectEndDates" + i);
        endDateInput.classList.add("form-control");

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

        if (savedStartDate[i - 1]) {
            startDateInput.value = savedStartDate[i - 1];  // Fill input field
        }

        if (savedEndDate[i - 1]) {
            endDateInput.value = savedEndDate[i - 1];  // Fill input field
        }

        div.appendChild(label);
        div.appendChild(input);

        div.appendChild(descLabel);
        div.appendChild(descInput);

        div.appendChild(startDateLabel);
        div.appendChild(startDateInput);

        div.appendChild(endDateLabel);
        div.appendChild(endDateInput);

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
    let savedStartDate = JSON.parse(document.getElementById("savedStageStartDate").value || "[]");
    let savedEndDate = JSON.parse(document.getElementById("savedStageEndDate").value || "[]");
    let savedEnreprise = JSON.parse(document.getElementById("savedStageEntreprises").value || "[]");
    let savedLocation = JSON.parse(document.getElementById("savedStageLocations").value || "[]");

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

        // Create Stage Date Label and Input
        let startDateLabel = document.createElement("label");
        startDateLabel.setAttribute("for", "stageStartDate" + i);
        startDateLabel.classList.add("form-label");
        startDateLabel.innerHTML = "Date de début du stage " + i + " :";

        let startDateInput = document.createElement("input");
        startDateInput.setAttribute("type", "date");
        startDateInput.setAttribute("name", "stageStartDates[]");
        startDateInput.setAttribute("id", "stageStartDate" + i);
        startDateInput.classList.add("form-control");

        let endDateLabel = document.createElement("label");
        endDateLabel.setAttribute("for", "stageEndDate" + i);
        endDateLabel.classList.add("form-label");
        endDateLabel.innerHTML = "Date de fin du stage " + i + " :";
        endDateLabel.classList.add("mt-2");

        let endDateInput = document.createElement("input");
        endDateInput.setAttribute("type", "date");
        endDateInput.setAttribute("name", "stageEndDates[]");
        endDateInput.setAttribute("id", "stageEndDate" + i);
        endDateInput.classList.add("form-control");

        // Create Stage Entreprise Label and Input
        let entrepriseLabel = document.createElement("label");
        entrepriseLabel.setAttribute("for", "stageEntreprise" + i);
        entrepriseLabel.classList.add("form-label");
        entrepriseLabel.innerHTML = "Entreprise du stage " + i + " :";
        entrepriseLabel.classList.add("mt-2");

        let entrepriseInput = document.createElement("input");
        entrepriseInput.setAttribute("type", "text");
        entrepriseInput.setAttribute("name", "stageEntreprises[]");
        entrepriseInput.setAttribute("id", "stageEntreprise" + i);
        entrepriseInput.setAttribute("placeholder", "Entrez le nom de l'entreprise du stage " + i);
        entrepriseInput.classList.add("form-control");

        // Create Stage Location Label and Input
        let locationLabel = document.createElement("label");
        locationLabel.setAttribute("for", "stageLocation" + i);
        locationLabel.classList.add("form-label");
        locationLabel.innerHTML = "Lieu du stage " + i + " :";
        locationLabel.classList.add("mt-2");

        let locationInput = document.createElement("input");
        locationInput.setAttribute("type", "text");
        locationInput.setAttribute("name", "stageLocations[]");
        locationInput.setAttribute("id", "stageLocation" + i);
        locationInput.setAttribute("placeholder", "Entrez le lieu du stage " + i);
        locationInput.classList.add("form-control");

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

        if (savedStartDate[i - 1]) {
            startDateInput.value = savedStartDate[i - 1];  // Fill input field
        }

        if (savedEndDate[i - 1]) {
            endDateInput.value = savedEndDate[i - 1];  // Fill input field
        }

        if (savedEnreprise[i - 1]) {
            entrepriseInput.value = savedEnreprise[i - 1];  // Fill input field
        }

        if (savedLocation[i - 1]) {
            locationInput.value = savedLocation[i - 1];  // Fill input field
        }

        // Append name input fields
        div.appendChild(nameLabel);
        div.appendChild(nameInput);

        // Append description input fields
        div.appendChild(descLabel);
        div.appendChild(descInput);

        // Append date input fields
        div.appendChild(startDateLabel);
        div.appendChild(startDateInput);

        div.appendChild(endDateLabel);
        div.appendChild(endDateInput);

        // Append entreprise input fields
        div.appendChild(entrepriseLabel);
        div.appendChild(entrepriseInput);

        // Append location input fields
        div.appendChild(locationLabel);
        div.appendChild(locationInput);

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

function toggleNiveauOptions() {
    var selectedFormation = document.querySelector('input[name="formation"]:checked').value;
    var niveau3Container = document.getElementById('niveau_3_container');

    if (selectedFormation === '2AP' || selectedFormation === 'MS') {
        niveau3Container.style.display = 'none';
    } else {
        niveau3Container.style.display = 'inline-block';
    }
}

// Run on page load to apply the correct state
window.onload = toggleNiveauOptions;

// Ensure fields are restored on reload
document.addEventListener("DOMContentLoaded", function () {
    generateProjectFields();
    generateStages();
    generateExperience();
});


particlesJS.load('particles-js', 'assets/particles.json', function() {
    console.log('callback - particles.js config loaded');
});