<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";

$data = [];
$db = (new DbConnection())->conn();

try {

	if(empty($_POST['post'])){
		throw new Exception("Preencher corretamente os campos de usuário e senha");
	}

	parse_str($_POST['post'],$post);

	if(empty($post['usuario'])){
		throw new Exception("Digite o usuário");
	}

	if(empty($post['senha'])){
		throw new Exception("Digite a senha");
	}

	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	OperadorLogado::setConexao($db);

	if(!OperadorLogado::logar($post['usuario'], $post['senha'])){
		throw new Exception(OperadorLogado::getMensagemErro());		
	}

	if(!OperadorLogado::recuperar()){
		throw new Exception(OperadorLogado::getMensagemErro());		
	}	

	$response = ["success" => true];

} catch (Exception $e) {
	$response = ["success" => false, "error" => $e->getMessage()];
}finally{
	if($db->getLink()){
		$db->close();
	}
	echo json_encode($response);
}