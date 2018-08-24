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
$battle_sexes = $bet->getBattleofSexes();
$battle_teams = $bet->getBattleofTeams();
$battle_clubs = $bet->getBattleofClubs();
$battle_countries = $bet->getBattleofCountries();
$battle_depts = $bet->getBattleofDepts();

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
	            <li><a href="tables.php">Overall</a></li>
			</ul>
			<ul class="nav nav-sidebar">
	            <li class="active"><a href="#">Battle of the...</a></li>
			</ul>
		</div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          	<h1 class="page-header">Battle of the...</h1>

		  	<!--
		  	<div class="row placeholders">
	            <div class="col-xs-6 col-sm-3 placeholder">
					<img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
					<h4>Number of Players</h4>
					<span class="text-muted">(Avg. Points)</span>
	            </div>
	            <div class="col-xs-6 col-sm-3 placeholder">
	            	<img data-src="holder.js/200x200/auto/vine" class="img-responsive" alt="Generic placeholder thumbnail">
					<h4>Goals Scored</h4>
					<span class="text-muted">(Excluding Penalty Shoot-outs)</span>
	            </div>
	            <div class="col-xs-6 col-sm-3 placeholder">
	              <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
	              <h4>Yellow / Red Cards</h4>
	              <span class="text-muted">(... Straight Red)</span>
	            </div>
	            <div class="col-xs-6 col-sm-3 placeholder">
	              <img data-src="holder.js/200x200/auto/vine" class="img-responsive" alt="Generic placeholder thumbnail">
	              <h4>Penalties Conceeded</h4>
	              <span class="text-muted">(... by England)</span>
	            </div>
			</div>
			-->
            
            <?php if (!empty($battle_sexes)) { ?>
			<h2 class="sub-header">Sexes</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Sex</th>
							<th>Points</th>
							<th>Players</th>
							<th>Avg Points</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($battle_sexes as $row) { ?>
						<tr <?php echo $row['rank'] == 1 ? 'class="leaderboard"' : ''; ?>>
							<td class="betcell"><?php echo $row['rank']; ?></td>
							<td class="betcell"><?php echo $row['user_sex']; ?></td>
							<td class="betcell"><?php echo $row['points']; ?></td>
							<td class="betcell"><?php echo $row['userno']; ?></td>
							<td class="betcell"><strong><em><?php echo $row['avg_points']; ?></em></strong></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } ?>
			
			<hr/>
			
			<?php if (!empty($battle_teams)) { ?>
			<h2 class="sub-header">Teams</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th></th>
							<th>Name</th>
							<th>Points</th>
							<th>Players</th>
							<th>Avg Points</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($battle_teams as $row) { ?>
						<tr <?php echo $row['rank'] == 1 ? 'class="leaderboard"' : ''; ?>>
							<td class="betcell"><?php echo $row['rank']; ?></td>
							<td class="betcell centeredcell">
							    <?php if ($row['team_name'] != '???') { ?>
                                <img src="img/flags/small/<?php echo $row['flag_filename']; ?>" />
                                <?php } else echo '-'; ?>
                            </td>
							<td class="betcell">
							    <?php if ($row['team_name'] != '???') { ?>
							    <a href="<?php echo $row['wiki_link']; ?>" target="_blank"><?php echo $row['team_name']; ?></a>
							    <?php } else echo $row['team_name']; ?>
							</td>
							<td class="betcell"><?php echo $row['points']; ?></td>
							<td class="betcell"><?php echo $row['userno']; ?></td>
							<td class="betcell"><strong><em><?php echo $row['avg_points']; ?></em></strong></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } ?>
			
			<hr/>
			
			<?php if (!empty($battle_clubs)) { ?>
			<h2 class="sub-header">Clubs</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th></th>
							<th>Name</th>
							<th>League</th>
							<th>Points</th>
							<th>Players</th>
							<th>Avg Points</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($battle_clubs as $row) { ?>
						<tr <?php echo $row['rank'] == 1 ? 'class="leaderboard"' : ''; ?>>
							<td class="betcell"><?php echo $row['rank']; ?></td>
							<td class="betcell centeredcell">
							    <?php if ($row['club_name'] != '???') { ?>
                                <img src="img/badges/small/<?php echo $row['badge_filename']; ?>" />
                                <?php } else echo '-'; ?>
                            </td>
							<td class="betcell"><?php echo $row['club_name']; ?></td>
							<td class="betcell"><?php echo $row['club_name'] != '???' ? $row['league_name'] : '-'; ?></td>
							<td class="betcell"><?php echo $row['points']; ?></td>
							<td class="betcell"><?php echo $row['userno']; ?></td>
							<td class="betcell"><strong><em><?php echo $row['avg_points']; ?></em></strong></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } ?>
			
			<hr/>
			
			<?php if (!empty($battle_countries)) { ?>
			<h2 class="sub-header">Countries</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th></th>
							<th>Name</th>
							<th>Points</th>
							<th>Players</th>
							<th>Avg Points</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($battle_countries as $row) { ?>
						<tr <?php echo $row['rank'] == 1 ? 'class="leaderboard"' : ''; ?>>
							<td class="betcell"><?php echo $row['rank']; ?></td>
							<td class="betcell centeredcell">
							    <?php if ($row['country_name'] != '???') { ?>
                                <img src="img/flags/small/<?php echo $row['flag_filename']; ?>" />
                                <?php } else echo '-'; ?>
                            </td>
							<td class="betcell"><?php echo $row['country_name'] != '???' ? $row['country_name'] : '???'; ?></td>
							<td class="betcell"><?php echo $row['points']; ?></td>
							<td class="betcell"><?php echo $row['userno']; ?></td>
							<td class="betcell"><strong><em><?php echo $row['avg_points']; ?></em></strong></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } ?>
			
			<?php if (ALLOW_DEPARTMENT_SELECTION) { ?>
			<hr/>
			
			<?php if (!empty($battle_depts)) { ?>
			<h2 class="sub-header">Departments</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Business</th>
							<th>Points</th>
							<th>Players</th>
							<th>Avg Points</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($battle_depts as $row) { ?>
						<tr <?php echo $row['rank'] == 1 ? 'class="leaderboard"' : ''; ?>>
							<td class="betcell"><?php echo $row['rank']; ?></td>
							<td class="betcell"><?php echo $row['department_name']; ?></td>
							<td class="betcell"><?php echo $row['department_name'] != '???' ? $row['business_name'] : '-'; ?></td>
							<td class="betcell"><?php echo $row['points']; ?></td>
							<td class="betcell"><?php echo $row['userno']; ?></td>
							<td class="betcell"><strong><em><?php echo $row['avg_points']; ?></em></strong></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</div> <!-- /container-fluid -->

<?php include('views/footer.php'); ?>
