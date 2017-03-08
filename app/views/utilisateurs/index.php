<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
 <div class="container-fluid">
 <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Utilisateurs
          </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <?php 
        if(!empty($data['error']))
        {
          echo $data['error'];
        }
      ?>
    </div>
  </div>
  <div class="row">
  	<div class="col-md-12">
  		<a href="#" class="btn btn-primary btn-raised newElement">NOUVEAU</a>
  		<a href="#" class="btn btn-primary btn-raised closeInputs">ANNULER EDITION</a>
  	</div>
  </div>
<div class="bs-example">
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
      <thead>
      	<tr><th colspan="7">UTILISATEURS</th></tr>
        <tr>
          <th colspan="2">Nom</th>
          <th>Prénom</th>
          <th>Nom d'utilisateur</th>
          <th>Statut</th>
          <th colspan="2"><strong>Accès</strong></th>
        </tr>
        <tr>
          <form method="POST" action="<?= DIRECTORY_NAME ?>/utilisateurs">
          <input type = "hidden" value = "<?= $form_id ?>" name = "form_id">
        	<td colspan="2"><input type="text" class="form-client" placeholder="Nom" name="lastname" required <?= (!empty($data['user']['lastname'])) ? "value='".$data['user']['lastname']."'" : "" ?>></td>
        	<td><input type="text" class="form-client" placeholder="Prénom" name="firstname" required <?= (!empty($data['user']['firstname'])) ? "value='".$data['user']['firstname']."'" : "" ?>></td>
        	<td><input type="text" class="form-client" placeholder="Nom d'utilisateur" name="username" required <?= (!empty($data['user']['username'])) ? "value='".$data['user']['username']."'" : "" ?>></td>
        	<td><select class="form-client" name="status" required>
            <option value="" disabled selected> -- Statut -- </option>
            <option value="1" <?= (!empty($data['user']['status']) && $data['user']['status'] == 1 ) ? "selected" : "" ?>>Actif</option>
            <option value="0" <?= (!empty($data['user']['status']) && $data['user']['status'] == 0) ? "selected" : "" ?>>Inactif</option>
          </select></td>
          <td><select class="form-client" name="access" required> 
            <option value="" disabled selected> -- Accès -- </option>
            <option value="SA" <?= (!empty($data['user']['access']) && $data['user']['access'] == "SA" ) ? "selected" : "" ?>>Super-admin</option>
            <option value="A" <?= (!empty($data['user']['access']) && $data['user']['access'] == "A" ) ? "selected" : "" ?>>Admin</option>
          </select></td>
        	<td colspan="2" class="deleteClient"><button class="btn btn-changed btn-primary" name="submit"><i class='glyphicon glyphicon-ok'></i></button></td>
          </form>
        </tr>
      </thead>
      <tbody>
        <?php  
        $count = count($data['users']);
        for($i=0;$i<$count;$i++)
        {
          echo "<tr id = '".$data['users'][$i]['id']."' class='utilisateurs'>";
          echo "<td colspan='2' class='lastname'>" . strtoupper($data['users'][$i]['lastName']) . "</td>";
          echo "<td class='firstname'>" . ucfirst($data['users'][$i]['firstName']) . "</td>";
          echo "<td>" . $data['users'][$i]['username'] . "</td>";
          echo "<td class='edit' id='user_status'>" . $data['users'][$i]['status'] . "</td>";
          echo "<td class='edit' id='access'>" . $data['users'][$i]['access'] . "</td>";
          echo "<td class='deleteClient'><a href='".DIRECTORY_NAME."/utilisateurs/delete/".$data['users'][$i]['id']."' class='supprimerclient'><i class='fa fa-trash-o'></i></a>
          <a href='".DIRECTORY_NAME."/utilisateurs/refresh/".$data['users'][$i]['id']."'><i class='fa fa-refresh'></i></a></td>";
          echo "</tr>";
        }
      ?>
      </tbody>
    </table>
	</div>
    
  </div>
</div>

<?php 
if($_SESSION["role"] == "SA")
{
  include(ROOT_DIRECTORY.'/app/views/footer.php'); 
}
else
{
  include(ROOT_DIRECTORY.'/app/views/footer_two.php'); 
}

?>