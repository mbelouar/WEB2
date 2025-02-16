
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

document.getElementById("generate-btn").addEventListener("click", function() {
    // Get form values
    let firstname = document.getElementById("firstname").value;
    let lastname = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;
    let age = document.getElementById("age").value;
    
    let formation = document.querySelector('input[name="formation"]:checked')?.value || "N/A";
    let niveau = document.querySelector('input[name="niveau"]:checked')?.value || "N/A";

    let modules = Array.from(document.querySelectorAll('input[name="modules[]"]:checked'))
                      .map(el => el.value).join(", ");

    let projectCount = document.getElementById("project").value;

    let interest1 = document.getElementById("interest1").value;
    let interest2 = document.getElementById("interest2").value;
    let interest3 = document.getElementById("interest3").value;
    let interest4 = document.getElementById("interest4").value;

    let langue1 = document.getElementById("langue1").value;
    let niveau1 = document.getElementById("niveau1").value;
    let langue2 = document.getElementById("langue2").value;
    let niveau2 = document.getElementById("niveau2").value;
    let langue3 = document.getElementById("langue3").value || "N/A";
    let niveau3 = document.getElementById("niveau3").value || "N/A";

    // Initialize jsPDF
    const { jsPDF } = window.jspdf;
    let doc = new jsPDF();

    // Add text to the PDF
    doc.setFontSize(18);
    doc.text("Curriculum Vitae", 70, 20);

    doc.setFontSize(12);
    doc.text(`Nom: ${firstname} ${lastname}`, 20, 40);
    doc.text(`Email: ${email}`, 20, 50);
    doc.text(`Téléphone: ${phone}`, 20, 60);
    doc.text(`Âge: ${age}`, 20, 70);
    doc.text(`Formation: ${formation}`, 20, 80);
    doc.text(`Niveau: ${niveau}`, 20, 90);
    doc.text(`Modules: ${modules}`, 20, 100);
    doc.text(`Nombre de projets: ${projectCount}`, 20, 110);

    doc.text(`Centres d'intérêt:`, 20, 120);
    doc.text(`1. ${interest1}`, 30, 130);
    doc.text(`2. ${interest2}`, 30, 140);
    if (interest3) doc.text(`3. ${interest3}`, 30, 150);
    if (interest4) doc.text(`4. ${interest4}`, 30, 160);

    doc.text(`Langues:`, 20, 170);
    doc.text(`1. ${langue1} - ${niveau1}`, 30, 180);
    doc.text(`2. ${langue2} - ${niveau2}`, 30, 190);
    if (langue3 !== "N/A") doc.text(`3. ${langue3} - ${niveau3}`, 30, 200);

    // Save the PDF
    doc.save(`CV_${firstname}_${lastname}.pdf`);
});