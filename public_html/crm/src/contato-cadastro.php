<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";
// error_reporting(0);

$data = [];
$db = (new DbConnection())->conn();
$response = ["success" => false];

try {
	UsuarioLogado::setConexao($db);

	if(!UsuarioLogado::recuperar()){
		throw new Exception("NOT_RECOVER_USER");
	}

	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	if(!isset($_POST['post']) || empty($_POST['post'])){
		throw new Exception("Não foi possível realizar a operação desejada");
	}

	$post = json_decode($_POST["post"]);

	if(empty($post->nome)){
		throw new Exception("Digite um nome");
	}
	if(strlen(Tools::nameFormat($post->nome)) < 2){
		throw new Exception("O nome deve ter no mínimo 2 letras");
	}
	if(empty($post->email)){
		throw new Exception("Digite um email");
	}
	if(!filter_var($post->email, FILTER_VALIDATE_EMAIL)){
		throw new Exception("Informe um e-mail válido");
	}

	$contato = new Contato();
	$contato
		->setConexao($db)
		->setDataCadastro((new DateTime("now", new DateTimeZone('America/Sao_Paulo')))->format("Y-m-d H:i:s"))
		->setNome(Tools::nameFormat($post->nome))
		->setEmail($post->email)
		->setIdUsuarioRespCadastrar(UsuarioLogado::getIdUsuario());

	$db->startCommit();

	if(!$contato->criar()){
		$db->rollback();
		throw new Exception($contato->getMensagemErro());
	}

	$db->commit();
	$response = ["success" => true];

} catch (Exception $e) {
	$response = ["success" => false, "error" => $e->getMessage()];
}finally{
	if($db->getLink()){
		$db->close();
	}
	echo json_encode($response);
}