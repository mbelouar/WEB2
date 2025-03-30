<?php
session_start();
include '../partials/header.php';
?>

<h2>Mes Notifications</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Message</th>
        <th>Date</th>
        <th>État</th>
        <th>Actions</th>
    </tr>
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $notif): ?>
        <tr>
            <td><?php echo $notif['id']; ?></td>
            <td><?php echo $notif['message']; ?></td>
            <td><?php echo $notif['date_notification']; ?></td>
            <td><?php echo $notif['is_read'] ? 'Lu' : 'Non lu'; ?></td>
            <td>
                <?php if (!$notif['is_read']): ?>
                <form action="../../traitement/notificationTraitement.php?action=markAsRead" method="POST">
                    <input type="hidden" name="notif_id" value="<?php echo $notif['id']; ?>">
                    <button type="submit">Marquer comme lu</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">Aucune notification trouvée</td></tr>
    <?php endif; ?>
</table>

<?php include '../partials/footer.php'; ?>
