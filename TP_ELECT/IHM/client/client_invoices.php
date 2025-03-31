<?php
session_start();

// Define API_REQUEST constant to prevent any text output from db.php
define('API_REQUEST', true);

require_once '../../BD/db.php';
require_once '../../BD/Facture.php';

// Set page variables
$pageTitle = 'Mes Factures';
$activePage = 'invoices';

// Check if the user is logged in
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

// Get client info
$clientId = $_SESSION['client']['id'];
$clientName = $_SESSION['client']['nom'] . ' ' . $_SESSION['client']['prenom'];

// Get factures for client
$factureModel = new Facture($pdo);
$factures = $factureModel->getFacturesByClient($clientId);

// Calculate statistics
$totalInvoices = count($factures);
$totalPaid = 0;
$totalUnpaid = 0;
$totalAmount = 0;
$totalDue = 0;

foreach ($factures as $facture) {
    $totalAmount += $facture['montant'];
    if ($facture['statut'] === 'payée') {
        $totalPaid++;
    } else {
        $totalUnpaid++;
        $totalDue += $facture['montant'];
    }
}

// Start page content
ob_start();
?>

<div class="container my-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0" data-aos="fade-right">
            <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
            Mes Factures
        </h1>
        <div class="filters" data-aos="fade-left">
            <div class="input-group">
                <label class="input-group-text" for="statusFilter">Statut:</label>
                <select class="form-select" id="statusFilter">
                    <option value="all">Tous</option>
                    <option value="impayée">Impayées</option>
                    <option value="payée">Payées</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Invoices Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo $totalInvoices; ?>
                </div>
                <div class="stat-label">Factures Totales</div>
                <i class="fas fa-file-invoice stat-icon"></i>
            </div>
        </div>
        
        <!-- Paid Invoices Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo $totalPaid; ?>
                </div>
                <div class="stat-label">Factures Payées</div>
                <i class="fas fa-check-circle stat-icon"></i>
            </div>
        </div>
        
        <!-- Unpaid Invoices Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo $totalUnpaid; ?>
                </div>
                <div class="stat-label">Factures Impayées</div>
                <i class="fas fa-exclamation-circle stat-icon"></i>
            </div>
        </div>
        
        <!-- Total Due Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo number_format($totalDue, 2); ?>
                    <small>DH</small>
                </div>
                <div class="stat-label">Montant Total Dû</div>
                <i class="fas fa-money-bill-wave stat-icon"></i>
            </div>
        </div>
    </div>
    
    <!-- Invoices List -->
    <div class="card shadow mb-4" data-aos="fade-up">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-list me-2"></i> Liste des Factures
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($factures)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Vous n'avez aucune facture pour le moment.
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover" id="invoicesTable">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Date d'émission</th>
                            <th>Consommation</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($factures as $facture): ?>
                        <tr class="invoice-row <?php echo $facture['statut']; ?>" data-id="<?php echo $facture['id']; ?>">
                            <td>#<?php echo $facture['id']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($facture['date_emission'])); ?></td>
                            <td>
                                <?php 
                                    echo isset($facture['kwh_consumed']) ? $facture['kwh_consumed'] . ' kWh' : 'N/A'; 
                                ?>
                            </td>
                            <td><?php echo number_format($facture['montant'], 2); ?> DH</td>
                            <td>
                                <?php if ($facture['statut'] === 'payée'): ?>
                                <span class="badge bg-success">Payée</span>
                                <?php else: ?>
                                <span class="badge bg-warning">Impayée</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info view-invoice me-1" data-id="<?php echo $facture['id']; ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if ($facture['statut'] === 'impayée'): ?>
                                <button class="btn btn-sm btn-success pay-invoice" data-id="<?php echo $facture['id']; ?>">
                                    <i class="fas fa-credit-card"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Invoice Detail Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détail de Facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="invoiceModalBody">
                <!-- Invoice content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="printInvoiceBtn">
                    <i class="fas fa-print me-1"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Paiement de Facture #<span id="paymentInvoiceId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <p class="mb-1">Choisissez votre méthode de paiement préférée:</p>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="cardPayment" value="card" checked>
                        <label class="form-check-label" for="cardPayment">
                            <i class="fas fa-credit-card me-2"></i> Carte bancaire
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="transferPayment" value="transfer">
                        <label class="form-check-label" for="transferPayment">
                            <i class="fas fa-university me-2"></i> Virement bancaire
                        </label>
                    </div>
                </div>
                
                <div id="cardPaymentForm">
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">Numéro de carte</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                            <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiryDate" class="form-label">Date d'expiration</label>
                            <input type="text" class="form-control" id="expiryDate" placeholder="MM/AA">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" placeholder="123">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cardHolder" class="form-label">Titulaire de la carte</label>
                        <input type="text" class="form-control" id="cardHolder" placeholder="Nom du titulaire">
                    </div>
                </div>
                
                <div id="transferPaymentForm" style="display: none;">
                    <div class="alert alert-info">
                        <p class="mb-1"><strong>Informations bancaires:</strong></p>
                        <p class="mb-1">IBAN: MA12 3456 7890 1234 5678 9012 3456</p>
                        <p class="mb-1">BIC: LYDEMA12</p>
                        <p class="mb-1">Banque: Bank Al-Maghrib</p>
                        <p class="mb-0">Référence: Facture #<span id="transferInvoiceId"></span></p>
                    </div>
                    <div class="mb-3">
                        <label for="transferDate" class="form-label">Date prévue du virement</label>
                        <input type="date" class="form-control" id="transferDate">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="confirmPaymentBtn">
                    <i class="fas fa-check me-1"></i> Confirmer le paiement
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

