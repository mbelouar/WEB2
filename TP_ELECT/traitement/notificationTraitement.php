<?php
// traitement/notificationTraitement.php
session_start();

require_once '../BD/db.php';
require_once '../BD/Notification.php';

$notificationModel = new Notification($pdo);

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

if ($action === 'list') {
    if (!isset($_SESSION['client'])) {
        header("Location: ../IHM/client/connexion.php");
        exit;
    }
    $clientId = $_SESSION['client']['id'];
    $notifications = $notificationModel->getNotificationsByClient($clientId);
    include '../IHM/notification/list.php';

} elseif ($action === 'markAsRead') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['notif_id'])) {
            echo "Notification non spécifiée.";
            exit;
        }
        $notificationId = $_POST['notif_id'];
        $notificationModel->markAsRead($notificationId);
        header("Location: notificationTraitement.php?action=list");
        exit;
    } else {
        echo "Méthode non autorisée.";
    }
} else {
    echo "Action non définie ou non prise en charge.";
}
?>
