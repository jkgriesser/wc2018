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
$match_data = $bet->getMyGroupStageMatchData();
$user_stats = $bet->getGroupStageStatsByUser($_SESSION['user_id']);

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
         <div class="main center-block">
          	<h1 class="page-header">Group Stage</h1>

		  	<div class="row placeholders">
	            <div class="col-xs-6 col-sm-3 placeholder">
					<img data-src="holder.js/200x200/auto/green/text:<?php echo $user_stats->total_points ?>" class="img-responsive" alt="Generic placeholder thumbnail">
					<h4>Total Points</h4>
					<span class="text-muted">(out of <?php echo $user_stats->matches_played * 4 ?> possible)</span>
	            </div>
	            <div class="col-xs-6 col-sm-3 placeholder">
                    <img data-src="holder.js/200x200/auto/red/text:<?php echo $user_stats->correct_scores ?>" class="img-responsive" alt="Generic placeholder thumbnail">
                    <h4>Correct Scores</h4>
                    <span class="text-muted">(4 points each)</span>
	            </div>
	            <div class="col-xs-6 col-sm-3 placeholder">
                    <img data-src="holder.js/200x200/auto/yellow/text:<?php echo $user_stats->correct_goal_differences ?>" class="img-responsive" alt="Generic placeholder thumbnail">
                    <h4>Correct Goal Differences</h4>
                    <span class="text-muted">(3 points each)</span>
	            </div>
	            <div class="col-xs-6 col-sm-3 placeholder">
	            	<img data-src="holder.js/200x200/auto/blue/text:<?php echo $user_stats->correct_tendencies ?>" class="img-responsive" alt="Generic placeholder thumbnail">
					<h4>Correct Tendencies</h4>
					<span class="text-muted">(2 points each)</span>
	            </div>
			</div>

			<h2 class="sub-header">My Bets</h2>
			<form role="form" class="betform" method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
    			<div class="table-responsive">
    				<table class="table table-striped">
    					<thead>
    						<tr>
    							<th>#</th>
    							<th>Group</th>
    							<th>Kick-off</th>
    							<th>Venue</th>
    							<th colspan="9" />
    							<th>Time Remaining</th>
    							<th>Points</th>
    							<th>Status</th>
    						</tr>
    					</thead>
    					<tbody>
    					    <?php foreach ($match_data as $match) { ?>
    						<tr>
    							<td class="betcell">
    							    <?php if ($match['is_open'] == 'N') { ?>
    							    <a href="results.php?match_id=<?php echo $match['match_id']; ?>"><?php echo $match['match_id']; ?></a>
    							    <?php } else echo $match['match_id']; ?>
    							 </td>
    							<td class="betcell"><a href="<?php echo $match['group_wiki']; ?>" target="_blank"><?php echo $match['group_name']; ?></a></td>
    							<td class="betcell">
    							    <!-- Concatenated "Z" required for localtime jQuery plugin -->
    							    <span data-localtime-format><?php echo $match['kickoff_datetime'].'Z'; ?></span>
    							    <br />
    							    <span><?php echo $match['broadcaster_name']; ?></span>
    							 </td>
    							<td class="betcell">
    							    <a href="<?php echo $match['wiki_stadium_link']; ?>" target="_blank"><?php echo $match['stadium_name']; ?></a>
    							    <br />
    							    <a href="<?php echo $match['wiki_city_link']; ?>" target="_blank"><?php echo $match['city_name']; ?></a>
    							</td>
    							<td class="betcell centeredcell">
    							    <img src="img/flags/small/<?php echo $match['home_flag']; ?>" />
                                </td>
    							<td class="betcell">
    							    <a href="<?php echo $match['home_wiki']; ?>" target="_blank"><?php echo $match['home_team']; ?></a>
                                </td>
                                <td class="betcell centeredcell">
                                    <input type="text" name="homebet[<?php echo $match['match_id']; ?>]" size="1" value="<?php echo $match['goals_home_bet']; ?>"
                                            maxlength="2" <?php if (!$match['is_open']) echo "disabled"; ?> />                              
                                </td>
                                <td class="betcell"><span><?php echo $match['goals_home']; ?></span></td>
                                <td class="betcell centeredcell">-</td>
                                <td class="betcell"><span><?php echo $match['goals_away']; ?></span></td>
                                <td class="betcell centeredcell">
                                    <input type="text" name="awaybet[<?php echo $match['match_id']; ?>]" size="1" value="<?php echo $match['goals_away_bet']; ?>"
                                            maxlength="2" <?php if (!$match['is_open']) echo "disabled"; ?> />             
                                </td>
                                <td class="betcell awaycell">
    							    <a href="<?php echo $match['away_wiki']; ?>" target="_blank"><?php echo $match['away_team']; ?></a>
                                </td>
    							<td class="betcell centeredcell">
    							    <img src="img/flags/small/<?php echo $match['away_flag']; ?>" />
                                </td>
                                <!-- Concatenated "Z" required for localtime jQuery plugin -->
                                <td class="betcell" data-countdown="<?php echo $match['kickoff_datetime'].'Z'; ?>" />
                                <td class="betcell"><strong><em><?php echo $match['points']; ?></em></strong></td>
                                <td class="betcell">
                                    <?php
                                    $icon = "";
                                    $statustext = "";                                    
                                    if (isset($bet->status[$match['match_id']])) {
                                        $statustext = $bet->status[$match['match_id']];
                                    	switch ($statustext) {
                                            case "OK":
                                                $icon = "glyphicon-ok-sign";
                                                break;
                                            case "No change":
                                                $icon = "glyphicon-minus-sign";
                                                break;
                                            case "Error":
                                                $icon = "glyphicon-remove-sign";
                                                break;
                                        }
                                    } else if (isset($match['goals_home_bet']) && isset($match['goals_away_bet']) && $match['is_open'] === "Y") {
                                        $statustext = "OK";
                                    	$icon = "glyphicon-ok-sign";
                                    } ?>
                                    <span class="glyphicon <?php echo $icon; ?>" title="<?php echo $statustext; ?>" ></span>
                                </td>
    						</tr>
    						<?php } ?> 
    					</tbody>
    				</table>
    			</div>
    			<br />
    			<button type="submit" class="btn btn-success btn-lg center-block" name="groupstagesubmit"><?php echo WORDING_SUBMIT_SCORES; ?></button>
			</form>
         </div>
    </div>
</div> <!-- /container-fluid -->

<?php include('views/footer.php'); ?>