// Include the template
require_once '../templates/client_template.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const invoiceRows = document.querySelectorAll('.invoice-row');
    
    statusFilter.addEventListener('change', function() {
        const value = this.value;
        
        invoiceRows.forEach(row => {
            if (value === 'all') {
                row.style.display = '';
            } else if (row.classList.contains(value)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // View invoice functionality
    const viewInvoiceButtons = document.querySelectorAll('.view-invoice');
    const invoiceModal = document.getElementById('invoiceModal');
    const invoiceModalBody = document.getElementById('invoiceModalBody');
    const printInvoiceBtn = document.getElementById('printInvoiceBtn');
    
    viewInvoiceButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const invoiceId = this.getAttribute('data-id');
            
            // Show loading spinner
            invoiceModalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            `;
            
            // Show modal
            const modal = new bootstrap.Modal(invoiceModal);
            modal.show();
            
            // Set the invoice ID for printing
            printInvoiceBtn.setAttribute('data-id', invoiceId);
            
            // Here you would fetch the invoice details from server
            // For now, we'll simulate loading with a timeout
            setTimeout(() => {
                // Find the invoice in our list
                const invoiceRow = document.querySelector(`.invoice-row[data-id="\${invoiceId}"]`);
                
                // Create invoice template (this would be fetched from server in a real app)
                invoiceModalBody.innerHTML = `
                    <div class="invoice-detail">
                        <div class="row mb-4">
                            <div class="col-6">
                                <img src="../../uploads/Lydec.png" alt="Lydec" style="height: 60px;">
                                <p class="mt-2 mb-1">Lydec</p>
                                <p class="mb-1">48, Rue Mohamed Diouri</p>
                                <p class="mb-1">Casablanca, Maroc</p>
                                <p class="mb-1">05 22 54 90 00</p>
                            </div>
                            <div class="col-6 text-end">
                                <h5>FACTURE #\${invoiceId}</h5>
                                <p class="mb-1">Date d'émission: 01/06/2023</p>
                                <p class="mb-1">Client: #\${clientId}</p>
                                <p class="mb-1">Statut: <span class="badge bg-warning">Impayée</span></p>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Consommation</th>
                                                <th>Prix unitaire</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Consommation Électricité (0-100 kWh)</td>
                                                <td>100 kWh</td>
                                                <td>0.82 DH</td>
                                                <td>82.00 DH</td>
                                            </tr>
                                            <tr>
                                                <td>Consommation Électricité (101-150 kWh)</td>
                                                <td>50 kWh</td>
                                                <td>0.92 DH</td>
                                                <td>46.00 DH</td>
                                            </tr>
                                            <tr>
                                                <td>Consommation Électricité (151+ kWh)</td>
                                                <td>75 kWh</td>
                                                <td>1.10 DH</td>
                                                <td>82.50 DH</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Sous-total</strong></td>
                                                <td>210.50 DH</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>TVA (18%)</strong></td>
                                                <td>37.89 DH</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                                <td><strong>248.39 DH</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <p class="mb-1"><strong>Informations de paiement:</strong></p>
                                    <p class="mb-1">Date limite de paiement: 15/06/2023</p>
                                    <p class="mb-0">Veuillez effectuer le paiement dans les 15 jours suivant la réception de cette facture.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }, 1000);
        });
    });
    
    // Print invoice functionality
    printInvoiceBtn.addEventListener('click', function() {
        const invoiceId = this.getAttribute('data-id');
        printInvoice(invoiceId);
    });
    
    // Pay invoice functionality
    const payInvoiceButtons = document.querySelectorAll('.pay-invoice');
    const paymentModal = document.getElementById('paymentModal');
    const paymentInvoiceId = document.getElementById('paymentInvoiceId');
    const transferInvoiceId = document.getElementById('transferInvoiceId');
    const cardPaymentForm = document.getElementById('cardPaymentForm');
    const transferPaymentForm = document.getElementById('transferPaymentForm');
    const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
    
    payInvoiceButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const invoiceId = this.getAttribute('data-id');
            
            // Update invoice ID in payment modal
            paymentInvoiceId.textContent = invoiceId;
            transferInvoiceId.textContent = invoiceId;
            
            // Show modal
            const modal = new bootstrap.Modal(paymentModal);
            modal.show();
        });
    });
    
    // Toggle payment method forms
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'card') {
                cardPaymentForm.style.display = 'block';
                transferPaymentForm.style.display = 'none';
            } else {
                cardPaymentForm.style.display = 'none';
                transferPaymentForm.style.display = 'block';
            }
        });
    });
    
    // Confirm payment (simulation)
    const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
    
    confirmPaymentBtn.addEventListener('click', function() {
        // Show processing
        this.disabled = true;
        this.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement...`;
        
        // Simulate payment processing
        setTimeout(() => {
            // Here you would send a request to mark the invoice as paid
            // After success, reload the page to show updated status
            window.location.reload();
        }, 2000);
    });
});

// Print invoice function
function printInvoice(invoiceId) {
    // Here you would fetch the invoice details and open a print window
    
    // For now, we'll open the current modal content in a new window for printing
    const modalContent = document.getElementById('invoiceModalBody').innerHTML;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Facture #\${invoiceId} - Lydec</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-12 text-end no-print">
                            <button class="btn btn-primary" onclick="window.print()">Imprimer</button>
                            <button class="btn btn-secondary ms-2" onclick="window.close()">Fermer</button>
                        </div>
                    </div>
                    \${modalContent}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
