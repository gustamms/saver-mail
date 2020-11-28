<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";

$data = [];
$db = (new DbConnection())->conn();

try {
	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	UsuarioLogado::setConexao($db);

	if(!UsuarioLogado::recuperar()){
	  throw new Exception("NOT_RECOVER_USER");
	}

	$campanha = new Campanha();
	$campanha
		->setConexao($db)
		->setIdUsuario(UsuarioLogado::getIdUsuario());

	$data = $campanha->qtdVisualizacoes();

	$response = [
		"success" => true, 
		"visualizacoes" => $data
	];

} catch (Exception $e) {
	$response = [
		"success" => false, 
		"visualizacoes" => $data, 
		"error" => $e->getMessage()
	];
}finally{
	if($db->getLink()){
		$db->close();
	}
	echo json_encode($response);
}