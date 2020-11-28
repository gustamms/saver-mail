<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";

$data = [];
$db = (new DbConnection())->conn();

try {

	if(!isset($_POST['post']) || empty($_POST['post'])){
		throw new Exception("Não foi possível realizar a operação desejada");
	}

	$post = json_decode($_POST["post"]);

	if(empty($post->email)){
		throw new Exception("Digite um e-mail");
	}
	if(empty($post->senha)){
		throw new Exception("Digite uma senha");
	}

	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	UsuarioLogado::setConexao($db);

	if(!UsuarioLogado::logar($post->email, $post->senha)){
		throw new Exception(UsuarioLogado::getMensagemErro());		
	}

	if(!UsuarioLogado::recuperar()){
		throw new Exception(UsuarioLogado::getMensagemErro());		
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