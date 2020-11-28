<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";

$db = (new DbConnection())->conn();

try {

	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	UsuarioLogado::setConexao($db);

	if(UsuarioLogado::recuperar()){
		UsuarioLogado::deslogar();
	}

	$response = [
		"success" => true,
		"urlLogout" => Dominio::get()
	];

}catch(Exception $e){
	
	$response = ["success" => false];

}finally{
	if($db->getLink()){
		$db->close();
	}

	echo json_encode($response);
}