<?php
require '../../lib/autoload.php';

error_reporting(0);
$row = [];

$db = (new DbConnection())->conn();

if(!$db->conectar()){
	var_dump("Não foi possível se conectar ao banco de dados");
}

$usuario = new Usuario();
$usuario->setConexao($db);
$dados = $usuario->selecionar(sprintf("WHERE linkConfirmacao = '%s'", $db->escape($_GET['url'])));

if(count($dados) == 0){
	var_dump("Não tem user");
}

foreach ($dados as $row) {
	$idUsuario = $row->getIdUsuario();
}

$usuario->setIdUsuario($idUsuario);
$usuario->recuperar();
$usuario
	->setConfirmouEmail('T')
	->setLinkConfirmacao('NULL');
$usuario->alterar();

// caminho
define(PATH, Dominio::get());
// armazena pagina
define(BODY, str_replace(["{PATH}","{FIRST_PARAM}"], [PATH,Page::$firstParam], ob_get_contents()));
?>
<!DOCTYPE html>
<html>
<head>
	<title>Link <?php echo $_GET['url'] ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?php echo PATH ?>/plugins/semantic-ui/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo PATH ?>/plugins/sweetalert2/sweetalert2.min.css">
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/vue.js"></script>
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/moment.min.js"></script>
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/semantic-ui/semantic.min.js"></script>
	<script type="text/javascript" src="<?php echo PATH ?>/plugins/sweetalert2/sweetalert2.all.min.js"></script>
</head>
<body>
	Confirmado
</body>
</html>