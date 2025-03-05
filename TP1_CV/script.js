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
    let savedStartDate = JSON.parse(document.getElementById("savedExperienceStartDate").value || "[]");
    let savedEndDate = JSON.parse(document.getElementById("savedExperienceEndDate").value || "[]");
    let savedEnreprise = JSON.parse(document.getElementById("savedExperienceEntreprises").value || "[]");
    let savedLocation = JSON.parse(document.getElementById("savedExperienceLocations").value || "[]");
    let savedPosition = JSON.parse(document.getElementById("savedExperiencePositions").value || "[]");

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

        // Create Experience Date Label and Input
        let startDateLabel = document.createElement("label");
        startDateLabel.setAttribute("for", "experienceStartDate" + i);
        startDateLabel.classList.add("form-label");
        startDateLabel.innerHTML = "Date de début de l'expérience " + i + " :";
        startDateLabel.classList.add("mt-2");

        let startDateInput = document.createElement("input");
        startDateInput.setAttribute("type", "date");
        startDateInput.setAttribute("name", "experienceStartDates[]");
        startDateInput.setAttribute("id", "experienceStartDate" + i);
        startDateInput.classList.add("form-control");

        let endDateLabel = document.createElement("label");
        endDateLabel.setAttribute("for", "experienceEndDate" + i);
        endDateLabel.classList.add("form-label");
        endDateLabel.innerHTML = "Date de fin de l'expérience " + i + " :";
        endDateLabel.classList.add("mt-2");

        let endDateInput = document.createElement("input");
        endDateInput.setAttribute("type", "date");
        endDateInput.setAttribute("name", "experienceEndDates[]");
        endDateInput.setAttribute("id", "experienceEndDate" + i);
        endDateInput.classList.add("form-control");

        // Create Experience Entreprise Label and Input
        let entrepriseLabel = document.createElement("label");
        entrepriseLabel.setAttribute("for", "experienceEntreprise" + i);
        entrepriseLabel.classList.add("form-label");
        entrepriseLabel.innerHTML = "Entreprise de l'expérience " + i + " :";
        entrepriseLabel.classList.add("mt-2");

        let entrepriseInput = document.createElement("input");
        entrepriseInput.setAttribute("type", "text");
        entrepriseInput.setAttribute("name", "experienceEntreprises[]");
        entrepriseInput.setAttribute("id", "experienceEntreprise" + i);
        entrepriseInput.setAttribute("placeholder", "Entrez le nom de l'entreprise de l'expérience " + i);
        entrepriseInput.classList.add("form-control");

        // Create Experience Location Label and Input
        let locationLabel = document.createElement("label");
        locationLabel.setAttribute("for", "experienceLocation" + i);
        locationLabel.classList.add("form-label");
        locationLabel.innerHTML = "Lieu de l'expérience " + i + " :";
        locationLabel.classList.add("mt-2");

        let locationInput = document.createElement("input");
        locationInput.setAttribute("type", "text");
        locationInput.setAttribute("name", "experienceLocations[]");
        locationInput.setAttribute("id", "experienceLocation" + i);
        locationInput.setAttribute("placeholder", "Entrez le lieu de l'expérience " + i);
        locationInput.classList.add("form-control");

        // Create Experience Position Label and Input
        let positionLabel = document.createElement("label");
        positionLabel.setAttribute("for", "experiencePosition" + i);
        positionLabel.classList.add("form-label");
        positionLabel.innerHTML = "Poste occupé lors de l'expérience " + i + " :";
        positionLabel.classList.add("mt-2");

        let positionInput = document.createElement("input");
        positionInput.setAttribute("type", "text");
        positionInput.setAttribute("name", "experiencePositions[]");
        positionInput.setAttribute("id", "experiencePosition" + i);
        positionInput.setAttribute("placeholder", "Entrez le poste occupé lors de l'expérience " + i);
        positionInput.classList.add("form-control");

        // Restore previously entered values if available
        if (savedExperiences[i - 1]) {
            nameInput.value = savedExperiences[i - 1];  // Fill input field
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

        if (savedPosition[i - 1]) {
            positionInput.value = savedPosition[i - 1];  // Fill input field
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

        // Append position input fields
        div.appendChild(positionLabel);
        div.appendChild(positionInput);

        // Append the div to the experienceFields container
        experienceFields.appendChild(div);
    }
}

function toggleNiveauOptions() {
    try {
        var selectedFormation = document.querySelector('input[name="formation"]:checked');
        var niveau3Container = document.getElementById('niveau_3_container');
        
        // Safety check for both elements
        if (!selectedFormation || !niveau3Container) {
            console.log('Formation or niveau container not found');
            return;
        }
        
        // Toggle display based on selected value
        if (selectedFormation.value === '2AP' || selectedFormation.value === 'MS') {
            niveau3Container.style.display = 'none';
        } else {
            niveau3Container.style.display = 'block';
        }
    } catch (e) {
        console.error('Error in toggleNiveauOptions:', e);
    }
}

document.getElementById("addInterest").addEventListener("click", function() {
    let container = document.getElementById("interestContainer");
    let count = container.getElementsByTagName("input").length + 1;

    let div = document.createElement("div");
    div.className = "col-lg-6 mt-2";

    let label = document.createElement("label");
    label.className = "form-label";
    label.textContent = "Centre d'intérêt " + count + ":";

    let input = document.createElement("input");
    input.type = "text";
    input.name = "interests[]"; // Ensures submission as an array
    input.className = "form-control";
    input.placeholder = "Entrez votre intérêt";

    div.appendChild(label);
    div.appendChild(input);
    container.appendChild(div);
});

document.getElementById("addCompetence").addEventListener("click", function() {
    let container = document.getElementById("competenceContainer");
    let count = container.getElementsByTagName("input").length + 1;

    let div = document.createElement("div");
    div.className = "col-lg-6 mt-2";

    let label = document.createElement("label");
    label.className = "form-label";
    label.textContent = "Compétence " + count + ":";

    let input = document.createElement("input");
    input.type = "text";
    input.name = "competences[]"; // Ensures it's submitted as an array
    input.className = "form-control";
    input.placeholder = "Entrez votre compétence";

    div.appendChild(label);
    div.appendChild(input);
    container.appendChild(div);
});

// Ensure fields are restored on reload
document.addEventListener("DOMContentLoaded", function () {
    generateProjectFields();
    generateStages();
    generateExperience();
});


particlesJS.load('particles-js', '/WEB2/TP1_CV/assets/particles.json', function() {
    console.log('callback - particles.js config loaded');
});