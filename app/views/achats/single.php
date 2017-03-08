<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
 <div class="container-fluid">
 <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Fiche
          </li>
          <li>#<?= $data['purchases'][0]['transaction_id'] ?>
          </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <a href="<?= DIRECTORY_NAME ?>/achats/fiche" class="btn btn-primary exportButto">RETOUR</a>
      <a href="<?= DIRECTORY_NAME ?>/achats/editer/<?= $data['purchases'][0]['id'] ?>" class="btn btn-primary exportButto">EDITER</a>
      <a target="_blank" href="<?= DIRECTORY_NAME ?>/pdf/imprimer/<?= $data['purchases'][0]['id'] ?>" class="btn btn-primary exportButto">IMPRIMER</a>
    </div>
  </div>
<div class="bs-example">
  <div class="table-responsive">
    <table class="table table-bordered table-hover" ng-app = "app">
      <thead>
        <tr><th colspan="4">DETAILS FICHE</th></tr>
        <tr>
          <td><strong>Responsable : <?= $data['purchases'][0]['u_firstname'] . " " . $data['purchases'][0]['u_lastname'] ?></strong></td>
          <td><strong>Date : <?= date("j/m/Y à H:i", strtotime($data['purchases'][0]['date']))?></strong></td>
          <td colspan="2"><strong>Client : <?= $data['purchases'][0]['c_firstname'] . " " . $data['purchases'][0]['c_lastname'] ?></strong></td>
        </tr>
        <tr>
          <th>Nom produit</th>
          <th>Prix</th>
          <th>Quantité</th>
          <th>Total (HTG)</th>
        </tr>
        
      </thead>
      <tbody>
        <?php  
        $count = count($data['purchases']);
        $subtotal = 0;
        for($i=0;$i<$count;$i++)
        {
          echo "<tr id = '".$data['purchases'][$i]['id']."' class='clients'>";
          echo "<td>" . $data['purchases'][$i]['name'] . "</td>";
          echo "<td class='lastname' id='lastname'>".number_format($data['purchases'][$i]['price'], 2, ".", " ")."</td>";
          echo "<td>" . $data['purchases'][$i]['quantity'] . "</td>";
          echo "<td><strong>" .number_format($data['purchases'][$i]['price']*$data['purchases'][$i]['quantity'], 2, ".", " ") . "</strong></td>";
          echo "</tr>";

          $subtotal = $subtotal + $data['purchases'][$i]['price']*$data['purchases'][$i]['quantity'];
        }
      ?>
      <tr><td colspan="3"><strong>TOTAL</strong></td><td><strong><?= number_format($subtotal, 2, ".", " ") ?></strong></td></tr>
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