<div class="container loginbox">
	<form role="form" class="form-horizontal" method="post" action="?password_reset">
		<div class="form-group">
			<legend><?php echo WORDING_FORGOT_MY_PASSWORD; ?></legend>
			<label for="user_name"><?php echo WORDING_REQUEST_PASSWORD_RESET; ?></label>
			<input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter username or email" required />
		</div>
		<div class="form-group">
			<button type="submit" name="request_password_reset" class="btn btn-success"><?php echo WORDING_RESET_PASSWORD; ?></button>
			</br>
			<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>"><?php echo WORDING_BACK_TO_LOGIN; ?></a>
		</div>
	</form>
</div>
