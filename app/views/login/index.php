<?php include(ROOT_DIRECTORY.'/app/views/headertwo.php'); ?>

<div class="container">
	<div class="row signinrow">
		<div class="col-md-4 col-md-offset-4">
			
			<form class="form-signin" action = '<?= DIRECTORY_NAME ?>/login' method = "POST" autocomplete="off">
			    <h2 class="form-signin-heading">Please sign in</h2>

			    <input type="text" class="form-control" placeholder="Username" name = "username" required>

			    <input type="password" class="form-control" placeholder="Password" name = "password" required>
			    <?= $data['error']; ?>
			    <input type='submit' class="btn btn-primary" value='Submit' name="submit">
			</form>
		</div>
	</div>
</div> <!-- /container -->

<?php include(ROOT_DIRECTORY.'/app/views/footer_login.php'); ?>
