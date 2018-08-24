<?php
// load php-login components
require_once('php-login.php');
// the login object will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

if (!$login->isUserLoggedIn()) {
	header('Location: index.php');
	exit();
}

include('views/header.php');
?>
<div class="container-fluid">

</div> <!-- /container-fluid -->


<?php include('views/footer.php'); ?>
