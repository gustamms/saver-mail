<?php
class OperadorLogado{

	const PREFIXO = "linop";
	const NM_SESSAO = "cod";
	const NM_COOKIE_USUARIO = "user";
	const NM_COOKIE_SENHA = "token";
	const LEMBRAR_COOKIE = 1;

	static private $Conexao;
	static private $mensagemErro;

	static private $idOperador;
	static private $nome;
	static private $usuario;
	static private $senha;

	static public function getConexao(){
	  return self::$Conexao;
	}
	static public function setConexao($Conexao){
	  self::$Conexao = $Conexao;
	}

	static public function getMensagemErro(){
	  return self::$mensagemErro;
	}
	static public function setMensagemErro($mensagemErro){
	  self::$mensagemErro = $mensagemErro;
	}

	static public function getIdOperador(){
	  return self::$idOperador;
	}
	static public function setIdOperador($idOperador){
	  self::$idOperador = $idOperador;
	}
	
	static public function getNome(){
	  return self::$nome;
	}
	static public function setNome($nome){
	  self::$nome = $nome;
	}
	
	static public function getUsuario(){
	  return self::$usuario;
	}
	static public function setUsuario($usuario){
	  self::$usuario = $usuario;
	}
	
	static public function getSenha(){
	  return self::$senha;
	}
	static public function setSenha($senha){
	  self::$senha = $senha;
	}
	
	/**
	 * [logar Faz login do operador]
	 * @param [string] $usuario [usuário do operador]
	 * @param [string] $senha [senha do operador]
	 * @return [boolean]
	 */
	static public function logar($usuario, $senha){

		self::setUsuario($usuario);
		self::setSenha($senha);
		return self::validar();
	}

	/**
	 * [validar Valida se existe um operador com usuário e senha informado]
	 * @return [boolean]
	 */
	static private function validar(){

		$operador = new Operador();
		$operador->setConexao(self::getConexao());
		
		try{
			$result = $operador->selecionar(sprintf("WHERE usuario = '%s'", self::getConexao()->escape(self::getUsuario())));

			if(!$result){
				throw new Exception("Este operador não existe!");
			}

			$row = array_shift($result);

			if(!Bcrypt::check(self::getSenha(), $row->getSenha())){
				throw new Exception("Senha inválida!");				
			}

			if($row->getDesativado() == "T"){
				throw new Exception("Operador desativado!");					
			}

			self::setIdOperador($row->getIdOperador());
			self::criarSessao();
			self::criarCookie();
			return true;
				
		} catch (Exception $e) {
			self::setMensagemErro($e->getMessage());
			return false;
		}
	}

	/**
	 * [criarSessao Cria sessão com id do operador]
	 * @return [void]
	 */
	static private function criarSessao(){
		if(!isset($_SESSION)){
			if(Server::on()){
				ini_set('session.cookie_domain', '.grsuporte.com.br');
			}
			session_start();
		}
		$_SESSION[self::PREFIXO.self::NM_SESSAO] = self::getIdOperador();
	}

	/**
	 * [obterSessao Recupera sessão com id do operador]
	 * @return [boolean]
	 */
	static private function obterSessao(){
		
		if(!isset($_SESSION)){
			if(Server::on()){
				ini_set('session.cookie_domain', '.grsuporte.com.br');
			}
			session_start();
		}
		if(isset($_SESSION[self::PREFIXO.self::NM_SESSAO]) && !empty($_SESSION[self::PREFIXO.self::NM_SESSAO])){
			self::setIdOperador($_SESSION[self::PREFIXO.self::NM_SESSAO]);
			return true;
		}
		return false;
	}

	/**
	 * [limparSessao Limpa sessões]
	 * @return [void]
	 */
	static private function limparSessao(){

		foreach($_SESSION as $key => $value){
			if(substr($key, 0, strlen(self::PREFIXO)) == self::PREFIXO){
				unset($_SESSION[$key]);
			}
		}
		
		if(count($_SESSION) == 0){				
			
			session_destroy();			
			
			if(isset($_COOKIE['PHPSESSID'])){
				setcookie('PHPSESSID', false, (time() - 3600));
				unset($_COOKIE['PHPSESSID']);
			}
		}
	}

	/**
	 * [criarCookie Cria cookie com usuário e senha do operador]
	 * @return [void]
	 */
	static private function criarCookie(){

		$tempo = strtotime(sprintf('+%d day', self::LEMBRAR_COOKIE), time());
		$usuario = base64_encode(rand(1,9).base64_encode(self::getUsuario()));
		$senha = base64_encode(rand(1,9).base64_encode(self::getSenha()));
		setCookie(self::PREFIXO.self::NM_COOKIE_USUARIO, $usuario, $tempo, "/");
		setCookie(self::PREFIXO.self::NM_COOKIE_SENHA, $senha, $tempo, "/");
	}

	/**
	 * [obterCookie Recupera cookie com número de telefone]
	 * @return [boolean]
	 */
	static private function obterCookie(){

		if(isset($_COOKIE[self::PREFIXO.self::NM_COOKIE_USUARIO]) && isset($_COOKIE[self::PREFIXO.self::NM_COOKIE_SENHA])){
			$usuario = base64_decode(substr(base64_decode($_COOKIE[self::PREFIXO.self::NM_COOKIE_USUARIO]), 1));
			$senha = base64_decode(substr(base64_decode($_COOKIE[self::PREFIXO.self::NM_COOKIE_SENHA]), 1));
			return self::logar($usuario, $senha);
		}
		return false;
	}

	/**
	 * [limparCookie Limpa cookies]
	 * @return [void]
	 */
	static private function limparCookie(){

		if(isset($_COOKIE[self::PREFIXO.self::NM_COOKIE_USUARIO])){
			setcookie(self::PREFIXO.self::NM_COOKIE_USUARIO, false, (time() - 3600), "/");
			unset($_COOKIE[self::PREFIXO.self::NM_COOKIE_USUARIO]);
		}
		if(isset($_COOKIE[self::PREFIXO.self::NM_COOKIE_SENHA])){
			setcookie(self::PREFIXO.self::NM_COOKIE_SENHA, false, (time() - 3600), "/");
			unset($_COOKIE[self::PREFIXO.self::NM_COOKIE_SENHA]);
		}
	}

	/**
	 * [recuperar recupera sessão]
	 * @param [boolean] $verificarCookie [verifica cookie?]
	 * @return [boolean]
	 * 
	 */
	static public function recuperar($verificarCookie = true){

		try {

			if(!self::obterSessao()){
				if($verificarCookie && !self::obterCookie()){
					throw new Exception("Não foi possível recuperar operador logado");
				}
				throw new Exception("Não foi possível recuperar operador logado");
			}

			$operador = new Operador(self::getIdOperador());
			$operador->setConexao(self::getConexao());

			if(!$operador->recuperar()){
				throw new Exception($operador->getMensagemErro());
			}

			self::setNome($operador->getNome());
			self::setUsuario($operador->getUsuario());

			return true;
			
		} catch (Exception $e) {
			self::setMensagemErro($e->getMessage());
			return false;
		}
	}

	/**
	 * [deslogar Desloga operador]
	 * @param  boolean $limparCookie [limpa os cookies?]
	 * @return [boolean]
	 */
	static public function deslogar($limparCookie = true){

		try{

			if(!self::obterSessao()){
				throw new Exception();
			}

			// sessões
			self::limparSessao();
			
			// cookies
			if($limparCookie){
				self::limparCookie();
			}

			return !self::recuperar();

		} catch (Exception $e) {
			self::setMensagemErro($e->getMessage());
			return false;
		}
	}
}