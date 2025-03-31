<?php
session_start();

// Define API_REQUEST constant to prevent any text output from db.php
define('API_REQUEST', true);

require_once '../../BD/db.php';
require_once '../../BD/Consumption.php';

// Set page variables
$pageTitle = 'Saisie de Consommation';
$activePage = 'consumption';

// Check if the user is logged in
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

// Get client info
$clientId = $_SESSION['client']['id'];

// Initialize models
$consumptionModel = new Consumption($pdo);

// Get the latest consumption to display previous reading
$lastConsumption = $consumptionModel->getLastConsumption($clientId);
$previousReading = $lastConsumption ? $lastConsumption['current_reading'] : 0;

// Start page content
ob_start();
?>

<!-- Custom page-specific style to ensure visibility -->
<style>
    .consumption-card {
        border: 3px solid #2B6041 !important;
        background-color: white !important;
        margin-bottom: 30px !important;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
    }
    
    .consumption-card .card-header {
        background-color: #2B6041 !important;
        color: white !important;
        padding: 15px 20px !important;
        border-bottom: 2px solid #1D4B2F !important;
    }
    
    .consumption-card .card-header h6 {
        color: white !important;
        font-weight: bold !important;
        margin: 0 !important;
    }
    
    .consumption-card .card-body {
        background-color: #f0f8f4 !important;
        padding: 25px !important;
    }
    
    .form-label {
        color: #2B6041 !important;
        font-weight: 600 !important;
    }
    
    .form-control {
        border: 2px solid #CED4DA !important;
        background-color: white !important;
    }
    
    .form-hint {
        color: #6C757D !important;
    }
    
    .btn-accent {
        background-color: #EF9651 !important;
        color: white !important;
    }
</style>

