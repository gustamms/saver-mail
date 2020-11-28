<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";

$data = [];
$db = (new DbConnection())->conn();

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

	if(empty($post->cod)){
		throw new Exception("Não foi possível realizar a operação desejada");
	}
	if(empty($post->descricao)){
		throw new Exception("Digite uma descrição");
	}
	if(empty($post->corpo)){
		throw new Exception("Informe o corpo da campanha");
	}
	if(count($post->contatos) <= 0){
		throw new Exception("Informe ao menos um contato");
	}

	$campanha = new Campanha($post->cod);
	$campanha->setConexao($db);
	if(!$campanha->recuperar()){
		throw new Exception($campanha->getMensagemErro());
	}

	if($campanha->getStatus() != "A"){
		throw new Exception("Não é possível alterar uma campanha já enviada ou desativada");
	}

	$campanha
		->setDescricao(htmlentities($post->descricao))
		->setIdUsuario(UsuarioLogado::getIdUsuario())
		->setCorpo(htmlentities($post->corpo));

	$db->startCommit();

	if(!$campanha->alterar()){
		$db->rollback();
		throw new Exception($campanha->getMensagemErro());
	}

	if(!$campanha->alteraContatos($post->contatos)){
		$db->rollback();
		throw new Exception($campanha->getMensagemErro());
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