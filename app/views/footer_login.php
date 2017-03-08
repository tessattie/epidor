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

    <script src="<?= DIRECTORY_NAME ?>/js/theme/plugins.min.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/theme/app.min.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/jquery-ui.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/chosen.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/datatable/jquery.dataTables.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/datatable/dataTables.bootstrap.js"></script>
    <script src="<?= DIRECTORY_NAME ?>/js/script.js"></script>
	</body>
</html>