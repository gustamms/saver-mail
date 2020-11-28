<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '../plugins/PHPMailer/src/Exception.php';
require '../plugins/PHPMailer/src/PHPMailer.php';
require '../plugins/PHPMailer/src/SMTP.php';

$contatosDados = [];
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

	$campanha = new Campanha($_POST['post']);
	$campanha->setConexao($db);

	if(!$campanha->recuperar()){
		throw new Exception($campanha->getMensagemErro());
	}

	$contatos = new CampanhaContatos();
	$contatos->setConexao($db);
	$dados = $contatos->selecionar(sprintf("WHERE idCampanha = %d", $db->escape($_POST['post'])));

	foreach ($dados as $row) {
		$contato = new Contato($row->getIdContato());
		$contato->setConexao($db);
		if(!$contato->recuperar()){
			throw new Exception($contato->getMensagemErro());
		}

		$temp["cod"] = $row->getIdCampanhaContatos();
		$temp["nome"] = $contato->getNome();
		$temp["email"] = $contato->getEmail();
		$temp["hash"] = $row->getHashVisualizado();
		$contatosDados[] = $temp;
	}

	$db->startCommit();

	foreach ($contatosDados as $row) {
		$envio = new EnvioEmail();
		$envio
			->setAssunto($campanha->getDescricao())
			->setEmailDestinatario($row["email"])
			->setNomeDestinatario($row["nome"])
			->setCorpoEmail($campanha->getCorpo()."<img alt='' style='display:none;' src='https://savermail.000webhostapp.com/visualiza-campanha/?hash=".$row["hash"]."' />");

		if(!$envio->enviar()){		
			throw new Exception("Ops... Algo deu erro, atualize e tente novamente");
		}

		// Deixa como enviada a campanha para o contato
		$enviado = new CampanhaContatos($row["cod"]);
		$enviado
			->setConexao($db)
			->setEnviado("S");
		if(!$enviado->alterar()){
			$db->rollback();
			throw new Exception($enviado->getMensagemErro());
		}
	}

	// Define como campanha enviada
	$campanha->setStatus("E");
	if(!$campanha->alterar()){
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