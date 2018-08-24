<div class="container loginbox">
	<form role="form" class="form-horizontal" method="post" action="?password_reset">
		<input type='hidden' name='user_name' value='<?php echo $_REQUEST['user_name']; ?>' />
		<input type='hidden' name='verification_code' value='<?php echo $_REQUEST['verification_code']; ?>' />
		<div class="form-group">
			<legend><?php echo WORDING_FORGOT_MY_PASSWORD; ?></legend>
			<label for="user_password_new"><?php echo WORDING_NEW_PASSWORD; ?></label>
			<input type="password" class="form-control" id="user_password_new" name="user_password_new" placeholder="<?php echo WORDING_NEW_PASSWORD_PLACEHOLDER ?>" pattern=".{6,}" required autocomplete="off" />
		</div>
		<div class="form-group">
			<label for="user_password_repeat"><?php echo WORDING_NEW_PASSWORD_REPEAT; ?></label>
			<input type="password" class="form-control" id="user_password_repeat" name="user_password_repeat" placeholder="<?php echo WORDING_NEW_PASSWORD_REPEAT_PLACEHOLDER ?>" pattern=".{6,}" required autocomplete="off" />
		</div>
		<div class="form-group">
			<button type="submit" name="submit_new_password" class="btn btn-success"><?php echo WORDING_RESET_PASSWORD; ?></button>
			</br>
			<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>"><?php echo WORDING_BACK_TO_LOGIN; ?></a>
		</div>
	</form>
</div>
