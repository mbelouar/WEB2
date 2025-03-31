<?php
session_start();
require_once '../../BD/db.php';
require_once '../../BD/Facture.php';
require_once '../../BD/Consumption.php';

// Set page variables
$pageTitle = 'Tableau de Bord';
$activePage = 'dashboard';

// Check if the user is logged in
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

// Get client info
$clientId = $_SESSION['client']['id'];
$client = $_SESSION['client'];

// Initialize models
$factureModel = new Facture($pdo);
$consumptionModel = new Consumption($pdo);

// Get latest invoice
$lastInvoice = $factureModel->getLastInvoice($clientId);

// Get latest consumption reading
$lastConsumption = $consumptionModel->getLastConsumption($clientId);

// Count total invoices
$allInvoices = $factureModel->getFacturesByClient($clientId);
$invoiceCount = count($allInvoices);

// Count unpaid invoices
$unpaidCount = 0;
$totalDue = 0;
foreach ($allInvoices as $invoice) {
    if ($invoice['statut'] === 'impayée') {
        $unpaidCount++;
        $totalDue += $invoice['montant'];
    }
}

// Start page content
ob_start();
?>

<div class="container my-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0" data-aos="fade-right">
            <i class="fas fa-tachometer-alt me-2 text-primary"></i>
            Tableau de bord
        </h1>
        <div data-aos="fade-left">
            <a href="client_new_consumption.php" class="btn btn-accent">
                <i class="fas fa-bolt me-2"></i> Saisir une consommation
            </a>
        </div>
    </div>

    <!-- Welcome Banner -->
    <div class="card mb-4" data-aos="fade-up">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4>Bienvenue, <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?> !</h4>
                    <p class="mb-0">
                        Voici votre espace personnel pour gérer vos consommations, factures et réclamations.
                        <?php if ($unpaidCount > 0): ?>
                            <span class="text-danger">Vous avez <?php echo $unpaidCount; ?> facture(s) impayée(s).</span>
                        <?php else: ?>
                            <span class="text-success">Toutes vos factures sont à jour. Merci !</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <img src="../../uploads/Lydec.png" alt="Welcome" class="img-fluid" style="max-height: 100px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Consumption Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo $lastConsumption ? $lastConsumption['current_reading'] : '0'; ?>
                    <small>kWh</small>
                </div>
                <div class="stat-label">Dernière Consommation</div>
                <i class="fas fa-bolt stat-icon"></i>
            </div>
        </div>
        
        <!-- Last Invoice Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo $lastInvoice ? number_format($lastInvoice['montant'], 2) : '0.00'; ?>
                    <small>DH</small>
                </div>
                <div class="stat-label">Dernière Facture</div>
                <i class="fas fa-file-invoice-dollar stat-icon"></i>
            </div>
        </div>
        
        <!-- Total Due Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo number_format($totalDue, 2); ?>
                    <small>DH</small>
                </div>
                <div class="stat-label">Montant Total Dû</div>
                <i class="fas fa-money-bill-wave stat-icon"></i>
            </div>
        </div>
        
        <!-- Invoice Count Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo $invoiceCount; ?>
                </div>
                <div class="stat-label">Factures Totales</div>
                <i class="fas fa-file-alt stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Latest Invoice -->
        <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> Dernière Facture</h5>
                    <a href="client_invoices.php" class="btn btn-sm btn-primary">Voir Toutes</a>
                </div>
                <div class="card-body">
                    <?php if ($lastInvoice): ?>
                        <div class="d-flex justify-content-between mb-3">
                            <div>Facture #<?php echo $lastInvoice['id']; ?></div>
                            <div class="badge <?php echo $lastInvoice['statut'] === 'payée' ? 'bg-success' : 'bg-warning'; ?>">
                                <?php echo ucfirst($lastInvoice['statut']); ?>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div>Date d'émission:</div>
                            <div><?php echo date('d/m/Y', strtotime($lastInvoice['date_emission'])); ?></div>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div>Montant:</div>
                            <div class="fw-bold"><?php echo number_format($lastInvoice['montant'], 2); ?> DH</div>
                        </div>
                        <?php if ($lastInvoice['statut'] === 'impayée'): ?>
                            <div class="text-center mt-4">
                                <a href="#" class="btn btn-accent">Payer Maintenant</a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-file-invoice text-muted mb-3" style="font-size: 3rem;"></i>
                            <p>Aucune facture disponible pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Latest Consumption -->
        <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Dernière Consommation</h5>
                    <a href="client_new_consumption.php" class="btn btn-sm btn-primary">Saisir Nouvelle</a>
                </div>
                <div class="card-body">
                    <?php if ($lastConsumption): ?>
                        <div class="d-flex justify-content-between mb-3">
                            <div>Mois:</div>
                            <div><?php echo htmlspecialchars($lastConsumption['month']); ?></div>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div>Date de relevé:</div>
                            <div><?php echo date('d/m/Y H:i', strtotime($lastConsumption['dateReleve'])); ?></div>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div>Lecture du compteur:</div>
                            <div class="fw-bold"><?php echo $lastConsumption['current_reading']; ?> kWh</div>
                        </div>
                        <?php if (!empty($lastConsumption['photo'])): ?>
                            <div class="text-center mt-3">
                                <img src="../../<?php echo htmlspecialchars($lastConsumption['photo']); ?>" 
                                    alt="Photo du compteur" class="img-fluid img-thumbnail" style="max-height: 200px;">
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-bolt text-muted mb-3" style="font-size: 3rem;"></i>
                            <p>Aucune consommation enregistrée pour le moment.</p>
                            <a href="client_new_consumption.php" class="btn btn-primary mt-2">Saisir une consommation</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

// Include the template
require_once '../templates/client_template.php';
?>
