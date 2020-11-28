<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";

$db = (new DbConnection())->conn();

try {
	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	OperadorLogado::setConexao($db);

	if(!OperadorLogado::recuperar()){
		throw new Exception("NOT_RECOVER_OPERATOR");
	}
	
	$response = [
		"success" => true,
		"operador" => [
			"cod" => OperadorLogado::getIdOperador(),
			"nome" => OperadorLogado::getNome()
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