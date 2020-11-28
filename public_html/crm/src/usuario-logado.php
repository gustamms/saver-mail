<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";

$db = (new DbConnection())->conn();

try {
	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	UsuarioLogado::setConexao($db);

	if(!UsuarioLogado::recuperar()){
		throw new Exception("NOT_RECOVER_USER");
	}
	
	$response = [
		"success" => true,
		"usuario" => [
			"cod" => UsuarioLogado::getIdUsuario(),
			"nome" => UsuarioLogado::getNome()
		]
	];
	
} catch (Exception $e) {
	$response = [
		"success" => false, 
		"error" => $e->getMessage()
	];
}finally{
	if($db->getLink()){
		$db->close();
	}
	echo json_encode($response);
}