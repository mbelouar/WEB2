<?php
session_start();
if (!isset($_SESSION['fournisseur'])) {
    header("Location: login.php");
    exit;
}
require_once '../../BD/db.php';
require_once '../../BD/Reclamation.php';

// Récupérer les réclamations
$reclamationModel = new Reclamation($pdo);
$reclamations = $reclamationModel->getAllReclamations();

// Page title
$pageTitle = "Gestion des Réclamations";
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

<!-- Page Loader -->
<div class="page-loader">
  <img src="../../uploads/Lydec.png" alt="Lydec" class="loader-logo">
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
      <img src="../../uploads/Lydec.png" alt="Lydec" class="logo">
      <div class="brand-text">
        <span class="brand-name">Lydec</span>
        <small class="brand-tagline d-none d-sm-inline">Électricité & Eau</small>
      </div>
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
          <a class="nav-link" href="consumption.php">
            <i class="fas fa-tachometer-alt me-1"></i> Consommations
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="reclamations.php">
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
      <i class="fas fa-comment-alt me-2 text-primary"></i>
      <?php echo $pageTitle; ?>
    </h1>
    <div data-aos="fade-left">
      <!-- Filtres -->
      <select class="form-select form-select-sm d-inline-block w-auto" id="statusFilter">
        <option value="all">Tous les statuts</option>
        <option value="en attente">En attente</option>
        <option value="traitée">Traitées</option>
        <option value="rejetée">Rejetées</option>
      </select>
    </div>
  </div>

  <!-- Liste des réclamations -->
  <div class="card shadow mb-4" data-aos="fade-up">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-white">
        <i class="fas fa-list me-2"></i> Liste des Réclamations
      </h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Client</th>
              <th>Type</th>
              <th>Description</th>
              <th>Date</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="complaintsTable">
            <?php if (!empty($reclamations)): ?>
              <?php foreach ($reclamations as $reclamation): ?>
                <tr class="complaint-row" data-status="<?php echo htmlspecialchars($reclamation['statut']); ?>">
                  <td><?php echo $reclamation['id']; ?></td>
                  <td><?php echo htmlspecialchars($reclamation['client_nom'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($reclamation['objet']); ?></td>
                  <td><?php echo htmlspecialchars(substr($reclamation['description'], 0, 100)) . (strlen($reclamation['description']) > 100 ? '...' : ''); ?></td>
                  <td><?php echo $reclamation['date_reclamation']; ?></td>
                  <td>
                    <span class="badge rounded-pill
                    <?php
                      switch($reclamation['statut']) {
                        case 'en attente': echo 'bg-warning'; break;
                        case 'traitée': echo 'bg-success'; break;
                        case 'rejetée': echo 'bg-danger'; break;
                        default: echo 'bg-secondary';
                      }
                    ?>">
                      <?php echo htmlspecialchars($reclamation['statut']); ?>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-primary view-complaint" data-id="<?php echo $reclamation['id']; ?>">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-success update-status" data-id="<?php echo $reclamation['id']; ?>" data-status="traitée" <?php echo ($reclamation['statut'] != 'en attente') ? 'disabled' : ''; ?>>
                      <i class="fas fa-check"></i>
                    </button>
                    <button class="btn btn-sm btn-danger update-status" data-id="<?php echo $reclamation['id']; ?>" data-status="rejetée" <?php echo ($reclamation['statut'] != 'en attente') ? 'disabled' : ''; ?>>
                      <i class="fas fa-times"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="7" class="text-center py-3">Aucune réclamation trouvée</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Complaint Detail Modal -->
<div class="modal fade" id="complaintModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails de la Réclamation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="complaintModalBody">
        <!-- Content will be loaded here -->
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
        <button type="button" class="btn btn-success" id="markAsTreated" data-id="">
          <i class="fas fa-check me-1"></i> Marquer comme traitée
        </button>
        <button type="button" class="btn btn-danger" id="markAsRejected" data-id="">
          <i class="fas fa-ban me-1"></i> Rejeter
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Répondre à la Réclamation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="replyForm">
          <input type="hidden" id="complaintId" name="complaintId">
          <input type="hidden" id="newStatus" name="newStatus">
          
          <div class="mb-3">
            <label for="replyMessage" class="form-label">Message de réponse</label>
            <textarea class="form-control" id="replyMessage" name="replyMessage" rows="4" required></textarea>
          </div>
          
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="sendEmail" name="sendEmail" checked>
            <label class="form-check-label" for="sendEmail">Envoyer une notification par email</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="submitReply">Envoyer</button>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4 mb-md-0">
        <h5>Lydec</h5>
        <p class="mb-3">Votre fournisseur d'électricité et d'eau, engagé pour un service de qualité et un développement durable.</p>
        <div class="d-flex mt-4">
          <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
          <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
          <a href="#" class="me-3"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="col-md-3 mb-4 mb-md-0">
        <h5>Navigation</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="dashboard.php">Tableau de bord</a></li>
          <li class="mb-2"><a href="clients.php">Clients</a></li>
          <li class="mb-2"><a href="consumption.php">Consommations</a></li>
          <li class="mb-2"><a href="reclamations.php">Réclamations</a></li>
        </ul>
      </div>
      <div class="col-md-5">
        <h5>Contact</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 48, Rue Mohamed Diouri, Casablanca</li>
          <li class="mb-2"><i class="fas fa-phone me-2"></i> 05 22 54 90 00</li>
          <li class="mb-2"><i class="fas fa-envelope me-2"></i> <a href="mailto:service-client@lydec.ma">service-client@lydec.ma</a></li>
          <li class="mb-2"><i class="fas fa-clock me-2"></i> Lun-Ven: 8h00-16h30</li>
        </ul>
      </div>
    </div>
    <div class="border-top border-secondary pt-4 mt-4 text-center">
      <p class="mb-0">&copy; <?php echo date('Y'); ?> - Lydec. Tous droits réservés.</p>
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
  
  // Hide page loader after page loads
  window.addEventListener('load', function() {
    const loader = document.querySelector('.page-loader');
    if (loader) {
      loader.classList.add('hidden');
      setTimeout(() => {
        loader.style.display = 'none';
      }, 500);
    }
  });
  
  // Function to fetch complaint details
  async function fetchComplaintDetails(complaintId) {
    try {
      // Define API_REQUEST to prevent text output in JSON response
      const url = `../../traitement/reclamationTraitement.php?action=get_details&id=${complaintId}&api_request=true`;
      const response = await fetch(url);
      const data = await response.json();
      return data;
    } catch (error) {
      console.error('Error fetching complaint details:', error);
      return { success: false, message: 'Erreur lors de la récupération des détails' };
    }
  }
  
  // Status filter
  document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('.complaint-row');
    
    rows.forEach(row => {
      if (status === 'all' || row.dataset.status === status) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
  
  // View complaint details
  document.querySelectorAll('.view-complaint').forEach(button => {
    button.addEventListener('click', function() {
      const complaintId = this.dataset.id;
      const modal = new bootstrap.Modal(document.getElementById('complaintModal'));
      
      // Get complaint details
      fetchComplaintDetails(complaintId).then(data => {
        if (data.success) {
          const complaint = data.complaint;
          const modalBody = document.getElementById('complaintModalBody');
          
          // Update modal with complaint details
          let photoHtml = '';
          if (complaint.photo_url) {
            photoHtml = `
              <div class="text-center mb-3">
                <img src="${complaint.photo_url}" alt="Photo jointe" class="img-fluid rounded" style="max-height: 300px;">
              </div>
            `;
          }
          
          modalBody.innerHTML = `
            <div class="row mb-4">
              <div class="col-md-6">
                <p><strong>Client:</strong> ${complaint.client_nom || 'N/A'}</p>
                <p><strong>Date:</strong> ${complaint.date_reclamation}</p>
                <p><strong>Type:</strong> ${complaint.objet}</p>
              </div>
              <div class="col-md-6">
                <p><strong>Status:</strong> 
                  <span class="badge rounded-pill 
                    ${complaint.statut === 'en attente' ? 'bg-warning' : 
                     complaint.statut === 'traitée' ? 'bg-success' : 'bg-danger'}">
                    ${complaint.statut}
                  </span>
                </p>
                ${complaint.date_traitement ? `<p><strong>Date de traitement:</strong> ${complaint.date_traitement}</p>` : ''}
                ${complaint.reponse ? `<p><strong>Réponse:</strong> ${complaint.reponse}</p>` : ''}
              </div>
            </div>
            
            <div class="card mb-3">
              <div class="card-header">
                <h6 class="m-0 font-weight-bold">Description</h6>
              </div>
              <div class="card-body">
                <p>${complaint.description}</p>
              </div>
            </div>
            
            ${photoHtml}
          `;
          
          // Update action buttons based on status
          const treatedBtn = document.getElementById('markAsTreated');
          const rejectedBtn = document.getElementById('markAsRejected');
          
          treatedBtn.dataset.id = complaintId;
          rejectedBtn.dataset.id = complaintId;
          
          if (complaint.statut !== 'en attente') {
            treatedBtn.disabled = true;
            rejectedBtn.disabled = true;
          } else {
            treatedBtn.disabled = false;
            rejectedBtn.disabled = false;
          }
          
          modal.show();
        } else {
          Swal.fire('Erreur', data.message || 'Impossible de charger les détails', 'error');
        }
      }).catch(error => {
        console.error('Error:', error);
        Swal.fire('Erreur', 'Une erreur est survenue lors du chargement des détails', 'error');
      });
    });
  });
  
  // Update status buttons
  document.querySelectorAll('.update-status').forEach(button => {
    button.addEventListener('click', function() {
      const complaintId = this.dataset.id;
      const newStatus = this.dataset.status;
      
      // Show reply modal
      document.getElementById('complaintId').value = complaintId;
      document.getElementById('newStatus').value = newStatus;
      
      const replyModalTitle = document.querySelector('#replyModal .modal-title');
      if (newStatus === 'traitée') {
        replyModalTitle.textContent = 'Marquer comme traitée';
      } else {
        replyModalTitle.textContent = 'Rejeter la réclamation';
      }
      
      const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
      replyModal.show();
    });
  });
  
  // Modal action buttons
  document.getElementById('markAsTreated').addEventListener('click', function() {
    const complaintId = this.dataset.id;
    
    document.getElementById('complaintId').value = complaintId;
    document.getElementById('newStatus').value = 'traitée';
    
    const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
    document.querySelector('#replyModal .modal-title').textContent = 'Marquer comme traitée';
    
    // Hide the first modal
    const complaintModal = bootstrap.Modal.getInstance(document.getElementById('complaintModal'));
    complaintModal.hide();
    
    // Show reply modal
    replyModal.show();
  });
  
  document.getElementById('markAsRejected').addEventListener('click', function() {
    const complaintId = this.dataset.id;
    
    document.getElementById('complaintId').value = complaintId;
    document.getElementById('newStatus').value = 'rejetée';
    
    const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
    document.querySelector('#replyModal .modal-title').textContent = 'Rejeter la réclamation';
    
    // Hide the first modal
    const complaintModal = bootstrap.Modal.getInstance(document.getElementById('complaintModal'));
    complaintModal.hide();
    
    // Show reply modal
    replyModal.show();
  });
  
  // Submit reply
  document.getElementById('submitReply').addEventListener('click', function() {
    const form = document.getElementById('replyForm');
    const complaintId = document.getElementById('complaintId').value;
    const newStatus = document.getElementById('newStatus').value;
    const replyMessage = document.getElementById('replyMessage').value;
    const sendEmail = document.getElementById('sendEmail').checked;
    
    if (!replyMessage.trim()) {
      Swal.fire('Erreur', 'Veuillez saisir un message de réponse', 'error');
      return;
    }
    
    // Show loading state
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement...';
    
    // Send request to update status
    const formData = new FormData();
    formData.append('id', complaintId);
    formData.append('status', newStatus);
    formData.append('response', replyMessage);
    formData.append('sendEmail', sendEmail ? '1' : '0');
    
    fetch('../../traitement/reclamationTraitement.php?action=update_status', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      // Reset button state
      this.disabled = false;
      this.innerHTML = 'Envoyer';
      
      if (data.success) {
        // Close modal
        const replyModal = bootstrap.Modal.getInstance(document.getElementById('replyModal'));
        replyModal.hide();
        
        // Show success message
        Swal.fire({
          title: 'Succès!',
          text: data.message || 'Statut mis à jour avec succès',
          icon: 'success',
          confirmButtonColor: '#2B6041'
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
      
      // Reset button state
      this.disabled = false;
      this.innerHTML = 'Envoyer';
      
      Swal.fire('Erreur', 'Une erreur de connexion est survenue', 'error');
    });
  });
</script>

</body>
</html>
