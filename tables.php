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
$overall_table = $bet->getOverallTable();
$overall_stats = $bet->getOverallStats();

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
	            <li class="active"><a href="tables.php">Overall</a></li>
			</ul>
			<ul class="nav nav-sidebar">
	            <li><a href="battles.php">Battle of the...</a></li>
			</ul>
		</div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          	<h1 class="page-header">Overall</h1>

		  	<div class="row placeholders">
	            <div class="col-xs-6 col-sm-3 placeholder">
					<img data-src="holder.js/200x200/auto/green/text:<?php echo $overall_stats->total_players ?>" class="img-responsive" alt="Generic placeholder thumbnail">
					<h4>Total Players</h4>
					<span class="text-muted">(<?php echo $overall_stats->total_bets ?> bets)</span>
	            </div>
	            <div class="col-xs-6 col-sm-3 placeholder">
	            	<img data-src="holder.js/200x200/auto/red/text:<?php echo $overall_stats->pts_remaining ?>" class="img-responsive" alt="Generic placeholder thumbnail">
					<h4>Points to Play for</h4>
					<span class="text-muted">(<?php echo $overall_stats->total_points ?> points earned so far)</span>
	            </div>
	            <div class="col-xs-6 col-sm-3 placeholder">
                    <img data-src="holder.js/200x200/auto/yellow/text:<?php echo $overall_stats->matches_played ?>" class="img-responsive" alt="Generic placeholder thumbnail">
                    <h4>Matches Played</h4>
                    <span class="text-muted">(<?php echo $overall_stats->matches_remaining ?> matches remaining)</span>
	            </div>
	            <div class="col-xs-6 col-sm-3 placeholder">
                    <img data-src="holder.js/200x200/auto/blue/text:<?php echo $overall_stats->avg_pts_match ?>" class="img-responsive" alt="Generic placeholder thumbnail">
                    <h4>Avg. Pts / Match</h4>
                    <span class="text-muted">(<?php echo $overall_stats->avg_pts_player ?> avg. pts / player)</span>
	            </div>
			</div>
            
            <?php if (!empty($overall_table)) { ?>
			<h2 class="sub-header">Leaderboard</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
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
							<th>Played</th>
							<th>Scores</th>
							<th>Goal Diffs</th>
							<th>Tendencies</th>
							<th>Avg Points</th>
							<th>Points</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($overall_table as $row) { ?>
						<tr <?php echo $row['rank'] == 1 ? 'class="leaderboard"' : ''; ?>>
							<td class="betcell"><?php echo $row['rank']; ?></td>
							<td class="betcell">
							    <img class="gravatar" src="<?php echo $login->get_gravatar_for_user($email_address = $row['user_email'], $s = 50); ?>" />
							</td>
							<td class="betcell">							    
							    <a href="results_player.php?user_id=<?php echo $row['user_id']; ?>"><?php echo $row['user_name']; ?></a>
							</td>
							<td class="betcell"><?php echo $row['user_first_name'] . ' ' . $row['user_last_name']; ?></td>
							<?php if (ALLOW_SEX_SELECTION) { ?>
							<td class="betcell">
							    <?php if (isset($row['user_sex'])) { ?>
							    <a href="battles.php"><?php echo $row['user_sex']; ?></a>
							    <?php } ?>
							</td>
							<?php } ?>
							<td class="betcell">
							    <?php if (isset($row['team_name'])) { ?>
							    <a href="battles.php">
							        <img src="img/flags/small/<?php echo $row['team_flag']; ?>" title="<?php echo $row['team_name']; ?>" />
							    </a>
							    <?php } ?>
							</td>
							<td class="betcell">
							    <?php if (isset($row['club_name'])) { ?>
							    <a href="battles.php">
							        <img src="img/badges/small/<?php echo $row['club_badge']; ?>" title="<?php echo $row['club_name']; ?>" />
							    </a>
							    <?php } ?>
							 </td>
							<td class="betcell">
							    <?php if (isset($row['country_name'])) { ?>
							    <a href="battles.php">
							        <img src="img/flags/small/<?php echo $row['country_flag']; ?>" title="<?php echo $row['country_name']; ?>" />
							    </a>
							    <?php } ?>
							</td>
							<?php if (ALLOW_DEPARTMENT_SELECTION) { ?>
							<td class="betcell">
							    <?php if (isset($row['department_name'])) { ?>
							    <a href="battles.php"><?php echo $row['department_name']; ?></a>
							    <?php } ?>
							</td>
							<?php } ?>
							<td class="betcell"><?php echo $row['total_bets']; ?></td>
							<td class="betcell"><?php echo $row['correct_scores']; ?></td>
							<td class="betcell"><?php echo $row['correct_goal_diffs']; ?></td>
							<td class="betcell"><?php echo $row['correct_tendencies']; ?></td>
							<td class="betcell"><?php echo $row['avg_points']; ?></td>
							<td class="betcell"><strong><em><?php echo $row['total_points']; ?></em></strong></td>
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
