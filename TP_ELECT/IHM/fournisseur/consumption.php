<?php
session_start();

// Define API_REQUEST constant to prevent any text output from db.php in AJAX calls
define('API_REQUEST', true);

if (!isset($_SESSION['fournisseur'])) {
    header("Location: login.php");
    exit;
}

require_once '../../BD/db.php';
require_once '../../BD/Consumption.php';
require_once '../../BD/Client.php';

// Récupérer les consommations en attente
$consumptionModel = new Consumption($pdo);
$pendingConsumptions = $consumptionModel->getPendingConsumptions();

// Récupérer la liste des clients
$clientModel = new Client($pdo);
$clients = $clientModel->getAllClients();

// Page title
$pageTitle = "Gestion des Consommations";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle; ?> - Gestion d'Électricité</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../assets/css/fournisseur-style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
      <i class="fas fa-bolt me-2"></i>
      <span>Gestion d'Électricité</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">
            <i class="fas fa-home me-1"></i> Tableau de bord
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="clients.php">
            <i class="fas fa-users me-1"></i> Clients
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="consumption.php">
            <i class="fas fa-tachometer-alt me-1"></i> Consommations
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reclamations.php">
            <i class="fas fa-comment-alt me-1"></i> Réclamations
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../traitement/fournisseurTraitement.php?action=logout">
            <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container my-4">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" data-aos="fade-right">
      <i class="fas fa-tachometer-alt me-2 text-primary"></i>
      <?php echo $pageTitle; ?>
    </h1>
  </div>

  <!-- Anomalies et validations -->
  <div class="card shadow mb-4" data-aos="fade-up">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-white">
        <i class="fas fa-exclamation-triangle me-2"></i> Traitement des Anomalies
      </h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Client</th>
              <th>Mois</th>
              <th>Relevé</th>
              <th>Consommation</th>
              <th>Date</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="consumptionsTable">
            <?php if (!empty($pendingConsumptions)): ?>
              <?php foreach ($pendingConsumptions as $consumption): ?>
                <tr class="consumption-row" data-id="<?php echo $consumption['id']; ?>">
                  <td><?php echo $consumption['id']; ?></td>
                  <td><?php echo htmlspecialchars($consumption['client_nom'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($consumption['mois']); ?></td>
                  <td><?php echo number_format($consumption['index_actuel'], 0, ',', ' '); ?> kWh</td>
                  <td><?php echo number_format($consumption['consommation'], 0, ',', ' '); ?> kWh</td>
                  <td><?php echo $consumption['date_saisie']; ?></td>
                  <td>
                    <span class="badge rounded-pill
                    <?php
                      switch($consumption['statut']) {
                        case 'validé': echo 'bg-success'; break;
                        case 'refusé': echo 'bg-danger'; break;
                        default: echo 'bg-warning';
                      }
                    ?>">
                      <?php echo htmlspecialchars($consumption['statut']); ?>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-primary view-consumption" data-id="<?php echo $consumption['id']; ?>">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning edit-consumption" data-id="<?php echo $consumption['id']; ?>">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-success validate-consumption" data-id="<?php echo $consumption['id']; ?>">
                      <i class="fas fa-check"></i>
                    </button>
                    <button class="btn btn-sm btn-danger reject-consumption" data-id="<?php echo $consumption['id']; ?>">
                      <i class="fas fa-times"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8" class="text-center py-3">Aucune consommation en attente</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Ajout manuel de consommation -->
  <div class="card shadow mb-4" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-white">
        <i class="fas fa-plus-circle me-2"></i> Ajouter une Consommation
      </h6>
    </div>
    <div class="card-body">
      <form id="addConsumptionForm" class="row g-3">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="API_REQUEST" value="true">
        
        <div class="col-md-4">
          <label for="client" class="form-label">Client</label>
          <select id="client" name="client_id" class="form-select" required>
            <option value="">Sélectionnez un client</option>
            <?php foreach ($clients as $client): ?>
              <option value="<?php echo $client['id']; ?>">
                <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="col-md-4">
          <label for="month" class="form-label">Mois</label>
          <select id="month" name="month" class="form-select" required>
            <option value="">Sélectionnez un mois</option>
            <?php
            $months = [
              'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
              'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ];
            $currentYear = date('Y');
            
            foreach ($months as $month) {
              echo "<option value=\"$month $currentYear\">$month $currentYear</option>";
            }
            ?>
          </select>
        </div>
        
        <div class="col-md-4">
          <label for="current_reading" class="form-label">Index actuel (kWh)</label>
          <input type="number" id="current_reading" name="current_reading" class="form-control" required min="0">
        </div>
        
        <div class="col-12 mt-4">
          <button type="submit" class="btn btn-accent" id="submitConsumption">
            <i class="fas fa-save me-1"></i> Enregistrer
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo me-1"></i> Réinitialiser
          </button>
        </div>
      </form>
      
      <!-- Alert -->
      <div id="consumptionAlert" class="alert mt-4 d-none" role="alert"></div>
    </div>
  </div>
</div>

<!-- Consumption Detail/Edit Modal -->
<div class="modal fade" id="consumptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails de Consommation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="consumptionModalBody">
        <!-- Will be populated by JS -->
        <div class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Fermer
        </button>
        <button type="button" class="btn btn-success" id="validateBtn">
          <i class="fas fa-check me-1"></i> Valider
        </button>
        <button type="button" class="btn btn-danger" id="rejectBtn">
          <i class="fas fa-ban me-1"></i> Rejeter
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Consumption Modal -->
<div class="modal fade" id="editConsumptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modifier la Consommation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editConsumptionForm">
          <input type="hidden" id="edit_id" name="id">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="API_REQUEST" value="true">
          
          <div class="mb-3">
            <label for="edit_client" class="form-label">Client</label>
            <input type="text" id="edit_client" class="form-control" readonly>
          </div>
          
          <div class="mb-3">
            <label for="edit_month" class="form-label">Mois</label>
            <input type="text" id="edit_month" name="month" class="form-control" readonly>
          </div>
          
          <div class="mb-3">
            <label for="edit_previous" class="form-label">Index précédent (kWh)</label>
            <input type="number" id="edit_previous" class="form-control" readonly>
          </div>
          
          <div class="mb-3">
            <label for="edit_current" class="form-label">Index actuel (kWh)</label>
            <input type="number" id="edit_current" name="current_reading" class="form-control" required min="0">
            <div class="form-text">L'index actuel doit être supérieur à l'index précédent.</div>
          </div>
          
          <div class="mb-3">
            <label for="edit_comment" class="form-label">Commentaire (raison de la modification)</label>
            <textarea id="edit_comment" name="comment" class="form-control" rows="3" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="saveEditBtn">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="py-4 mt-auto">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center small">
      <div class="text-light">
        &copy; <?php echo date('Y'); ?> Gestion d'Électricité - Interface Fournisseur
      </div>
      <div>
        <a href="#" class="text-light">Conditions d'utilisation</a>
        &middot;
        <a href="#" class="text-light">Politique de confidentialité</a>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- SweetAlert2 for nice alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Initialize AOS animations
  AOS.init({
    duration: 800,
    once: true
  });
  
  // View consumption details
  document.querySelectorAll('.view-consumption').forEach(button => {
    button.addEventListener('click', function() {
      const consumptionId = this.dataset.id;
      const modal = new bootstrap.Modal(document.getElementById('consumptionModal'));
      
      // Get consumption details
      fetch(`../../traitement/consommationTraitement.php?action=get_details&id=${consumptionId}&API_REQUEST=true`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const consumption = data.consumption;
            const modalBody = document.getElementById('consumptionModalBody');
            
            // Update modal content
            let photoHtml = '';
            if (consumption.photo_url) {
              photoHtml = `
                <div class="text-center mb-3 mt-4">
                  <img src="${consumption.photo_url}" alt="Photo du compteur" class="img-fluid rounded" style="max-height: 300px;">
                  <p class="mt-2 text-muted">Photo du compteur</p>
                </div>
              `;
            }
            
            modalBody.innerHTML = `
              <div class="row">
                <div class="col-md-6">
                  <p><strong>Client:</strong> ${consumption.client_nom}</p>
                  <p><strong>Mois:</strong> ${consumption.mois}</p>
                  <p><strong>Date de saisie:</strong> ${consumption.date_saisie}</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Index précédent:</strong> ${consumption.index_precedent} kWh</p>
                  <p><strong>Index actuel:</strong> ${consumption.index_actuel} kWh</p>
                  <p><strong>Consommation:</strong> ${consumption.consommation} kWh</p>
                </div>
              </div>
              
              <div class="alert alert-${consumption.anomalie ? 'warning' : 'success'} mt-3">
                <i class="fas fa-${consumption.anomalie ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
                <strong>${consumption.anomalie ? 'Anomalie détectée' : 'Consommation normale'}</strong>
                ${consumption.anomalie ? ' - ' + consumption.commentaire : ''}
              </div>
              
              ${photoHtml}
            `;
            
            // Update action buttons
            const validateBtn = document.getElementById('validateBtn');
            const rejectBtn = document.getElementById('rejectBtn');
            
            validateBtn.dataset.id = consumptionId;
            rejectBtn.dataset.id = consumptionId;
            
            if (consumption.statut !== 'en attente') {
              validateBtn.disabled = true;
              rejectBtn.disabled = true;
            } else {
              validateBtn.disabled = false;
              rejectBtn.disabled = false;
            }
            
            modal.show();
          } else {
            Swal.fire('Erreur', data.message || 'Impossible de charger les détails', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Erreur', 'Une erreur est survenue lors du chargement des détails', 'error');
        });
    });
  });
  
  // Edit consumption
  document.querySelectorAll('.edit-consumption').forEach(button => {
    button.addEventListener('click', function() {
      const consumptionId = this.dataset.id;
      
      // Get consumption details to populate the form
      fetch(`../../traitement/consommationTraitement.php?action=get_details&id=${consumptionId}&API_REQUEST=true`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const consumption = data.consumption;
            
            // Populate form
            document.getElementById('edit_id').value = consumption.id;
            document.getElementById('edit_client').value = consumption.client_nom;
            document.getElementById('edit_month').value = consumption.mois;
            document.getElementById('edit_previous').value = consumption.index_precedent;
            document.getElementById('edit_current').value = consumption.index_actuel;
            
            // Show modal
            const editModal = new bootstrap.Modal(document.getElementById('editConsumptionModal'));
            editModal.show();
          } else {
            Swal.fire('Erreur', data.message || 'Impossible de charger les détails', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Erreur', 'Une erreur est survenue lors du chargement des détails', 'error');
        });
    });
  });
  
  // Validate consumption
  document.querySelectorAll('.validate-consumption').forEach(button => {
    button.addEventListener('click', validateConsumption);
  });
  
  document.getElementById('validateBtn').addEventListener('click', function() {
    validateConsumption.call(this);
  });
  
  function validateConsumption() {
    const consumptionId = this.dataset.id;
    
    Swal.fire({
      title: 'Valider cette consommation?',
      text: 'Cette action permettra la génération de la facture.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Oui, valider',
      cancelButtonText: 'Annuler'
    }).then((result) => {
      if (result.isConfirmed) {
        // Send validation request
        const formData = new FormData();
        formData.append('id', consumptionId);
        formData.append('action', 'validate');
        formData.append('API_REQUEST', 'true');
        
        fetch('../../traitement/consommationTraitement.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              title: 'Validée!',
              text: data.message || 'La consommation a été validée avec succès.',
              icon: 'success'
            }).then(() => {
              // Reload page to show updated data
              window.location.reload();
            });
          } else {
            Swal.fire('Erreur', data.message || 'Une erreur est survenue', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Erreur', 'Une erreur de connexion est survenue', 'error');
        });
      }
    });
  }
  
  // Reject consumption
  document.querySelectorAll('.reject-consumption').forEach(button => {
    button.addEventListener('click', rejectConsumption);
  });
  
  document.getElementById('rejectBtn').addEventListener('click', function() {
    rejectConsumption.call(this);
  });
  
  function rejectConsumption() {
    const consumptionId = this.dataset.id;
    
    Swal.fire({
      title: 'Rejeter cette consommation?',
      text: 'Le client devra soumettre une nouvelle saisie.',
      icon: 'warning',
      input: 'text',
      inputLabel: 'Raison du rejet',
      inputPlaceholder: 'Entrez la raison du rejet...',
      inputAttributes: {
        required: 'true'
      },
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Oui, rejeter',
      cancelButtonText: 'Annuler',
      preConfirm: (reason) => {
        if (!reason.trim()) {
          Swal.showValidationMessage('Veuillez entrer une raison');
        }
        return reason;
      }
    }).then((result) => {
      if (result.isConfirmed) {
        // Send reject request with reason
        const formData = new FormData();
        formData.append('id', consumptionId);
        formData.append('reason', result.value);
        formData.append('action', 'reject');
        formData.append('API_REQUEST', 'true');
        
        fetch('../../traitement/consommationTraitement.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              title: 'Rejetée!',
              text: data.message || 'La consommation a été rejetée.',
              icon: 'success'
            }).then(() => {
              // Reload page to show updated data
              window.location.reload();
            });
          } else {
            Swal.fire('Erreur', data.message || 'Une erreur est survenue', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Erreur', 'Une erreur de connexion est survenue', 'error');
        });
      }
    });
  }
  
  // Save edited consumption
  document.getElementById('saveEditBtn').addEventListener('click', function() {
    const form = document.getElementById('editConsumptionForm');
    const formData = new FormData(form);
    
    // Validate form
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }
    
    // Show loading state
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement...';
    
    // Send request
    fetch('../../traitement/consommationTraitement.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      // Reset button state
      this.disabled = false;
      this.innerHTML = 'Enregistrer';
      
      if (data.success) {
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editConsumptionModal'));
        modal.hide();
        
        Swal.fire({
          title: 'Succès!',
          text: data.message || 'Consommation modifiée avec succès',
          icon: 'success'
        }).then(() => {
          // Reload page
          window.location.reload();
        });
      } else {
        Swal.fire('Erreur', data.message || 'Une erreur est survenue', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      
      // Reset button state
      this.disabled = false;
      this.innerHTML = 'Enregistrer';
      
      Swal.fire('Erreur', 'Une erreur de connexion est survenue', 'error');
    });
  });
  
  // Add consumption form
  document.getElementById('addConsumptionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = document.getElementById('submitConsumption');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement...';
    
    // Create form data
    const formData = new FormData(this);
    
    // Send request
    fetch('../../traitement/consommationTraitement.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      // Reset button state
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Enregistrer';
      
      const alert = document.getElementById('consumptionAlert');
      
      if (data.success) {
        // Show success message
        alert.className = 'alert alert-success mt-4';
        alert.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + (data.message || 'Consommation ajoutée avec succès');
        alert.classList.remove('d-none');
        
        // Reset form
        this.reset();
        
        // Reload after 2 seconds
        setTimeout(() => {
          window.location.reload();
        }, 2000);
      } else {
        // Show error message
        alert.className = 'alert alert-danger mt-4';
        alert.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + (data.message || 'Une erreur est survenue');
        alert.classList.remove('d-none');
      }
      
      // Hide alert after 5 seconds
      setTimeout(() => {
        alert.classList.add('d-none');
      }, 5000);
    })
    .catch(error => {
      console.error('Error:', error);
      
      // Reset button state
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Enregistrer';
      
      // Show error message
      const alert = document.getElementById('consumptionAlert');
      alert.className = 'alert alert-danger mt-4';
      alert.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> Une erreur de connexion est survenue';
      alert.classList.remove('d-none');
      
      // Hide alert after 5 seconds
      setTimeout(() => {
        alert.classList.add('d-none');
      }, 5000);
    });
  });
  
  // Check if client has previous reading when selected
  document.getElementById('client').addEventListener('change', function() {
    const clientId = this.value;
    
    if (!clientId) return;
    
    fetch(`../../traitement/consommationTraitement.php?action=get_previous&client_id=${clientId}&API_REQUEST=true`)
      .then(response => response.json())
      .then(data => {
        const currentReadingInput = document.getElementById('current_reading');
        
        if (data.success && data.previous_reading) {
          currentReadingInput.min = data.previous_reading;
          currentReadingInput.placeholder = `Min: ${data.previous_reading}`;
        } else {
          currentReadingInput.min = 0;
          currentReadingInput.placeholder = '';
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  });
</script>

</body>
</html>
