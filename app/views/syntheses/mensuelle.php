<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
 <div class="container-fluid">
 <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Synthèse mensuelle
          </li>
      </ul>
    </div>
  </div>
  <div class="row">
  	<div class="col-md-12">
      <a target="_blank" href="<?= DIRECTORY_NAME ?>/export/synthese/mensuelle" class="btn btn-primary exportButto">EXPORTER</a>
      <a target="_blank" href="<?= DIRECTORY_NAME ?>/pdf/imprimerSyntheseMensuelle" class="btn btn-primary exportButto">IMPRIMER</a>
      <form method = "POST" action = "<?= DIRECTORY_NAME ?>/syntheses/mensuelle" id="monthlyForm">
        <input type = "text" value="<?= $_SESSION['monthly']['year'] ?>" class="form-control monthlyFormInput dailyInput dailyYear">
        <select class="form-control dateInput dailyinput monthlyFormInput" name="month" id='month'>
          <option value = "01" <?= ($_SESSION['monthly']['month'] == "01") ? "selected" : "" ?>>Janvier</option>
          <option value = "02" <?= ($_SESSION['monthly']['month'] == "02") ? "selected" : "" ?>>Février</option>
          <option value = "03" <?= ($_SESSION['monthly']['month'] == "03") ? "selected" : "" ?>>Mars</option>
          <option value = "04" <?= ($_SESSION['monthly']['month'] == "04") ? "selected" : "" ?>>Avril</option>
          <option value = "05" <?= ($_SESSION['monthly']['month'] == "05") ? "selected" : "" ?>>Mai</option>
          <option value = "06" <?= ($_SESSION['monthly']['month'] == "06") ? "selected" : "" ?>>Juin</option>
          <option value = "07" <?= ($_SESSION['monthly']['month'] == "07") ? "selected" : "" ?>>Juillet</option>
          <option value = "08" <?= ($_SESSION['monthly']['month'] == "08") ? "selected" : "" ?>>Août</option>
          <option value = "09" <?= ($_SESSION['monthly']['month'] == "09") ? "selected" : "" ?>>Septembre</option>
          <option value = "10" <?= ($_SESSION['monthly']['month'] == "10") ? "selected" : "" ?>>Octobre</option>
          <option value = "11" <?= ($_SESSION['monthly']['month'] == "11") ? "selected" : "" ?>>Novembre</option>
          <option value = "12" <?= ($_SESSION['monthly']['month'] == "12") ? "selected" : "" ?>>Décembre</option>
        </select>

      </form>
  	</div>
  </div>
<div class="bs-example">
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-transactions" id="journaliere">
      <thead>
        <tr><th colspan="6">SYNTHESE MENSUELLE</th><th colspan="2">Balance le <?= "01-".$_SESSION['monthly']['month']."-".$_SESSION['monthly']['year'] ?>  : <?= (!empty($data['transactions'][0]["balance_from"])) ?  number_format($data['transactions'][0]["balance_from"], 2, '.', " ") : "0.00" ?> HTG</th></tr>
        <tr>
          <th>Numéro</th>
          <th>Client</th>
          <th>Description</th>
          <th>Date</th>
          <th>Responsable</th>
          <th>Total (HTG)</th>
          <th>Commission (HTG)</th>
          <th>Débit (HTG)</th>
          
        </tr>
      </thead>
      <tbody>
        <?php 
          $total_trans = 0;
          $total = 0;
          $comission = 0;
          $debit = 0;
          for($i=0;$i<count($data['transactions']);$i++)
          {
            echo "<tr id = '".$data['transactions'][$i]['id']."'>";
            echo "<td class='name' id='transaction_id'>#" . $data['transactions'][$i]['transaction_id'] . "</td>";
            echo "<td class='description'>" . $data['transactions'][$i]['client_firstname'] . " " . $data['transactions'][$i]['client_lastname'] . "</td>";
            if($data['transactions'][$i]['description'] == null)
            {
              echo "<td class='description tdcenter'><strong>-</strong></td>";
            }
            else
            {
              echo "<td class='description'>" . $data['transactions'][$i]['description'] . "</td>";
            }
            echo "<td id='user_status'>" . date("j/m/Y à H:i", strtotime($data['transactions'][$i]['date'])) . "</td>";
            echo "<td class='description'>" . $data['transactions'][$i]['user_firstname'] . " " . $data['transactions'][$i]['user_lastname'] . "</td>";
            if($data['transactions'][$i]['total'] == null)
            {
              if($data['transactions'][$i]['total_cash'] == null)
              {
                echo "<td class='description tdcenter'><strong>-</strong></td>";
              }
              else
              {
                echo "<td class='description'><strong>" . number_format($data['transactions'][$i]['total_cash'], 2, ".", " ") . "</strong></td>";
              }
            }
            else
            {
              echo "<td class='description'><strong>" . number_format($data['transactions'][$i]['total'], 2, ".", " ") . "</strong></td>";
            }

            if($data['transactions'][$i]['comission'] == null)
            {
              echo "<td class='description tdcenter'><strong>-</strong></td>";
            }
            else
            {
              echo "<td class='description'><strong>+" . number_format($data['transactions'][$i]['comission'], 2, ".", " ") . "</strong></td>";
            }
            if($data['transactions'][$i]['debit'] == null)
            {
              echo "<td class='description tdcenter'><strong>-</strong></td>";
            }
            else
            {
              echo "<td class='description'><strong>-" . number_format($data['transactions'][$i]['debit'], 2, ".", " ") . "</strong></td>";
            }
            echo "</tr>";
            $total = $total + $data['transactions'][$i]['total'] +  $data['transactions'][$i]['total_cash'];
            $comission = $comission + $data['transactions'][$i]['comission'];
            $debit = $debit + $data['transactions'][$i]['debit'];
          }
        ?>
      </tbody>

      <tfoot>
        <tr><th colspan="5">TOTAL (HTG)</th><th><?= number_format($total, 2, ".", " ")?></th><th>+<?= number_format($comission, 2, ".", " ")?></th><th>-<?= number_format($debit, 2, ".", " ")?></th></tr>
        <tr><th colspan="6"></th><th colspan="2">Balance le <?= date("t-m-Y" , strtotime("01-".$_SESSION['monthly']['month']."-".$_SESSION['monthly']['year'])) ?>  : <?= (!empty($data['transactions'][0]["balance_to"])) ?  number_format($data['transactions'][0]["balance_to"], 2, '.', " ") : "0.00" ?> HTG</th></tr>
      </tfoot>
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