<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '../plugins/PHPMailer/src/Exception.php';
require '../plugins/PHPMailer/src/PHPMailer.php';
require '../plugins/PHPMailer/src/SMTP.php';
// error_reporting(0);

$data = [];
$hashLink = null;
$db = (new DbConnection())->conn();

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Username = 'gustavomendes.com@gmail.com';
$mail->Password = 'secret**';
$mail->Port = 587;

try {
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
	if(empty($post->email)){
		throw new Exception("Digite um e-mail");
	}
	if(empty($post->senha)){
		throw new Exception("Digite uma senha");
	}

	$usuario = new Usuario();
	$usuario->setConexao($db);
	$dados = $usuario->selecionar(sprintf("WHERE email = '%s'", $db->escape($post->email)));
	if(count($dados) >= 1){
		throw new Exception("E-mail já cadastrado");
	}

	//Hash de senha
	$hash = new Bcrypt();
	$post->senha = $hash->hash($post->senha);
	$hashLink = md5($post->email, false);

	$usuario
		->setDataCadastro((new DateTime("now", new DateTimeZone('America/Sao_Paulo')))->format("Y-m-d H:i:s"))
		->setNome($post->nome)
		->setEmail($post->email)
		->setSenha($post->senha)
		->setConfirmouEmail('F')
		->setLinkConfirmacao($hashLink);

	$db->startCommit();

	if(!$usuario->criar()){
		$db->rollback();
		throw new Exception($usuario->getMensagemErro());
	}

	$mail->setFrom('gustavomendes.com@gmail.com', 'SaverMail');
	$mail->addAddress($post->email);
	$mail->isHTML(true);
	$mail->Subject = 'Confirmação de conta';
	$mail->Body    ='Clique no link para confirmar a conta <a href="'.Dominio::get('confirmacao').'/confirmacao/'.$hashLink.'" class="ui primary button">Confirmar</a>';

	if(!$mail->send()) {
    throw new Exception($mail->ErrorInfo);
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