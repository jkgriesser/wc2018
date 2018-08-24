<?php
// load php-login components
require_once('php-login.php');
// the login object will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

if (!$login->isUserLoggedIn() || $_SESSION['user_access_level'] != "255") {
	header('Location: index.php');
	exit();
}

$admin = new Admin();
$match_data = $admin->getMatchData();

include('views/header.php');

foreach ($admin->errors as $error) {
    echo "<div class=\"alert alert-danger\">$error</div><br/>\n";
}

foreach ($admin->messages as $message) {
    echo "<div class=\"alert alert-info\">$message</div><br/>\n";
}
?>

<div class="container-fluid">
    <div class="row">
         <div class="main center-block">
          	<h1 class="page-header">Final Scores</h1>
          	
          	<form role="form" class="betform" method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
    			<div class="table-responsive">
    				<table class="table table-striped">
    					<thead>
    						<tr>
    							<th>#</th>
    							<th>Group</th>
    							<th>Kick-off</th>
    							<th>Venue</th>
    							<th colspan="7" />
    						</tr>
    					</thead>
    					<tbody>
    					    <?php foreach ($match_data as $match) { ?>
    						<tr>
    							<td class="betcell"><?php echo $match['match_id']; ?></td>
    							<td class="betcell"><a href="<?php echo $match['group_wiki']; ?>" target="_blank"><?php echo $match['group_name']; ?></td>
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
                                    <input type="text" name="homescore[<?php echo $match['match_id']; ?>]" size="1" value="<?php echo $match['goals_home']; ?>"
                                            maxlength="2" />                              
                                </td>
                                <td class="betcell centeredcell">-</td>
                                <td class="betcell centeredcell">
                                    <input type="text" name="awayscore[<?php echo $match['match_id']; ?>]" size="1" value="<?php echo $match['goals_away']; ?>"
                                            maxlength="2" />             
                                </td>
                                <td class="betcell awaycell">
    							    <a href="<?php echo $match['away_wiki']; ?>" target="_blank"><?php echo $match['away_team']; ?></a>
                                </td>
    							<td class="betcell centeredcell">
    							    <img src="img/flags/small/<?php echo $match['away_flag']; ?>" />
                                </td>
    						</tr>
    						<?php } ?> 
    					</tbody>
    				</table>
    			</div>
    			<br />
    			<button type="submit" class="btn btn-success btn-lg center-block" name="finalscoresubmit"><?php echo WORDING_SUBMIT_SCORES; ?></button>
			</form>
         </div>
    </div>
</div> <!-- /container-fluid -->


<?php include('views/footer.php'); ?>
