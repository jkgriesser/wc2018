<div class="container loginbox">
	<form role="form" class="form-horizontal" method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
		<div class="form-group">
			<legend><?php echo WORDING_LOGIN; ?></legend>
			<label for="user_name"><?php echo WORDING_USERNAME; ?></label>
			<input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter username or email" required />
		</div>
		<div class="form-group">
			<label for="user_password"><?php echo WORDING_PASSWORD; ?></label>
			<input type="password" class="form-control" id="user_password" name="user_password" placeholder="<?php echo WORDING_PASSWORD_PLACEHOLDER; ?>" autocomplete="off" required />
		</div>
		<div class="form-group">
			<div class="checkbox">
				<label>
					<input type="checkbox" id="user_rememberme" name="user_rememberme" value="1"/><label for="user_rememberme"> <?php echo WORDING_REMEMBER_ME; ?>
				</label>
			</div>	
		</div>
		<div class="form-group">
			<button type="submit" name="login" class="btn btn-success"><?php echo WORDING_SIGNIN; ?></button>
			</br>
			<?php echo (ALLOW_USER_REGISTRATION ? '<a href="?register">'. WORDING_REGISTER_NEW_ACCOUNT .'</a>&nbsp;&nbsp;|&nbsp;' : ''); ?>
			<a href="?password_reset"><?php echo WORDING_FORGOT_MY_PASSWORD; ?></a>
		</div>
	</form>
</div>
