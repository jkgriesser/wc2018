<?php
$db_connection = $login->getDatabaseConnection();
$teams = $db_connection->query('SELECT team_id, team_name FROM teams WHERE team_active = 1 ORDER BY team_name');
$clubs = $db_connection->query('SELECT club_id, club_name FROM clubs ORDER BY club_name');
$countries = $db_connection->query('SELECT country_id, country_name FROM countries ORDER BY country_name');
$departments = $db_connection->query('SELECT d.department_id, b.business_name, d.department_name FROM departments d, businesses b
    WHERE d.business_id = b.business_id ORDER BY b.business_name, d.department_name');
?>

<head>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<div class="container loginbox">
	<form role="form" class="form-horizontal" method="post" action="?register">
		<div class="form-group">
			<legend><?php echo WORDING_REGISTRATION; ?></legend>
			<label for="user_name"><?php echo WORDING_REGISTRATION_USERNAME; ?></label>
			<input type="text" pattern="[a-zA-Z0-9]{2,64}" class="form-control" id="user_name" name="user_name" value="<?php echo (isset($_POST['user_name']) ? $_POST['user_name'] : ''); ?>" placeholder="<?php echo WORDING_REGISTRATION_USERNAME_PLACEHOLDER ?>" required />
		</div>
		<div class="form-group">
			<label for="user_first_name"><?php echo WORDING_REGISTRATION_FIRSTNAME; ?></label>
			<input type="text" class="form-control" id="user_first_name" name="user_first_name" value="<?php echo (isset($_POST['user_first_name']) ? $_POST['user_first_name'] : ''); ?>" placeholder="<?php echo WORDING_REGISTRATION_FIRSTNAME_PLACEHOLDER ?>" required />
		</div>
		<div class="form-group">
			<label for="user_last_name"><?php echo WORDING_REGISTRATION_LASTNAME; ?></label>
			<input type="text" class="form-control" id="user_last_name" name="user_last_name" value="<?php echo (isset($_POST['user_last_name']) ? $_POST['user_last_name'] : ''); ?>" placeholder="<?php echo WORDING_REGISTRATION_LASTNAME_PLACEHOLDER ?>" required />
		</div>
		<div class="form-group">
			<label for="user_email"><?php echo WORDING_REGISTRATION_EMAIL; ?></label>
			<input type="email" class="form-control" id="user_email" name="user_email" placeholder="<?php echo WORDING_REGISTRATION_EMAIL_PLACEHOLDER ?>" value="<?php echo (isset($_POST['user_email']) ? $_POST['user_email'] : ''); ?>" required />
		</div>
		<div class="form-group">
			<label for="user_password_new"><?php echo WORDING_REGISTRATION_PASSWORD; ?></label>
			<input type="password" class="form-control" id="user_password_new" name="user_password_new" pattern=".{6,}" placeholder="<?php echo WORDING_REGISTRATION_PASSWORD_PLACEHOLDER ?>" autocomplete="off" required />
		</div>
		<div class="form-group">
			<label for="user_password_repeat"><?php echo WORDING_REGISTRATION_PASSWORD_REPEAT; ?></label>
			<input type="password" class="form-control" id="user_password_repeat" name="user_password_repeat" pattern=".{6,}" placeholder="<?php echo WORDING_REGISTRATION_PASSWORD_REPEAT_PLACEHOLDER ?>" autocomplete="off" required />
		</div>
		<div class="form-group">
			<legend><?php echo WORDING_OPTIONAL; ?></legend>			
			<a href="http://www.gravatar.com" target="_blank">
				<img src="/img/content/Logo_Gravatar.png" />
				<br/>
				<?php echo WORDING_REGISTRATION_GRAVATAR; ?>
			</a>
		</div>
		<?php if (ALLOW_SEX_SELECTION) { ?>
		<div class="form-group" data-toggle="buttons">
			<label for="user_sex"><?php echo WORDING_REGISTRATION_SEX; ?></label>
			<br/>
			<label class="btn btn-primary<?php echo (isset($_POST["user_sex"]) && $_POST['user_sex'] == 1 ? ' active' : ''); ?>">
				<input type="radio" name="user_sex" id="option1" value="1"<?php echo (isset($_POST["user_sex"]) && $_POST['user_sex'] == 1 ? ' checked' : ''); ?>> <?php echo WORDING_REGISTRATION_FEMALE; ?>
			</label>
			<label class="btn btn-primary<?php echo (isset($_POST["user_sex"]) && $_POST['user_sex'] == 2 ? ' active' : ''); ?>">
				<input type="radio" name="user_sex" id="option2" value="2"<?php echo (isset($_POST["user_sex"]) && $_POST['user_sex'] == 2 ? ' checked' : ''); ?>> <?php echo WORDING_REGISTRATION_MALE; ?>
			</label>
			<label class="btn btn-primary<?php echo (isset($_POST["user_sex"]) && $_POST['user_sex'] == 3 ? ' active' : ''); ?>">
				<input type="radio" name="user_sex" id="option3" value="3"<?php echo (isset($_POST["user_sex"]) && $_POST['user_sex'] == 3 ? ' checked' : ''); ?>> <?php echo WORDING_REGISTRATION_TRANS; ?>
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
					<option value="<?php echo($row['team_id']); ?>"<?php echo (isset($_POST["user_team"]) && $_POST['user_team'] == $row['team_id'] ? ' selected' : ''); ?>><?php echo($row['team_name']); ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label for="user_club"><?php echo WORDING_REGISTRATION_CLUB; ?></label>
			<select class="form-control selectpicker" id="user_club" name="user_club" data-live-search="true">
				<option value=""><?php echo WORDING_REGISTRATION_CLUB_SELECT; ?></option>
				<?php foreach ($clubs as $row) { ?>
					<option value="<?php echo($row['club_id']); ?>"<?php echo (isset($_POST["user_club"]) && $_POST['user_club'] == $row['club_id'] ? ' selected' : ''); ?>><?php echo($row['club_name']); ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label for="user_country"><?php echo WORDING_REGISTRATION_COUNTRY; ?></label>
			<select class="form-control selectpicker" id="user_country" name="user_country" data-live-search="true">
				<option value=""><?php echo WORDING_REGISTRATION_COUNTRY_SELECT; ?></option>
				<?php foreach ($countries as $row) { ?>
					<option value="<?php echo($row['country_id']); ?>"<?php echo (isset($_POST["user_country"]) && $_POST['user_country'] == $row['country_id'] ? ' selected' : ''); ?>><?php echo($row['country_name']); ?></option>
				<?php } ?>
			</select>
		</div>
		<?php if (ALLOW_DEPARTMENT_SELECTION) { ?>
		<div class="form-group">
			<label for="user_department"><?php echo WORDING_REGISTRATION_DEPARTMENT; ?></label>
			<select class="form-control selectpicker" id="user_department" name="user_department" data-live-search="true">
				<option value=""><?php echo WORDING_REGISTRATION_DEPARTMENT_SELECT; ?></option>
				<?php foreach ($departments as $row) { ?>
					<option value="<?php echo($row['department_id']); ?>"<?php echo (isset($_POST["user_department"]) && $_POST['user_department'] == $row['department_id'] ? ' selected' : ''); ?>><?php echo($row['business_name']. " - " . $row['department_name']) ?></option>
				<?php } ?>
			</select>
		</div>
		<?php } else { 
            $_POST['user_department'] = "";
        } ?>
		<div class="form-group">
            <div class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_SITE_KEY ?>"></div>
		</div>
		<div class="form-group">
			<button type="submit" name="register" class="btn btn-success"><?php echo WORDING_REGISTER; ?></button>
			<br/>
			<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>"><?php echo WORDING_BACK_TO_LOGIN; ?></a>
		</div>
	</form>
</div>