<div class="container my-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0" data-aos="fade-right" style="color: #2B6041 !important; font-weight: bold !important;">
            <i class="fas fa-bolt me-2 text-primary"></i>
            Saisie de Consommation
        </h1>
    </div>

    <!-- Instruction Card -->
    <div class="consumption-card mb-4" data-aos="fade-up">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 style="color: #2B6041 !important;"><i class="fas fa-info-circle me-2 text-primary"></i> Comment saisir votre consommation</h4>
                    <ol class="mb-0">
                        <li>Relevez l'index actuel de votre compteur électrique</li>
                        <li>Sélectionnez le mois correspondant</li>
                        <li>Entrez la valeur de l'index dans le formulaire ci-dessous</li>
                        <li>Prenez une photo claire de votre compteur (optionnel mais recommandé)</li>
                        <li>Validez votre saisie</li>
                    </ol>
                    <p class="mt-3 mb-0">
                        <strong>Note:</strong> La consommation est calculée en soustrayant l'index précédent de l'index actuel.
                    </p>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                    <img src="../../uploads/complaints/compteur.jpg" alt="Compteur électrique" class="img-fluid mt-3 mt-md-0" style="max-height: 150px;">
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Consumption Entry Form -->
        <div class="col-lg-8 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="consumption-card">
                <div class="card-header">
                    <h6 class="m-0">
                        <i class="fas fa-edit me-2"></i> Formulaire de Saisie
                    </h6>
                </div>
                <div class="card-body">
                    <form id="consumptionForm" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="client_id" value="<?php echo $clientId; ?>">
                        
                        <!-- Previous Reading Info -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-history me-3 fa-2x"></i>
                                <div>
                                    <h6 class="mb-1">Dernière Valeur Enregistrée</h6>
                                    <p class="mb-0">
                                        <?php if ($lastConsumption): ?>
                                            <strong><?php echo $previousReading; ?> kWh</strong> 
                                            (<?php echo htmlspecialchars($lastConsumption['month']); ?>)
                                        <?php else: ?>
                                            Aucune valeur précédente
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="month" class="form-label">Mois de consommation <span class="text-danger">*</span></label>
                                <select class="form-select" id="month" name="month" required>
                                    <option value="">Sélectionnez un mois</option>
                                    <?php
                                    $months = [
                                        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                                        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                                    ];
                                    
                                    $currentMonth = date('n') - 1; // 0-based index for the current month
                                    $currentYear = date('Y');
                                    
                                    // If we're in the first few days of the month, suggest the previous month
                                    if (date('j') < 5) {
                                        $currentMonth = ($currentMonth - 1 + 12) % 12;
                                        if ($currentMonth == 11) { // If we went back to December
                                            $currentYear--; // Previous year
                                        }
                                    }
                                    
                                    foreach ($months as $index => $month) {
                                        $selected = ($index == $currentMonth) ? 'selected' : '';
                                        $monthYear = "$month $currentYear";
                                        echo "<option value=\"$monthYear\" $selected>$monthYear</option>";
                                    }
                                    ?>
                                </select>
                                <div class="form-hint">Mois pour lequel vous souhaitez faire la saisie</div>
                            </div>
                            <div class="col-md-6">
                                <label for="current_reading" class="form-label">Index actuel (kWh) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="current_reading" name="current_reading" 
                                       min="<?php echo $previousReading; ?>" step="1" required
                                       placeholder="Exemple: <?php echo $previousReading + 150; ?>">
                                <div class="form-hint">La valeur actuelle affichée sur votre compteur</div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="photo" class="form-label">Photo du compteur</label>
                            <input type="file" class="form-control" id="photo" name="meterPhoto" accept="image/*">
                            <div class="form-hint">Optionnel - Une photo de votre compteur électrique</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Informations complémentaires si nécessaire"></textarea>
                        </div>
                        
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" id="resetBtn">
                                <i class="fas fa-undo me-2"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-accent" id="submitBtn">
                                <i class="fas fa-check me-2"></i> Soumettre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Information Panel -->
        <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="consumption-card">
                <div class="card-header">
                    <h6 class="m-0">
                        <i class="fas fa-info-circle me-2"></i> Informations Importantes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 style="color: #2B6041 !important;"><i class="fas fa-calendar-alt me-2"></i> Dates de relevé</h6>
                        <p>Les relevés sont à effectuer entre le 1er et le 5 de chaque mois pour une facturation optimale.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 style="color: #2B6041 !important;"><i class="fas fa-calculator me-2"></i> Calcul de la facture</h6>
                        <p>Votre facture est calculée selon les tranches suivantes :</p>
                        <ul>
                            <li>0-100 kWh : 0.82 DH/kWh</li>
                            <li>101-150 kWh : 0.92 DH/kWh</li>
                            <li>151+ kWh : 1.10 DH/kWh</li>
                        </ul>
                        <p class="small">Une TVA de 18% sera appliquée au montant total.</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important :</strong> Toute saisie erronée peut entraîner une facturation incorrecte. Vérifiez bien les valeurs avant validation.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Alert (Hidden by default) -->
    <div id="resultAlert" class="alert d-none" role="alert"></div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Consommation Enregistrée</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Votre consommation a été enregistrée avec succès !</p>
                <div class="consumption-summary">
                    <div class="row">
                        <div class="col-6 text-end fw-bold">Mois :</div>
                        <div class="col-6" id="summary-month"></div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-end fw-bold">Index relevé :</div>
                        <div class="col-6" id="summary-reading"></div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-end fw-bold">Consommation :</div>
                        <div class="col-6" id="summary-consumption"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const consumptionForm = document.getElementById('consumptionForm');
    const resetBtn = document.getElementById('resetBtn');
    const submitBtn = document.getElementById('submitBtn');
    const resultAlert = document.getElementById('resultAlert');
    const currentReadingInput = document.getElementById('current_reading');
    const previousReading = <?php echo $previousReading; ?>;
    
    // Add validation for current reading
    currentReadingInput.addEventListener('input', function() {
        const currentValue = parseFloat(this.value);
        if (currentValue <= previousReading) {
            this.setCustomValidity(`L'index actuel doit être supérieur à ${previousReading} kWh`);
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Reset form
    resetBtn.addEventListener('click', function() {
        consumptionForm.reset();
        currentReadingInput.setCustomValidity('');
    });
    
    // Form submission
    consumptionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement...';
        
        // Get form data
        const formData = new FormData(consumptionForm);
        
        // Get current and previous readings
        const currentReading = parseInt(document.getElementById('current_reading').value);
        const previousReading = <?php echo $previousReading; ?>;
        
        // Calculate consumption
        const consumption = currentReading - previousReading;
        
        // Add consumption to form data
        formData.append('consumption', consumption);
        
        // For better compatibility, ensure field names match what the backend expects
        formData.append('currentReading', currentReading);
        
        // Add API_REQUEST flag to prevent text output in JSON response
        formData.append('API_REQUEST', 'true');
        
        // Display the form data being sent (for debugging)
        console.log("Sending form data:");
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Send data via AJAX
        fetch('../../traitement/consommationTraitement.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.text().then(text => {
                try {
                    // Try to parse as JSON
                    return JSON.parse(text);
                } catch (e) {
                    // If parse fails, show the raw response for debugging
                    console.error("Failed to parse JSON:", text);
                    throw new Error(`Invalid JSON response: ${text.substring(0, 100)}...`);
                }
            });
        })
        .then(data => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i> Soumettre';
            
            console.log("Response data:", data);
            
            if (data.success) {
                // Show success modal
                document.getElementById('summary-month').textContent = document.getElementById('month').value;
                document.getElementById('summary-reading').textContent = currentReading + ' kWh';
                document.getElementById('summary-consumption').textContent = consumption + ' kWh';
                
                // Show modal
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                
                // Reset form
                consumptionForm.reset();
                
                // Select current month again
                document.querySelector(`#month option[value="${document.getElementById('summary-month').textContent}"]`).selected = true;
            } else {
                // Show error message
                resultAlert.classList.remove('d-none', 'alert-success');
                resultAlert.classList.add('alert-danger');
                
                // Show detailed error information if available
                if (data.errors && data.errors.length > 0) {
                    resultAlert.innerHTML = '<strong>Erreur:</strong><br>' + data.errors.join('<br>');
                } else if (data.debug) {
                    resultAlert.innerHTML = '<strong>Erreur:</strong> ' + data.message + 
                                         '<br><br><details><summary>Détails techniques</summary>' + 
                                         '<pre>' + JSON.stringify(data.debug, null, 2) + '</pre></details>';
                } else {
                    resultAlert.innerHTML = '<strong>Erreur:</strong> ' + data.message;
                }
                
                // Scroll to the error message
                resultAlert.scrollIntoView({ behavior: 'smooth' });
            }
        })
        .catch(error => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i> Soumettre';
            
            // Show error message with technical details for debugging
            resultAlert.classList.remove('d-none', 'alert-success');
            resultAlert.classList.add('alert-danger');
            resultAlert.innerHTML = '<strong>Erreur de connexion:</strong> Échec lors de la communication avec le serveur.' +
                                  '<br><br><details><summary>Détails techniques</summary>' + 
                                  '<pre>' + error.message + '</pre></details>';
            
            // Log error to console for debugging
            console.error('Error submitting form:', error);
            
            // Scroll to the error message
            resultAlert.scrollIntoView({ behavior: 'smooth' });
        });
    });
});
</script>

<?php
$pageContent = ob_get_clean();

// Include the template
require_once '../templates/client_template.php';
?>