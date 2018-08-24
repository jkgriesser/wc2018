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

$bet = new Bet();
$matches_played = $bet->getMatchesPlayed();

if (isset($_GET["match_id"])) {
	$results_data = $bet->getUserDataByMatch($_GET["match_id"]);
}

include('views/header.php');

foreach ($bet->errors as $error) {
    echo "<div class=\"alert alert-danger\">$error</div><br/>\n";
}

foreach ($bet->messages as $message) {
    echo "<div class=\"alert alert-info\">$message</div><br/>\n";
}
?>
<div class="container-fluid">
	<div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
	            <li class="active"><a href="results.php">By Match</a></li>
	            <li><a href="results_player.php">By Player</a></li>
			</ul>
		</div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          	<h1 class="page-header">Results by Match</h1>
          	<form role="form" class="form-horizontal" method="get" action="?results">
        			<label for="match_id">Match selection</label>
        			<select class="form-control selectpicker" id="match_id" name="match_id" data-live-search="true">
        				<option value="">Pick a match...</option>
        				    <?php foreach ($matches_played as $row) { ?>
        					<option value="<?php echo($row['match_id']); ?>"<?php echo (isset($_GET["match_id"]) && $_GET['match_id'] == $row['match_id'] ? ' selected' : ''); ?>>
        					    Match <?php echo($row['match_id']); ?>: <?php echo($row['home_team']); ?> vs <?php echo($row['away_team']); ?> (<?php echo($row['goals_home']); ?>-<?php echo($row['goals_away']); ?>)
                            </option>
                            <?php } ?>
        			</select><br /><br />
        			<button type="submit" class="btn btn-success">Show match data</button>
          	</form>
          	
          	<hr/>
            
            <?php if (isset($results_data) && !empty($results_data)) { ?>
			<h2 class="sub-header">
			    <?php foreach ($matches_played as $row) { ?>
			        <?php if ($row['match_id'] == $_GET["match_id"]) { ?>			    
			    <img src="img/flags/small/<?php echo $row['home_flag']; ?>" />
			    <a href="<?php echo $row['home_wiki']; ?>" target="_blank"><?php echo $row['home_team']; ?></a>
			    <?php echo $row['goals_home']; ?>-<?php echo $row['goals_away']; ?>
			    <a href="<?php echo $row['away_wiki']; ?>" target="_blank"><?php echo $row['away_team']; ?></a>
			    <img src="img/flags/small/<?php echo $row['away_flag']; ?>" />
			        <?php }
			    } ?>
			</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th></th>
							<th>Username</th>
							<th>Name</th>
							<?php if (ALLOW_SEX_SELECTION) { ?>
							<th>Sex</th>
							<?php } ?>
							<th>Team</th>
							<th>Club</th>
							<th>Country</th>
							<?php if (ALLOW_DEPARTMENT_SELECTION) { ?>
							<th>Department</th>
							<?php } ?>
							<th>Bet</th>
							<th>Points</th>
						</tr>
					</thead>
					<tbody>
					    <?php foreach ($results_data as $row) { ?>
						<tr>
							<td class="betcell"><img class="gravatar" src="<?php echo $login->get_gravatar_for_user($email_address = $row['user_email'], $s = 50); ?>" /></td>
							<td class="betcell"><a href="results_player.php?user_id=<?php echo $row['user_id']; ?>"><?php echo $row['user_name']; ?></a></td>
							<td class="betcell"><?php echo $row['user_first_name'] . ' ' . $row['user_last_name']; ?></td>
							<?php if (ALLOW_SEX_SELECTION) { ?>
							<td class="betcell"><?php if (isset($row['user_sex'])) echo $row['user_sex']; ?></td>
							<?php } ?>
							<td class="betcell">
							    <?php if (isset($row['team_name'])) { ?>
							    <img src="img/flags/small/<?php echo $row['team_flag']; ?>" title="<?php echo $row['team_name']; ?>" />
							    <?php } ?>
							</td>
							<td class="betcell">
							    <?php if (isset($row['club_name'])) { ?>
							    <img src="img/badges/small/<?php echo $row['club_badge']; ?>" title="<?php echo $row['club_name']; ?>" />
							    <?php } ?>
							 </td>
							<td class="betcell">
							    <?php if (isset($row['country_name'])) { ?>
							    <img src="img/flags/small/<?php echo $row['country_flag']; ?>" title="<?php echo $row['country_name']; ?>" />
							    <?php } ?>
							</td>
							<?php if (ALLOW_DEPARTMENT_SELECTION) { ?>
							<td class="betcell"><?php if (isset($row['department_name'])) echo $row['department_name']; ?></td>
							<?php } ?>
							<td class="betcell"><?php echo $row['goals_home']; ?>-<?php echo $row['goals_away']; ?></td>
							<td class="betcell"><strong><em><?php echo $row['points']; ?></em></strong></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } ?>
		</div>		
	</div>
</div> <!-- /container-fluid -->

<?php include('views/footer.php'); ?>
