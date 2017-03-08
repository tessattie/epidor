<?php include(ROOT_DIRECTORY.'/app/views/header.php'); ?>
 <div class="container-fluid">
 <div class="row">
    <div class="col-md-12">
      <ul class="breadcrumb">
        <li>EPI D'OR</li>
          <li>Produits
          </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
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
  <div class="row">
    <div class="col-md-12">
      <div class="bs-example">
        <div class="table-responsive">
          <table class="table table-bordered table-hover modification">
            <thead>
              <tr class="modification"><th colspan="8">NOUVEAU PRODUIT</th></tr>
              <tr class="modification"><form method="POST" action="<?= DIRECTORY_NAME ?>/produits">
                <td colspan="2"><input type="text" class="form-client" placeholder="Nom" name="name" required></td>
                <td><select class="form-client" name="category_id" required>
                  <option value="" disabled selected> -- Category -- </option>
                  <?php  
                    for($i=0;$i<count($data['active_cat']);$i++)
                    {
                      echo "<option value='".$data['categories'][$i]['id']."'>".$data['categories'][$i]['name']."</option>";
                    }
                  ?>
                </select></td>
                <td><input type="text" class="form-client" placeholder="Prix" name="price"></td>
                <td colspan="2"><select class="form-client" name="status" required>
                  <option value="" disabled selected> -- Statut -- </option>
                  <option value="1">Actif</option>
                  <option value="0">Inactif</option>
                </select></td>
                <td colspan="2" class="deleteClient"><button class="btn btn-changed btn-primary" name="submit"><i class='glyphicon glyphicon-ok'></i></button></td>
              </form></tr>
            </thead>
          </table>

          <table class="table table-bordered table-hover"  id="productsTable">
            <thead>
              <tr><th colspan="5">PRODUITS</th></tr>
              <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix (HTG)</th>
                <th>Statut</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                for($i=0;$i<count($data['products']);$i++)
                {
                  echo "<tr id = '".$data['products'][$i]['id']."' class='products'>";
                  echo "<td>".($i+1)."</td>";
                  echo "<td class='name edit firstname' id='name'>" . strtoupper($data['products'][$i]['name']) . "</td>";
                  echo "<td class='editCat category_id' id='".$data['products'][$i]['category_id']."'>" . $data['products'][$i]['cat_name'] . "</td>";
                  echo "<td class='edit' id='price'>" . number_format($data['products'][$i]['price'], 2, ".", " ") . "</td>";
                  echo "<td class='edit' id='user_status'>" . $data['products'][$i]['status'] . "</td>";
                  echo "</tr>";
                }
              ?>
            </tbody>
          </table>

        </div>
      </div>
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