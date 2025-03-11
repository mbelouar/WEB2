$(document).ready(function() {
    // Charger les pétitions au démarrage
    chargerPetitions();

    // Gestionnaire pour le bouton nouvelle pétition
    $('#btnNouvellePetition').click(function() {
        $('#modalPetition').show();
    });

    // Fermer les modals
    $('.btnFermer').click(function() {
        $('.modal').hide();
    });

    // Soumission du formulaire de nouvelle pétition
    $('#formPetition').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'php/creer_petition.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#modalPetition').hide();
                    $('#formPetition')[0].reset();
                    chargerPetitions();
                } else {
                    alert('Erreur lors de la création de la pétition');
                }
            },
            error: function() {
                alert('Erreur de communication avec le serveur');
            }
        });
    });

    // Soumission du formulaire de signature
    $('#formSignature').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'php/signer_petition.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#modalSignature').hide();
                    $('#formSignature')[0].reset();
                    chargerPetitions();
                } else {
                    alert('Erreur lors de la signature');
                }
            },
            error: function() {
                alert('Erreur de communication avec le serveur');
            }
        });
    });
});

// Fonction pour charger les pétitions
function chargerPetitions() {
    $.ajax({
        url: 'php/liste_petitions.php',
        method: 'GET',
        success: function(petitions) {
            let html = '';
            petitions.forEach(function(petition) {
                html += `
                    <div class="petition-card">
                        <h3>${petition.titre}</h3>
                        <p>${petition.description}</p>
                        <p>Date de fin: ${petition.dateFin}</p>
                        <p>Signatures: ${petition.nombreSignatures}</p>
                        <button onclick="ouvrirSignature(${petition.idPetition})">Signer</button>
                        <div class="dernieres-signatures">
                            ${genererListeSignatures(petition.dernieresSignatures)}
                        </div>
                    </div>
                `;
            });
            $('#listePetitions').html(html);
        },
        error: function() {
            alert('Erreur lors du chargement des pétitions');
        }
    });
}

// Fonction pour ouvrir le modal de signature
function ouvrirSignature(petitionId) {
    $('#petitionId').val(petitionId);
    $('#modalSignature').show();
}

// Fonction pour générer la liste des dernières signatures
function genererListeSignatures(signatures) {
    if (!signatures || signatures.length === 0) return '';
    
    let html = '<h4>Dernières signatures:</h4><ul>';
    signatures.slice(0, 5).forEach(function(signature) {
        html += `<li>${signature.prenom} ${signature.nom} (${signature.pays})</li>`;
    });
    html += '</ul>';
    return html;
} 