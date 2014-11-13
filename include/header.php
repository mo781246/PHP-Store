<?php
require_once "include/Session.php";
$session = new Session();
?>

<img src="images/header.png" />

<?php if (isset($session->user)): ?>
<div class="welcome">Welcome, <?php echo $session->user->name?></div>
<?php endif?>
