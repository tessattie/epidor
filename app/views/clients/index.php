<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
 <div class="container-fluid">
 <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Clients
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
      <a href="<?= DIRECTORY_NAME ?>/export/clients" class="btn btn-primary exportButton">EXPORTER</a>
  	</div>
  </div>
<div class="bs-example">
	<div class="table-responsive">
		<table class="table table-bordered table-hover modification" ng-app = "app">
      <thead>
      	<tr><th colspan="9">NOUVEAU CLIENT</th></tr>
        
        <tr class="modification"><form name="frm" id="frm" method = "POST" action = "<?= DIRECTORY_NAME ?>/clients">
        	<td colspan="2"><input type="text" class="form-client" placeholder="Nom" name="lastname" id="lastname" ng-model="frm.lastname" required ng-init='frm.lastname="<?= (!empty($data['client']['lastname'])) ? $data['client']['lastname'] : "" ?>"'></td>
        	<td><input type="text" class="form-client" placeholder="Prénom" name="firstname" id="firstname" ng-model="frm.firstname" required ng-init='frm.firstname="<?= (!empty($data['client']['firstname'])) ? $data['client']['firstname'] : "" ?>"'></td>
        	<td><input type="text" class="form-client" placeholder="NIF" name="nif" numbers-only ng-model="frm.nif" ng-maxlength="8" ng-minlength="8" required ng-init='frm.nif="<?= (!empty($data['client']['nif'])) ? $data['client']['nif'] : "" ?>"'></td>
        	<td><input type="text" class="form-client" placeholder="Téléphone" name="telephone" numbers-only ng-maxlength="8" ng-minlength="8" ng-model="frm.telephone" required ng-init='frm.telephone="<?= (!empty($data['client']['telephone'])) ? $data['client']['telephone'] : "" ?>"'></td>
          <td><input type="text" class="form-client" placeholder="Plafond" name="plafond" numbers-only ng-init='frm.plafond="10000"' ng-model="frm.plafond" required ng-init='frm.lastname="<?= (!empty($data['client']['plafond'])) ? $data['client']['plafond'] : "" ?>"'></td>
          <td><select class="form-client" name="status" ng-model="frm.status" required ng-init='frm.status="<?= (!empty($data['client']['status'])) ? $data['client']['status'] : "" ?>"'>
            <option value="" disabled selected> -- Statut -- </option>
            <option value="1">Actif</option>
            <option value="0">Inactif</option>
          </select></td>
        	<td class="deleteClient"><button class="btn btn-changed btn-primary" ng-disabled="frm.$invalid" name="submit"><i class='glyphicon glyphicon-ok'></i></button></td>
        </form></tr>
      </thead>
    </table> 
    <table class="table table-bordered table-hover" id="clientsTable">
      <thead>
        <tr><th colspan="10">CLIENTS</th></tr>
        <tr>
          <th>ID client</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>NIF</th>
          <th>Téléphone</th>
          <th>Plafond (HTG)</th>
          <th>Statut</th>
          <th><strong>Solde (HTG)</strong></th>
        </tr>
      </thead>
      <tbody>
        <?php  
        $count = count($data['clients']);
        for($i=0;$i<$count;$i++)
        {
          echo "<tr id = '".$data['clients'][$i]['id']."' class='clients'>";
          echo "<td>" . $data['clients'][$i]['id'] . "</td>";
          if($data['clients'][$i]['status'] == "Actif")
          {
            echo "<td class='lastname edit' id='lastname'><a href='".DIRECTORY_NAME."/clients/setClientChoice/".$data['clients'][$i]['id']."'>" . strtoupper($data['clients'][$i]['lastname']) . "</a></td>";
          }
          else
          {
            echo "<td class='lastname edit' id='lastname'>" . strtoupper($data['clients'][$i]['lastname']) . "</td>";
          }
          echo "<td class='firstname edit' id='firstname'>" . ucfirst($data['clients'][$i]['firstname']) . "</td>";
          echo "<td class='edit' id='NIF'>" . $data['clients'][$i]['NIF'] . "</td>";
          echo "<td class='edit' id='telephone'>+509 " . $data['clients'][$i]['telephone'] . "</td>";
          echo "<td class='edit' id='plafond'>" . number_format($data['clients'][$i]['plafond'], 2, ".", " ") . "</td>";
          echo "<td class='edit' id='user_status'>" . $data['clients'][$i]['status'] . "</td>";
          echo "<td>" . number_format($data['clients'][$i]['balance'], 2, ".", " ") . "</td>";
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