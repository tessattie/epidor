<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
<div class="container-fluid">
	<div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Paramètres
          </li>
      </ul>
    </div>
  </div>

  <div class="error col-md-4"><?php if(!empty($data['error'])){
  	echo $data['error'];
  }  ?></div>
  <div class="errorDiv"></div>
	<div class="breadcrumb">
		<div class="row">
		<div class="changepw"></div>
		<form action = "<?= DIRECTORY_NAME ?>/utilisateurs/changePassword" method = "POST" class="form-inline" id='setpassform'>
			<div class="form-group">
				<label>Change password : </label>
			</div>
			<div class="form-group">
			    <input type="password" class="form-control oldpass" placeholder="Ancien mot de passe" name = "oldpass" required>
			  </div>
			<div class="form-group">
			  <input type="password" class="form-control newpass" placeholder="Nouveau mot de passe" name="newpass" required>
			</div>
			<div class="form-group">
			  <input type="password" class="form-control newpass2" placeholder="Confirmez mot de passe" name="newpass2" required>
			</div>
	    	<input type="submit" class="btn btn-default setpass" value="Valider" name="submit">		
		</form>
		
	</div>
	</div>
</div>



<?php include(ROOT_DIRECTORY.'/app/views/footer.php'); ?>