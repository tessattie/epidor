<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
 <div class="container-fluid">
 <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Fiches
          </li>
          <li><?= $_SESSION['client']['firstname'] . ' ' . $_SESSION['client']['lastname'] ?>
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
<div class="bs-example">
  <div class="table-responsive">
    <table class="table table-bordered table-hover" id="fichestable">
      <thead>
        <tr><th colspan="5">FICHES CLIENT</th></tr>
        <tr>
          <th>Numéro de fiche</th>
          <th>Client</th>
          <th>Date</th>
          <th>Total (HTG)</th>
          <th>Responsable</th>
        </tr>
        
      </thead>
      <tbody>
        <?php  
        $count = count($data['fiches']);
        for($i=0;$i<$count;$i++)
        {
          echo "<tr id = '".$data['fiches'][$i]['id']."' class='clients'>";
          echo "<td><a href='".DIRECTORY_NAME."/achats/single/".$data['fiches'][$i]['id']."'>" . $data['fiches'][$i]['transaction_id'] . "</a></td>";
          echo "<td class='lastname edit' id='lastname'>" . ucfirst($data['fiches'][$i]['client_firstname']) . " " . $data['fiches'][$i]['client_lastname']. "</td>";
          echo "<td>" . date("j/m/Y à H:i", strtotime($data['fiches'][$i]['date'])) . "</td>";
          echo "<td id='NIF'>" .number_format($data['fiches'][$i]['total'], 2, ".", " ") . "</td>";
          echo "<td id='plafond'>" . $data['fiches'][$i]['user_firstname'] . " " .  $data['fiches'][$i]['user_lastname'] . "</td>";
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