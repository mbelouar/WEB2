<?php
// BD/Notification.php

class Notification {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getNotificationsByClient($clientId) {
        $sql = "SELECT * FROM Notification WHERE client_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function markAsRead($id) {
        $sql = "UPDATE Notification SET is_read = 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
