      <footer class="ms-footer">
        <?php
        $disabled = ''; 
        $class = '';
        if(empty($_SESSION['client']))
        {
          $disabled = 'disabled="disabled"';
          $class = "FaireChoix";
        }
        ?>
        <div class="container">
          <p>Copyright &copy; EPI D'OR <?= date('Y') ?></p>
        </div>
      </footer>
      <div class="btn-back-top">
        <a href="#" data-scroll id="back-top" class="btn-circle btn-circle-primary btn-circle-sm btn-circle-raised ">
          <i class="zmdi zmdi-long-arrow-up"></i>
        </a>
      </div>
    </div>
    <!-- sb-site-container -->
    <div class="ms-slidebar sb-slidebar sb-left sb-momentum-scrolling sb-style-overlay">
      <header class="ms-slidebar-header">
          <div class="ms-slidebar-t">
            <img src="<?= DIRECTORY_NAME ?>/img/logo.jpg">
          </div>
          <div class="ms-slidebar-t">
            <h4><strong><?= $_SESSION['firstname'] . " " . $_SESSION['lastname'] ?></strong></h4>
          </div>
      </header>
      <ul class="ms-slidebar-menu" id="slidebar-menu" role="tablist" aria-multiselectable="true">
        <li class="panel">
          <a class="collapsed" href="<?= DIRECTORY_NAME ?>/home" aria-expanded="false">
            Manuel utilisateur </a>
        </li>
        <li class="panel">
          <a class="collapsed <?= $class ?>" href="<?= DIRECTORY_NAME ?>/achats/nouveau" <?= $disabled ?> aria-expanded="false" alt='Choose client to access this page'>
            Nouvel achat</a>
        </li>
        <li class="panel">
          <a class="collapsed <?= $class ?>" href="<?= DIRECTORY_NAME ?>/transactions" <?= $disabled ?> aria-expanded="false">
            Retrait</a>
        </li>
        <li class="panel">
          <a class="collapsed" href="<?= DIRECTORY_NAME ?>/clients" aria-expanded="false">
            Clients</a>
        </li>
        <li class="panel">
          <a class="collapsed" href="<?= DIRECTORY_NAME ?>/utilisateurs/compte" aria-expanded="false">
            Paramètres </a>
        </li>
        <li class="panel">
          <a class="collapsed" href="<?= DIRECTORY_NAME ?>/utilisateurs/deconnexion" aria-expanded="false">
            Déconnexion </a>
        </li>
      </ul>
    </div>
		<script src="<?= DIRECTORY_NAME ?>/js/theme/plugins.min.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/theme/app.min.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/jquery-ui.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/chosen.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/datatable/jquery.dataTables.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/datatable/dataTables.bootstrap.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/script.js"></script>
    

    <script>
      $(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });
    </script>
	</body>
</html>