<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";
// error_reporting(0);

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

	if(empty($post->descricao)){
		throw new Exception("Digite uma descrição");
	}
	if(empty($post->corpo)){
		throw new Exception("Informe o corpo da campanha");
	}
	if(count($post->contatos) <= 0){
		throw new Exception("Informe ao menos um contato");
	}

	$campanha = new Campanha();
	$campanha
		->setConexao($db)
		->setDataCadastro((new DateTime("now", new DateTimeZone('America/Sao_Paulo')))->format("Y-m-d H:i:s"))
		->setDescricao(htmlentities($post->descricao))
		->setIdUsuario(UsuarioLogado::getIdUsuario())
		->setStatus("A")
		->setCorpo(htmlentities($post->corpo));

	$db->startCommit();

	if(!$campanha->criar()){
		$db->rollback();
		throw new Exception($campanha->getMensagemErro());
	}

	if(!$campanha->vinculaContatos($post->contatos)){
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