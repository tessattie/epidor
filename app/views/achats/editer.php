<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
 <div class="container-fluid">
 <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Nouvel achat
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
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-12 rechercheCatalogue">
          <form class="form-inline">
            <div class="form-group categoriesFilter">
            <select class="chosen-select form-control" multiple tabindex="4">
              <?php 
                if(!empty($data['categories']))
                {
                  for($i=0;$i<count($data['categories']);$i++)
                  {
                    echo '<option value="'.$data['categories'][$i]['id'].'" selected="selected">'.$data['categories'][$i]['name'].'</option>';
                  }
                }
              ?>
            </select>
          </div>
          <div class="form-group researchFilter">
            <input type="text" placeholder="Recherchez un produit" class="form-control rechercheCatalogueFiltre">
          </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12" id="productCatalog">
          <?php 
        $width = 280*count($data['categories']);
      ?>
      <div style="width:<?= $width ?>px;height:auto;">
        <?php  
          for($i=0;$i<count($data['products']);$i++)
          {
            if($i == 0 || ( $i > 0 && $data['products'][$i]['category_id'] != $data['products'][$i-1]['category_id']))
            {
              echo "<div class='catalogueTable'>";
              echo '<table class="table table-bordered table-hover tableAchats tableProduits" id="'.$data['products'][$i]['category_id'].'">';
              echo '<thead>
                      <tr><th colspan="2">'.$data['products'][$i]['cat_name'].'</th></tr>
                    </thead><tbody>';
            }
            echo '<tr id="'.$data['products'][$i]['id'].'" class="addToFiche"><td class="name">'.$data['products'][$i]['name'].'</td><td class="price">'.number_format($data['products'][$i]['price'], 2, ".", " ").'</td></tr>';
            if(!empty($data['products'][$i+1]['category_id']))
            {
              if( $i > 0 && $data['products'][$i]['category_id'] != $data['products'][$i+1]['category_id'])
              {
                echo '</tbody></table></div>';
              }
            }
            else
            {
              echo '</tbody></table></div>';
            }
          }
        ?>
      </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <form method="POST" action="<?= DIRECTORY_NAME ?>/achats/editer" id="ficheForm">
        <input type = "hidden" value = "<?= $form_id ?>" name = "form_id">
        <input type = "hidden" value = "<?= $data['purchases'][0]['id'] ?>" name = "transaction">
      <div class="row">
        <div class="col-md-12">
          <input type="submit" class="btn btn-success" name="submit" value="VALIDER FICHE" id="validerFiche">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive table-height">

            <table class="table table-bordered table-hover tableAchats">
              <thead>
                <tr><th colspan="5">Fiche</th></tr>
                <tr><th colspan="2">Nom</th><th>Prix</th><th>Qté</th><th>Total</th></tr>
              </thead>
              <tbody class="ficheBody">
                <?php  
                  $count = count($data['purchases']);
                  $subtotal = 0;
                  for($i=0; $i<$count; $i++)
                  {
                    if($data['purchases'][$i]['quantity'] == null)
                    {
                      $data['purchases'][$i]['quantity'] = $data['purchases'][$i]['cash_quantity'];
                      $data['purchases'][$i]['product_id'] = $data['purchases'][$i]['cash_product_id'];
                      $data['purchases'][$i]['price'] = $data['purchases'][$i]['cash_price'];
                      $data['purchases'][$i]['name'] = $data['purchases'][$i]['cash_product_name'];
                    }
                    echo "<tr class = '".$data['purchases'][$i]['product_id']."'>";
                    echo "<td class='tddanger removeFiche' id='removeFiche'>-</td>";
                    echo "<td class='name'>".$data['purchases'][$i]['name']."<input type='hidden' class='qtyInput' name='quantity[]' value='".$data['purchases'][$i]['quantity']."'><input type='hidden' class='idInput' name='id[]' value='".$data['purchases'][$i]['product_id']."'></td>";
                    echo "<td class = 'price'><input type='hidden' class='priceInput' name='price[]' value='".$data['purchases'][$i]['price']."'>
                    <span id='pricevalue'>".$data['purchases'][$i]['price']."</span></td>";
                    echo "<td class='qty changeqty'>".$data['purchases'][$i]['quantity']."</td>";
                    echo "<td class='total'>".number_format($data['purchases'][$i]['quantity']*$data['purchases'][$i]['price'], 2, ".", " ")."</td>";
                    echo "</tr>";
                    $subtotal = $subtotal + $data['purchases'][$i]['price']*$data['purchases'][$i]['quantity'];
                  }
                ?>
              </tbody>
              <tfoot><tr><td colspan="4">TOTAL</td><td id="ficheTotal"><?= number_format($subtotal, 2, ".", " ") ?></td></tr></tfoot>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered quantityTable">
            <thead>
              <tr><th colspan="2">Quantité</th><td class="tddanger"><span class="glyphicon glyphicon-arrow-left"></span></td></tr>
            </thead>
            <tbody>
              <tr><td class="one number">1</td><td class="two number">2</td><td class="three number">3</td></tr>
              <tr><td class="four number">4</td><td class="five number">5</td><td class="six number">6</td></tr>
              <tr><td class="seven number">7</td><td class="eight number">8</td><td class="nine number">9</td></tr>
              <tr><td colspan="2" class="zero number">0</td><td class="tdsuccess"><span class="glyphicon glyphicon-ok"></span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
      </form>
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