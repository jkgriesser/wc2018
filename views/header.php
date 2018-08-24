<!DOCTYPE html>
 
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="google" content="notranslate">
    <meta http-equiv="Content-Language" content="en_UK">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="<?php echo AUTHOR ?>">
	<link rel="shortcut icon" href="img/layout/favicon.png">
	
	<title><?php echo NAV_TITLE ?></title>
	
	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- Bootstrap Select -->
	<link href="css/bootstrap-select.min.css" rel="stylesheet">	
	<!-- Custom style -->
	<link href="css/wc2018.css" rel="stylesheet">
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<?php 
	function echoActiveClassIfRequestMatches($requestUri)
	{
	    $url = strtok($_SERVER['REQUEST_URI'], '?');
	    $current_file_name = basename($url, ".php");
	    
	    if ($current_file_name == "" || $current_file_name == "wc2018") {
		    $current_file_name = "index";
	    } else if (strpos($current_file_name, 'bets') !== false) {
		    $current_file_name = "bets";
	    }
	    
	    if ($current_file_name == $requestUri)
	        echo " active";
	}	
	?>
</head>
  
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
			<a class="navbar-brand logo" href="index.php"><img src="img/content/trophy.png"/></a>
			<a class="navbar-brand" href="index.php"><?php echo NAV_TITLE ?></a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="<?php echoActiveClassIfRequestMatches("index") ?>"><a href="index.php"><?php echo NAV_HOME ?></a></li>
				<?php if ($login->isUserLoggedIn()): ?>
				<li class="dropdown <?php echoActiveClassIfRequestMatches("bets") ?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo NAV_BETS ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="bets-group.php"><?php echo NAV_BETS_GROUPSTAGE ?></a></li>
						<li><a href="bets-knockout.php"><?php echo NAV_BETS_KNOCKOUT ?></a></li>
						<li><a href="bets-bonus.php"><?php echo NAV_BETS_BONUSCOMP ?></a></li>
					</ul>
				</li>
				<li class="<?php echoActiveClassIfRequestMatches("tables") || echoActiveClassIfRequestMatches("battles") ?>">
				    <a href="tables.php"><?php echo NAV_TABLES ?></a>
				</li>
				<li class="<?php echoActiveClassIfRequestMatches("results") || echoActiveClassIfRequestMatches("results_player") ?>">
				    <a href="results.php"><?php echo NAV_RESULTS ?></a>
				</li>
				<?php if ($_SESSION['user_access_level'] == "255"): ?>
				<li class="<?php echoActiveClassIfRequestMatches("admin") ?>"><a href="admin.php"><?php echo NAV_ADMIN ?></a></li>
				<?php endif; ?>
				<li class="<?php echoActiveClassIfRequestMatches("forum") ?>"><a href="forum.php"><?php echo NAV_FORUM ?></a></li>
				<?php endif; ?>
			</ul>
			<?php if ($login->isUserLoggedIn()): ?>
			<ul class="nav navbar-nav navbar-right hidden-sm"> 				
					<li class="<?php echoActiveClassIfRequestMatches("edit") ?>"><a href="edit.php"><?php echo $_SESSION['user_name'] ?></a></li>
					<li class="hidden-xs <?php echoActiveClassIfRequestMatches("edit") ?>">
						<div class="navbar-collapse navbar-right collapse">
							<a href="edit.php"><img class="gravatar" src="<?php echo $login->get_gravatar($s = 50); ?>" /></a>
						</div>
					</li>													
			</ul>
			<form class="navbar-form navbar-right" role="form" method="get" action="index.php">
				<div class="form-group">
					<input type="text" hidden="true" name="logout" value="true">
                </div>
                <button type="submit" class="btn btn-danger"><?php echo WORDING_LOGOUT; ?></button>    				
			</form>
			<?php endif; ?>
		</div> <!--/.navbar-collapse -->
	</div>
</div>
<?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    foreach ($login->errors as $error) {
        echo "<div class=\"alert alert-danger\">$error</div><br/>\n";
    }

    foreach ($login->messages as $message) {
        echo "<div class=\"alert alert-info\">$message</div><br/>\n";
    }
}
?>
