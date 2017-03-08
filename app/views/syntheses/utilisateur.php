<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
 <div class="container-fluid">
 <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Synthèse utilisateur
          </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <a target="_blank" href="<?= DIRECTORY_NAME ?>/export/synthese/utilisateur" class="btn btn-primary exportButto">EXPORTER</a>
      <form method = "POST" action = "<?= DIRECTORY_NAME ?>/syntheses/utilisateur" id="parDateForm">
        <input type="text" class="form-control dateInput dailydatepicker dailyInput pardateFormInput" name="to" id='to' value = "<?= $_SESSION['pardate']['to'] ?>" placeholder = "AAAA/MM/JJ">
        <input type="text" class="form-control dateInput dailydatepicker dailyInput pardateFormInput" name="from" id='from' value = "<?= $_SESSION['pardate']['from']?>" placeholder = "AAAA/MM/JJ">
        <select class="form-control dailyInput pardateFormInput" name="utilisateur">
          <?php  
          for($i = 0; $i<count($data['users']);$i++)
          {
            if($_SESSION['synthese_user'] == $data['users'][$i]['id'])
            {
              echo '<option selected value="'.$data['users'][$i]['id'].'">'.strtoupper($data['users'][$i]['lastName']).' '.ucfirst(strtolower($data['users'][$i]['firstName'])).'</option>';
            }
            else
            {
              echo '<option value="'.$data['users'][$i]['id'].'">'.strtoupper($data['users'][$i]['lastName']).' '.ucfirst(strtolower($data['users'][$i]['firstName'])).'</option>';
            }
            
          }
          ?>
        </select>
      </form>
    </div>
  </div>
<div class="bs-example">
  <div class="table-responsive">
    <table class="table table-bordered table-hover table-transactions" id="journaliere">
      <thead>
        <tr><th colspan="6">SYNTHESE UTILISATEUR</th><th colspan="2">Due le <?= $_SESSION['pardate']['from'] ?>  : <?= (!empty($data['transactions'][0]["balance_from"])) ?  number_format($data['transactions'][0]["balance_from"], 2, '.', " ") : "0.00" ?> HTG</th></tr>
        <tr>
          <th>Numéro</th>
          <th>Client</th>
          <th>Description</th>
          <th>Date</th>
          <th>Responsable</th>
          <th>Total (HTG)</th>
          <th>Crédit (HTG)</th>
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
              echo "<td class='editCat description'><strong>+" . number_format($data['transactions'][$i]['comission'], 2, ".", " ") . "</strong></td>";
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
        <tr><th colspan="6"></th><th colspan="2">Due le <?= $_SESSION['pardate']['from'] ?> : <?= (!empty($data['transactions'][0]["balance_to"])) ?  number_format($data['transactions'][0]["balance_to"], 2, '.', " ") : "0.00" ?> HTG</th></tr>
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