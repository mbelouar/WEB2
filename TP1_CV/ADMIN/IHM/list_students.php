<?php
/**
 * UI file for displaying the list of students
 */

// Start session for messages
session_start();

// Include database functions
require_once '../BD/database.php';

// Get all students
$students = getAllStudents();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Liste des Candidats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #626F47, #4a5339);
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            padding: 20px;
        }
        .admin-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .admin-header {
            margin-bottom: 30px;
            text-align: center;
            color: #626F47;
        }
        .table-responsive {
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #626F47;
            border-color: #626F47;
        }
        .btn-primary:hover {
            background-color: #4a5339;
            border-color: #4a5339;
        }
        .btn-warning {
            background-color: #FFCF50;
            border-color: #FFCF50;
            color: white;
        }
        .btn-warning:hover {
            background-color: #e0b646;
            border-color: #e0b646;
            color: white;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="admin-container">
                    <div class="admin-header">
                        <h1>Gestion des Candidats</h1>
                        <p>Interface d'administration pour gérer les candidats et leurs CV</p>
                    </div>
                    
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($students)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Aucun candidat trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td><?php echo $student['id']; ?></td>
                                            <td><?php echo htmlspecialchars($student['lastname']); ?></td>
                                            <td><?php echo htmlspecialchars($student['firstname']); ?></td>
                                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                                            <td class="action-buttons">
                                                <a href="../../edit_CV.php?id=<?php echo $student['id']; ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <a href="../Traitement/delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat?');">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="../Traitement/clear_session.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un Candidat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 