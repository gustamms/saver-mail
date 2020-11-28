<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class EnvioEmail {

	private $assunto;
	private $emailDestinatario;
	private $nomeDestinatario;
	private $corpoEmail;
	private $mensagemErro;

	function __construct(){

	}

	public function getAssunto(){
	  return $this->assunto;
	}
	public function setAssunto($assunto){
		$this->assunto = $assunto;
	  return $this;
	}
	public function getEmailDestinatario(){
	  return $this->emailDestinatario;
	}
	public function setEmailDestinatario($emailDestinatario){
		$this->emailDestinatario = $emailDestinatario;
	  return $this;
	}
	public function getNomeDestinatario(){
	  return $this->nomeDestinatario;
	}
	public function setNomeDestinatario($nomeDestinatario){
		$this->nomeDestinatario = $nomeDestinatario;
	  return $this;
	}
	public function getCorpoEmail(){
	  return $this->corpoEmail;
	}
	public function setCorpoEmail($corpoEmail){
		$this->corpoEmail = $corpoEmail;
	  return $this;
	}	
	public function getMensagemErro(){
	  return $this->mensagemErro;
	}
	public function setMensagemErro($mensagemErro){
		$this->mensagemErro = $mensagemErro;
	  return $this;
	}

	public function enviar(){
		$mail = new PHPMailer();
		$mail->SMTPDebug = false;
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com;smtp2.gmail.com';
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls';
		$mail->Username = 'gustavomendes.com@gmail.com';
		$mail->Password = 'secret**';
		$mail->Port = 587;

		$mail->setFrom('savermail@savermail.com.br', 'SaverMail');
		$mail->addAddress($this->getEmailDestinatario(), $this->getNomeDestinatario());
		$mail->isHTML(true);
		$mail->Subject = $this->getAssunto();
		$mail->Body    = html_entity_decode($this->getCorpoEmail());
		$mail->AltBody = 'Para visualizar essa mensagem acesse http://site.com.br/mail';


		try {
			if(!$mail->send()) {
		    throw new Exception($mail->ErrorInfo);
			} 

			return true;
		} catch (Exception $e) {
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}
}
?>