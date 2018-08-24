<?php
// load php-login components
require_once('php-login.php');
// the login object will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

include('views/header.php');
?>

<div class="container">
  <!-- Example row of columns -->
	<div class="row">
		<div class="col-md-4">
			<h2><?php echo WORDING_EDIT_USER_DATA; ?></h2>
			<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
			<p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Heading</h2>
			<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
			<p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Heading</h2>
			<p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
			<p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
		</div>
	</div>
</div> <!-- /container -->

<?php include('views/footer.php'); ?>
