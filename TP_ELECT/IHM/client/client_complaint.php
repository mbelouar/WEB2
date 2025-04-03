<?php
session_start();
require_once '../../BD/db.php';
require_once '../../BD/Reclamation.php';

// Set page variables
$pageTitle = 'Réclamations';
$activePage = 'complaint';

// Check if the user is logged in
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

$reclamationModel = new Reclamation($pdo);
$clientId = $_SESSION['client']['id'];

// Récupérer les réclamations du client
$reclamations = $reclamationModel->getReclamationsByClient($clientId);

// Page-specific CSS for any custom styling
$pageSpecificCSS = '<style>
  .complaint-card {
    transition: transform 0.3s;
  }
  .complaint-card:hover {
    transform: translateY(-5px);
  }
</style>';

// Start page content
ob_start();
?>

<div class="container my-4">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" data-aos="fade-right">
      <i class="fas fa-comment-alt me-2 text-primary"></i>
      Mes Réclamations
    </h1>
  </div>

  <!-- New Complaint Form -->
  <div class="card shadow mb-4" data-aos="fade-up">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-edit me-2"></i>Nouvelle Réclamation</h6>
    </div>
    <div class="card-body">
      <form id="complaintForm" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="complaintType" class="form-label">Type de réclamation</label>
          <select id="complaintType" name="complaintType" class="form-select" required>
            <option value="fuite_externe">Fuite Externe</option>
            <option value="fuite_interne">Fuite Interne</option>
            <option value="facture">Facture</option>
            <option value="autre">Autre (à spécifier)</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="complaintDetails" class="form-label">Détails / Description</label>
          <textarea id="complaintDetails" name="complaintDetails" class="form-control" rows="4" placeholder="Décrivez votre problème..." required></textarea>
        </div>
        <div class="mb-3">
          <label for="complaintPhoto" class="form-label">Photo / Pièce jointe (optionnel)</label>
          <input type="file" class="form-control" name="complaintPhoto" id="complaintPhoto" accept="image/*">
        </div>
        <button type="submit" class="btn btn-accent">
          <i class="fas fa-paper-plane me-2"></i>Soumettre la réclamation
        </button>
      </form>
    </div>
  </div>

  <!-- Complaint History -->
  <div class="card shadow" data-aos="fade-up" data-aos-delay="200">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-history me-2"></i>Historique des Réclamations</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Objet</th>
              <th>Description</th>
              <th>Date</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody id="complaintsTable">
            <?php if (!empty($reclamations)): ?>
              <?php foreach ($reclamations as $reclamation): ?>
                <tr>
                  <td><?php echo $reclamation['id']; ?></td>
                  <td><?php echo htmlspecialchars($reclamation['objet']); ?></td>
                  <td><?php echo htmlspecialchars($reclamation['description']); ?></td>
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
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5" class="text-center py-3">Aucune réclamation trouvée</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Script pour gérer l'AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('complaintForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
    
    // Use native fetch instead of axios for better compatibility
    fetch('../../traitement/reclamationTraitement.php?action=add', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        // Reset form and button
        this.reset();
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        // Add the new complaint to the table
        const tableBody = document.getElementById('complaintsTable');
        
        // Remove "no complaints" message if it exists
        const noComplaintsRow = tableBody.querySelector('tr td[colspan="5"]');
        if (noComplaintsRow) {
          tableBody.innerHTML = '';
        }
        
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td>${data.reclamation.id}</td>
          <td>${data.reclamation.objet}</td>
          <td>${data.reclamation.description}</td>
          <td>${data.reclamation.date_reclamation}</td>
          <td><span class="badge rounded-pill bg-warning">${data.reclamation.statut}</span></td>
        `;
        tableBody.insertBefore(newRow, tableBody.firstChild);
        
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
          <i class="fas fa-check-circle me-2"></i>
          Votre réclamation a été soumise avec succès!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('form'));
        
        // Auto-dismiss alert after 5 seconds
        setTimeout(() => {
          alertDiv.classList.remove('show');
          setTimeout(() => alertDiv.remove(), 500);
        }, 5000);
        
      } else {
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        // Show error message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.innerHTML = `
          <i class="fas fa-exclamation-triangle me-2"></i>
          Erreur: ${data.message || 'Une erreur est survenue'}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('form'));
      }
    })
    .catch(error => {
      console.error('Erreur:', error);
      
      // Reset button
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;
      
      // Show error message
      const alertDiv = document.createElement('div');
      alertDiv.className = 'alert alert-danger alert-dismissible fade show';
      alertDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>
        Une erreur est survenue lors de la soumission de votre réclamation.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;
      document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('form'));
    });
  });
});
</script>

<?php
$pageContent = ob_get_clean();

// Include the template
require_once '../templates/client_template.php';
?>