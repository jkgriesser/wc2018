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
$all_players = $bet->getAllPlayers();

if (isset($_GET["user_id"])) {
	$results_data = $bet->getMatchDataByUser($_GET["user_id"]);
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
	            <li><a href="results.php">By Match</a></li>
	            <li class="active"><a href="results_player.php">By Player</a></li>
			</ul>
		</div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          	<h1 class="page-header">Results by Player</h1>
          	<form role="form" class="form-horizontal" method="get" action="?results_player">
        			<label for="match_id">Player selection</label>
        			<select class="form-control selectpicker" id="user_id" name="user_id" data-live-search="true">
        				<option value="">Pick a player...</option>
        				    <?php foreach ($all_players as $row) { ?>
        					<option value="<?php echo($row['user_id']); ?>"<?php echo (isset($_GET["user_id"]) && $_GET['user_id'] == $row['user_id'] ? ' selected' : ''); ?>>
        					    <?php echo($row['user_name']); ?> - <?php echo($row['user_first_name']); ?> <?php echo($row['user_last_name']); ?>
                            </option>
                            <?php } ?>
        			</select><br /><br />
        			<button type="submit" class="btn btn-success">Show player data</button>
          	</form>
          	
          	<hr/>         	
            
            <?php if (isset($results_data) && !empty($results_data)) { ?>
			    <?php
              	foreach ($all_players as $row) {
                    if ($row['user_id'] == $_GET["user_id"]) {
                ?>
            <h2 class="sub-header">
                <img class="gravatar" src="<?php echo $login->get_gravatar_for_user($email_address = $row['user_email'], $s = 50); ?>" />
                        <?php 
                        echo $row['user_name'] . ' - ' . $row['user_first_name'] . ' ' . $row['user_last_name'] . ' (' . $row['total_points'] . ' pts)';
                        if (isset($row['team_name'])) {
                        ?>
                <img src="img/flags/small/<?php echo $row['team_flag']; ?>" title="<?php echo $row['team_name']; ?>" />
                        <?php } ?>
                        <?php if (isset($row['club_name'])) { ?>
                <img src="img/badges/small/<?php echo $row['club_badge']; ?>" title="<?php echo $row['club_name']; ?>" />
						<?php } ?>
                        <?php if (isset($row['country_name'])) { ?>
                <img src="img/flags/small/<?php echo $row['country_flag']; ?>" title="<?php echo $row['country_name']; ?>" />
						<?php } ?>
                  	<?php } ?>
            </h2>
              	<?php } ?>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Group</th>
							<th>Venue</th>
							<th colspan="5" />
							<th>Bet</th>
							<th>Points</th>
						</tr>
					</thead>
					<tbody>
					    <?php foreach ($results_data as $row) { ?>
						<tr>
							<td class="betcell"><a href="results.php?match_id=<?php echo $row['match_id']; ?>"><?php echo $row['match_id']; ?></a></td>
							<td class="betcell"><a href="<?php echo $row['group_wiki']; ?>" target="_blank"><?php echo $row['group_name']; ?></a></td>
							<td class="betcell">
							    <a href="<?php echo $row['wiki_stadium_link']; ?>" target="_blank"><?php echo $row['stadium_name']; ?></a>
							    <br />
							    <a href="<?php echo $row['wiki_city_link']; ?>" target="_blank"><?php echo $row['city_name']; ?></a>
							</td>
							<td class="betcell centeredcell">
							    <img src="img/flags/small/<?php echo $row['home_flag']; ?>" />
							</td>
							<td class="betcell">
							    <a href="<?php echo $row['home_wiki']; ?>" target="_blank"><?php echo $row['home_team']; ?></a>
							</td>
							<td class="betcell centeredcell"><?php echo $row['goals_home'] . '-' . $row['goals_away']; ?></td>
							<td class="betcell awaycell"> 
							    <a href="<?php echo $row['away_wiki']; ?>" target="_blank"><?php echo $row['away_team']; ?></a>
							</td>
							<td class="betcell centeredcell">
							    <img src="img/flags/small/<?php echo $row['away_flag']; ?>" />
							</td>
							<td class="betcell"><?php echo $row['goals_home_bet'] . '-' . $row['goals_away_bet']; ?></td>
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
