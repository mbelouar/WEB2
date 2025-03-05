<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database functions
require_once 'getInfo.php';

// Function to get all CVs
function getAllCVs() {
    $conn = connectDB();
    $cvs = [];
    
    try {
        $stmt = $conn->prepare("SELECT id, firstname, lastname, email, created_at FROM users ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $cvs[] = $row;
        }
        
        $stmt->close();
    } catch (Exception $e) {
        error_log("Error retrieving CVs: " . $e->getMessage());
    } finally {
        $conn->close();
    }
    
    return $cvs;
}

// Get all CVs
$cvs = getAllCVs();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des CV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .cv-list {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            margin-top: 50px;
        }
        .cv-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        .cv-item:hover {
            background-color: #f5f5f5;
        }
        .action-buttons a {
            margin-left: 10px;
        }
        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1>Liste des CV enregistrés</h1>
                <div class="cv-list">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                    <?php endif; ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <a href="formulaire.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer un nouveau CV
                            </a>
                        </div>
                    </div>
                    
                    <?php if (empty($cvs)): ?>
                        <div class="alert alert-info">
                            Aucun CV n'a été enregistré pour le moment.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Date de création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cvs as $cv): ?>
                                        <tr class="cv-item">
                                            <td><?php echo htmlspecialchars($cv['lastname']); ?></td>
                                            <td><?php echo htmlspecialchars($cv['firstname']); ?></td>
                                            <td><?php echo htmlspecialchars($cv['email']); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($cv['created_at'])); ?></td>
                                            <td class="action-buttons">
                                                <a href="view_cv.php?id=<?php echo $cv['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                                <a href="edit_cv.php?id=<?php echo $cv['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <a href="delete_cv.php?id=<?php echo $cv['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce CV?');">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </a>
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
    </div>
    
    <script src="particles.js"></script>
    <script>
        particlesJS.load('particles-js', 'assets/particles.json', function() {
            console.log('particles.js loaded');
        });
    </script>
</body>
</html> 