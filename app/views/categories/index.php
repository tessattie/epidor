<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
 <div class="container-fluid">
 <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Catégories
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
  	</div>
  </div>
<div class="bs-example">
	<div class="table-responsive">
    <table class="table table-bordered table-hover modification">
      <thead>
        <tr class="modification"><th colspan="4">NOUVELLE CATEGORIE</th></tr>
        <tr class="modification"><form method="POST" action="<?= DIRECTORY_NAME ?>/categories">
          <td colspan="2"><input type="text" class="form-client" placeholder="Nom" name="name"></td>
          <td><select class="form-client" name="status" required>
            <option value="" disabled selected> -- Statut -- </option>
            <option value="1">Actif</option>
            <option value="0">Inactif</option>
          </select></td>
          <td class="deleteClient"><button class="btn btn-changed btn-primary" name="categorie"><i class='glyphicon glyphicon-ok'></i></button></td>
        </form></tr>
      </thead>
    </table>
    <table class="table table-bordered table-hover">
      <thead>
        <tr><th colspan="5">CATEGORIES</th></tr>
        <tr>
          <th colspan="3">Nom</th>
          <th colspan="2">Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          for($i=0;$i<count($data['categories']);$i++)
          {
            echo "<tr id = '".$data['categories'][$i]['id']."' class='categories'>";
            echo "<td>".($i+1)."</td>";
            echo "<td colspan='2' class='name edit firstname' id='name'>" . strtoupper($data['categories'][$i]['name']) . "</td>";
            echo "<td class='edit' id='user_status'>" . $data['categories'][$i]['status'] . "</td>";
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