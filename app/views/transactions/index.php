<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Nouvelle transaction
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
  <div class="row">
  	<div class="col-md-12">
  		<a href="#" class="btn btn-primary btn-raised newElement">NOUVEAU</a>
  	</div>
  </div>
<div class="bs-example">
	<div class="table-responsive">
		<table class="table table-bordered table-hover modification">
      <thead>
      	<tr><th colspan="5">NOUVEAU RETRAIT</th></tr>
        <tr class="modification"><form method="POST" action="<?= DIRECTORY_NAME ?>/transactions">
          <input type = "hidden" value = "<?= $form_id ?>" name = "form_id">
        	<td><input type="text" class="form-client" placeholder="Montant" name="amount" required <?= (!empty($data['transaction'])) ? 'value="'.$data['transaction']['amount'].'"' : "" ?>></td>
        	<td colspan="3"><input type="text" class="form-client" placeholder="Description" name="description" required <?= (!empty($data['transaction'])) ? 'value="'.$data['transaction']['description'].'"' : "" ?>></td>
        	<td class="deleteClient"><button class="btn btn-changed btn-primary" name="submit"><i class='glyphicon glyphicon-ok'></i></button></td>
        </tr>
      </thead>
    </table>
    <table class="table table-bordered table-hover" id = "retraitsTable">
      <thead>
        <tr><th colspan="4">RETRAITS</th><th class="tdsuccess">SOLDE : <span class="soldeRetrait"><?= number_format($data['balance'], 2, ".", " ") ?></span> (HTG)</th></tr>
        <tr>
          <th># Transaction</th>
          <th>Montant (HTG)</th>
          <th>Description</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(!empty($_SESSION['role']) && $_SESSION['role'] == "SA")
        {
          for($i=0;$i<count($data['transactions']);$i++)
          {
            echo "<tr id = '".$data['transactions'][$i]['id']."' class='debit'>";
            echo "<td class='name firstname' id='transaction_id'>#" . strtoupper($data['transactions'][$i]['transaction_id']) . "</td>";
            echo "<td class='edit' id='amount'>-" . number_format($data['transactions'][$i]['debit'], 2, ".", " ") . "</td>";
            echo "<td class='edit' id='description'>" . $data['transactions'][$i]['description'] . "</td>";
            echo "<td>" . date("j/m/Y à H:i", strtotime($data['transactions'][$i]['date'])) . "</td>";
            echo "<td class='deleteClient'><a href='".DIRECTORY_NAME."/transactions/deleteTransaction/".$data['transactions'][$i]['id']."' class='supprimerclient'><i class='fa fa-trash-o'></i></a>
            <a target='_blank' href='".DIRECTORY_NAME."/pdf/imprimerRetrait/".$data['transactions'][$i]['id']."'><i class='fa fa-print'></i></a></td>";
            echo "</tr>";
          }
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