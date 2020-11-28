<?php
require '../../lib/autoload.php';

error_reporting(0);

//paginas
Page::set($_GET['url']);

// verifica logado
require 'src/verifica.php';

// caminho
define(PATH, Dominio::get());

// buffer de saida
ob_start();

// inclui pagina
include_once(Page::getPage());

// armazena pagina
define(BODY, str_replace(["{PATH}","{FIRST_PARAM}"], [PATH,Page::$firstParam], ob_get_contents()));

//fecha buffer
ob_end_clean();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Campanhas de e-mail com saverMail</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="images/favicon.png" />
	<link rel="stylesheet" type="text/css" href="<?php echo PATH ?>/plugins/semantic-ui/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo PATH ?>/plugins/sweetalert2/sweetalert2.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo PATH ?>/css/custom.css">
	<link rel="stylesheet" type="text/css" href="<?php echo PATH ?>/css/header.css">
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/vue.js"></script>
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/moment.min.js"></script>
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/semantic-ui/semantic.min.js"></script>
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/sweetalert2/sweetalert2.all.min.js"></script>
</head>
<body>
	<script type="text/javascript">moment.locale('pt-br');</script>
	<div id="url" data-url="<?php echo PATH ?>"></div>
	<?php echo BODY; ?>
</body>
</html>