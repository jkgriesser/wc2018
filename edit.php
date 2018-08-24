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

$db_connection = $login->getDatabaseConnection();
$teams = $db_connection->query('SELECT team_id, team_name FROM teams WHERE team_active = 1 ORDER BY team_name');
$clubs = $db_connection->query('SELECT club_id, club_name FROM clubs ORDER BY club_name');
$countries = $db_connection->query('SELECT country_id, country_name FROM countries ORDER BY country_name');
$departments = $db_connection->query('SELECT d.department_id, b.business_name, d.department_name FROM departments d, businesses b
    WHERE d.business_id = b.business_id ORDER BY b.business_name, d.department_name');

include('views/header.php');
?>

<div class="container loginbox">
	<form role="form" class="form-horizontal" method="post" action="?edit">
		<div class="form-group">
			<legend><?php echo WORDING_NEW_USERNAME . " (" . WORDING_CURRENTLY . ": " . $_SESSION['user_name'] .")"; ?></legend>
		</div>
		<div class="form-group">
			<input type="text" pattern="[a-zA-Z0-9]{2,64}" class="form-control" id="user_name" name="user_name" placeholder="<?php echo WORDING_REGISTRATION_USERNAME; ?>" value="<?php echo (isset($_POST['user_name']) ? $_POST['user_name'] : ''); ?>" required />
		</div>
		<div class="form-group">
			<button type="submit" name="user_edit_submit_name" class="btn btn-success"><?php echo WORDING_CHANGE_USERNAME; ?></button>
		</div>				
	</form>
	<hr/>
	<form role="form" class="form-horizontal" method="post" action="?edit">
		<div class="form-group">
			<legend><?php echo WORDING_NEW_FIRSTNAME . " (" . WORDING_CURRENTLY . ": " . $_SESSION['user_first_name'] .")"; ?></legend>
		</div>
		<div class="form-group">
			<input type="text" class="form-control" id="user_first_name" name="user_first_name" placeholder="<?php echo WORDING_REGISTRATION_FIRSTNAME; ?>" value="<?php echo (isset($_POST['user_first_name']) ? $_POST['user_first_name'] : ''); ?>" required />
		</div>
		<div class="form-group">
			<button type="submit" name="user_edit_submit_firstname" class="btn btn-success"><?php echo WORDING_CHANGE_FIRSTNAME; ?></button>
		</div>				
	</form>
	<hr/>
	<form role="form" class="form-horizontal" method="post" action="?edit">
		<div class="form-group">
			<legend><?php echo WORDING_NEW_LASTNAME . " (" . WORDING_CURRENTLY . ": " . $_SESSION['user_last_name'] .")"; ?></legend>
		</div>
		<div class="form-group">
			<input type="text" class="form-control" id="user_last_name" name="user_last_name" placeholder="<?php echo WORDING_REGISTRATION_LASTNAME; ?>" value="<?php echo (isset($_POST['user_last_name']) ? $_POST['user_last_name'] : ''); ?>" required />
		</div>
		<div class="form-group">
			<button type="submit" name="user_edit_submit_lastname" class="btn btn-success"><?php echo WORDING_CHANGE_LASTNAME; ?></button>
		</div>				
	</form>
	<hr/>
	<form role="form" class="form-horizontal" method="post" action="?edit">
		<div class="form-group">
			<legend><?php echo WORDING_NEW_EMAIL . " (" . WORDING_CURRENTLY . ": " . $_SESSION['user_email'] .")"; ?></legend>
		</div>
		<div class="form-group">
			<input type="email" class="form-control" id="user_email" name="user_email" placeholder="<?php echo WORDING_REGISTRATION_EMAIL; ?>" required />
		</div>
		<div class="form-group">
			<button type="submit" name="user_edit_submit_email" class="btn btn-success"><?php echo WORDING_CHANGE_EMAIL; ?></button>
		</div>				
	</form>
	<hr/>
	<form role="form" class="form-horizontal" method="post" action="?edit">
		<div class="form-group">
			<legend><?php echo WORDING_NEW_PASSWORD; ?></legend>
		</div>
		<div class="form-group">
			<input type="password" class="form-control" id="user_password_old" name="user_password_old" pattern=".{6,}" autocomplete="off" placeholder="<?php echo WORDING_OLD_PASSWORD; ?>" required />
		</div>
		<div class="form-group">
			<input type="password" class="form-control" id="user_password_new" name="user_password_new" pattern=".{6,}" autocomplete="off" placeholder="<?php echo WORDING_NEW_PASSWORD; ?>" required />
		</div>
		<div class="form-group">
			<input type="password" class="form-control" id="user_password_repeat" name="user_password_repeat" pattern=".{6,}" autocomplete="off" placeholder="<?php echo WORDING_REGISTRATION_PASSWORD_REPEAT_PLACEHOLDER; ?>" required />
		</div>
		<div class="form-group">
			<button type="submit" name="user_edit_submit_password" class="btn btn-success"><?php echo WORDING_CHANGE_PASSWORD; ?></button>
		</div>		
	</form>
	<hr/>
	<form role="form" class="form-horizontal" method="post" action="?edit">
		<div class="form-group">
			<legend><?php echo WORDING_OPTIONAL; ?></legend>
			<a href="http://www.gravatar.com" target="_blank">
				<img src="img/content/Logo_Gravatar.png" />
				<br/>
				<?php echo WORDING_REGISTRATION_GRAVATAR; ?>
			</a>
		</div>
		<?php if (ALLOW_SEX_SELECTION) { ?>
		<div class="form-group" data-toggle="buttons">
			<label for="user_sex"><?php echo WORDING_REGISTRATION_SEX; ?></label>
			<br/>
			<label class="btn btn-primary<?php echo (isset($_SESSION["user_sex"]) && $_SESSION['user_sex'] == 1 ? ' active' : ''); ?>">
				<input type="radio" name="user_sex" id="option1" value="1"<?php echo (isset($_SESSION["user_sex"]) && $_SESSION['user_sex'] == 1 ? ' checked' : ''); ?>> <?php echo WORDING_REGISTRATION_FEMALE; ?>
			</label>
			<label class="btn btn-primary<?php echo (isset($_SESSION["user_sex"]) && $_SESSION['user_sex'] == 2 ? ' active' : ''); ?>">
				<input type="radio" name="user_sex" id="option2" value="2"<?php echo (isset($_SESSION["user_sex"]) && $_SESSION['user_sex'] == 2 ? ' checked' : ''); ?>> <?php echo WORDING_REGISTRATION_MALE; ?>
			</label>
			<label class="btn btn-primary<?php echo (isset($_SESSION["user_sex"]) && $_SESSION['user_sex'] == 3 ? ' active' : ''); ?>">
				<input type="radio" name="user_sex" id="option3" value="3"<?php echo (isset($_SESSION["user_sex"]) && $_SESSION['user_sex'] == 3 ? ' checked' : ''); ?>> <?php echo WORDING_REGISTRATION_TRANS; ?>
			</label>
		</div>
        <?php } else { 
            $_POST['user_sex'] = "";
        } ?>
		<div class="form-group">
			<label for="user_team"><?php echo WORDING_REGISTRATION_TEAM; ?></label>
			<select class="form-control selectpicker" id="user_team" name="user_team" data-live-search="true">
				<option value=""><?php echo WORDING_REGISTRATION_TEAM_SELECT; ?></option>
				<?php foreach ($teams as $row) { ?>
					<option value="<?php echo($row['team_id']); ?>"<?php echo (isset($_SESSION["user_team_id"]) && $_SESSION['user_team_id'] == $row['team_id'] ? ' selected' : ''); ?>><?php echo($row['team_name']); ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label for="user_club"><?php echo WORDING_REGISTRATION_CLUB; ?></label>
			<select class="form-control selectpicker" id="user_club" name="user_club" data-live-search="true">
				<option value=""><?php echo WORDING_REGISTRATION_CLUB_SELECT; ?></option>
				<?php foreach ($clubs as $row) { ?>
					<option value="<?php echo($row['club_id']); ?>"<?php echo (isset($_SESSION["user_club_id"]) && $_SESSION['user_club_id'] == $row['club_id'] ? ' selected' : ''); ?>><?php echo($row['club_name']); ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label for="user_country"><?php echo WORDING_REGISTRATION_COUNTRY; ?></label>
			<select class="form-control selectpicker" id="user_country" name="user_country" data-live-search="true">
				<option value=""><?php echo WORDING_REGISTRATION_COUNTRY_SELECT; ?></option>
				<?php foreach ($countries as $row) { ?>
					<option value="<?php echo($row['country_id']); ?>"<?php echo (isset($_SESSION["user_country_id"]) && $_SESSION['user_country_id'] == $row['country_id'] ? ' selected' : ''); ?>><?php echo($row['country_name']); ?></option>
				<?php } ?>
			</select>
		</div>
		<?php if (ALLOW_DEPARTMENT_SELECTION) { ?>
		<div class="form-group">
			<label for="user_department"><?php echo WORDING_REGISTRATION_DEPARTMENT; ?></label>
			<select class="form-control selectpicker" id="user_department" name="user_department" data-live-search="true">
				<option value=""><?php echo WORDING_REGISTRATION_DEPARTMENT_SELECT; ?></option>
				<?php foreach ($departments as $row) { ?>
					<option value="<?php echo($row['department_id']); ?>"<?php echo (isset($_SESSION["user_department_id"]) && $_SESSION['user_department_id'] == $row['department_id'] ? ' selected' : ''); ?>><?php echo($row['business_name']. " - " . $row['department_name']) ?></option>
				<?php } ?>
			</select>
		</div>
		<?php } else { 
            $_POST['user_department'] = "";
        } ?>
		<div class="form-group">
			<button type="submit" name="user_edit_submit_optional" class="btn btn-success"><?php echo WORDING_CHANGE_OPTIONAL; ?></button>
		</div>		
	</form>
</div>

<?php include('views/footer.php'); ?>
