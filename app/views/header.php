<html>
<head>
	<title>EPID'OR</title>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="theme-color" content="#333">
  <meta name="description" content="WYZDEV official website">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="<?= DIRECTORY_NAME ?>/css/theme/preload.min.css" />
  <link rel="stylesheet" href="<?= DIRECTORY_NAME ?>/css/theme/plugins.min.css" />
  <link rel="stylesheet" href="<?= DIRECTORY_NAME ?>/css/theme/style.red-800.min.css" />
  <link rel="stylesheet" href="<?= DIRECTORY_NAME ?>/css/style.css" media="screen" />
  <link rel="stylesheet" href="<?= DIRECTORY_NAME ?>/css/dataTables.bootstrap.css" media="screen" />
  <link rel="stylesheet" href="<?= DIRECTORY_NAME ?>/css/bootstrap-chosen.css" media="screen" />
  <link rel="stylesheet" href="<?= DIRECTORY_NAME ?>/css/jquery-ui.css">
  <link rel="apple-touch-icon" sizes="57x57" href="<?= DIRECTORY_NAME ?>/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?= DIRECTORY_NAME ?>/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?= DIRECTORY_NAME ?>/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?= DIRECTORY_NAME ?>/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?= DIRECTORY_NAME ?>/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?= DIRECTORY_NAME ?>/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="<?= DIRECTORY_NAME ?>/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?= DIRECTORY_NAME ?>/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= DIRECTORY_NAME ?>/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="<?= DIRECTORY_NAME ?>/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= DIRECTORY_NAME ?>/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="<?= DIRECTORY_NAME ?>/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= DIRECTORY_NAME ?>/favicon-16x16.png">
  <link rel="manifest" href="<?= DIRECTORY_NAME ?>/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
  <?php echo '<script type="text/javascript"> var DIRECTORY_NAME = "'.DIRECTORY_NAME.'"</script>'; ?>
  <script src="<?= DIRECTORY_NAME ?>/js/angular.min.js"></script>
  <script src="<?= DIRECTORY_NAME ?>/js/angular.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <?php
  if(!empty($data['active_cat']))
  {
    echo '<script type="text/javascript">';
    echo 'var categories = [];';
    for($i=0;$i<count($data['active_cat']);$i++)
    {
      echo "categories['".$data['active_cat'][$i]['name']."'] = ".$data['active_cat'][$i]['id'].";";
    }
    echo '</script>';
  }

  ?>
</head>
<body>

  <?php  
    $form_id = rand(0000, 9999);
  ?>
	<div class="sb-site-container">
      <nav class="navbar navbar-static-top yamm ms-navbar ms-navbar-primary navbar-mode">
        <div class="container container-full">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.html">
              <!-- <img src="assets/img/demo/logo-navbar.png" alt=""> -->
              <span class="ms-logo ms-logo-sm">E</span>
              <span class="ms-title">EPI 
                <strong>D'OR</strong>
              </span>
            </a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li>
                <?php 
                    echo '<input type="text" class="form-control dateInput clientInputMain"';
                    if(!empty($_SESSION['client']))
                    {
                      echo " value = '".$_SESSION['client']['firstname']." ".$_SESSION['client']['lastname']. " - NIF : ".$_SESSION['client']['NIF']." - ID : ". $_SESSION['client']['id'] ."'";
                    }
                    else
                    {
                      echo " placeholder = 'Client'";
                    }
                    echo ' id="clientInput">';
                ?>
                <div class="resetClient"><a href="<?= DIRECTORY_NAME ?>/home/resetClient">X</a></div>
                <div class="clientMainDiv">
                  <div class="close">x</div>
                </div>
              </li>
              <!-- <li class="btn-navbar-menu"><a href="javascript:void(0)" class="sb-toggle-left"><i class="zmdi zmdi-menu"></i></a></li> -->
            </ul>
          </div>
          <!-- navbar-collapse collapse -->
          <a href="javascript:void(0)" class="sb-toggle-left btn-navbar-menu">
            <i class="zmdi zmdi-menu"></i>
          </a>
        </div>
        <!-- container -->
      </nav>