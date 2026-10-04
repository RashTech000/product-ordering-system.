<?php
if (isset($_SESSION['message'])) {
    echo "<div class='message'>" . htmlspecialchars($_SESSION['message']) . "</div>";
    unset($_SESSION['message']);
}
?>
