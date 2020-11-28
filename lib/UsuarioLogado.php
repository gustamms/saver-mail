<?php 
/**
 * 
 */
class UsuarioLogado {
	const PREFIXO = "linus";
	const NM_SESSAO = "cod";
	const NM_COOKIE_USUARIO = "user";
	const NM_COOKIE_SENHA = "token";
	const LEMBRAR_COOKIE = 1;

	static private $conexao;
	static private $mensagemErro;

	static private $idUsuario;
	static private $dataCadastro;
	static private $nome;
	static private $email;
	static private $senha;

	public function getConexao(){
	  return self::$conexao;
	}
	public function setConexao($conexao){
		self::$conexao = $conexao;
	}
	public function getMensagemErro(){
	  return self::$mensagemErro;
	}
	public function setMensagemErro($mensagemErro){
		self::$mensagemErro = $mensagemErro;
	}
	public function getIdUsuario(){
	  return self::$idUsuario;
	}
	public function setIdUsuario($idUsuario){
		self::$idUsuario = $idUsuario;
	}
	public function getDataCadastro(){
	  return self::$dataCadastro;
	}
	public function setDataCadastro($dataCadastro){
		self::$dataCadastro = $dataCadastro;
	}
	public function getNome(){
	  return self::$nome;
	}
	public function setNome($nome){
		self::$nome = $nome;
	}
	public function getEmail(){
	  return self::$email;
	}
	public function setEmail($email){
		self::$email = $email;
	}
	public function getSenha(){
	  return self::$senha;
	}
	public function setSenha($senha){
		self::$senha = $senha;
	}

	/**
	 * [logar Faz login do usuário]
	 * @param [string] $usuario [e-mail do operador]
	 * @param [string] $senha [senha do operador]
	 * @return [boolean]
	 */
	static public function logar($usuario, $senha){

		self::setEmail($usuario);
		self::setSenha($senha);
		return self::validar();
	}

	/**
	 * [validar Valida se existe um usuário com e-mail e senha informado]
	 * @return [boolean]
	 */
	static private function validar(){

		$usuario = new Usuario();
		$usuario->setConexao(self::getConexao());
		
		try{
			$result = $usuario->selecionar(sprintf("WHERE email = '%s'", self::getConexao()->escape(self::getEmail())));

			if(!$result){
				throw new Exception("Este usuario não existe!");
			}

			$row = array_shift($result);

			if($row->getConfirmouEmail() == 'F'){
				throw new Exception("Confirmar e-mail");
			}

			if(!Bcrypt::check(self::getSenha(), $row->getSenha())){
				throw new Exception("Senha inválida!");				
			}

			self::setIdUsuario($row->getIdUsuario());
			self::criarSessao();
			self::criarCookie();
			return true;
				
		} catch (Exception $e) {
			self::setMensagemErro($e->getMessage());
			return false;
		}
	}

		/**
	 * [criarSessao Cria sessão com id do usuário]
	 * @return [void]
	 */
	static private function criarSessao(){
		if(!isset($_SESSION)){
			if(Server::on()){
				ini_set('session.cookie_domain', '.savermail.000webhostapp.com');
			}
			session_start();
		}
		$_SESSION[self::PREFIXO.self::NM_SESSAO] = self::getIdUsuario();
	}

	/**
	 * [obterSessao Recupera sessão com id do usuário]
	 * @return [boolean]
	 */
	static private function obterSessao(){
		
		if(!isset($_SESSION)){
			if(Server::on()){
				ini_set('session.cookie_domain', '.savermail.000webhostapp.com');
			}
			session_start();
		}
		if(isset($_SESSION[self::PREFIXO.self::NM_SESSAO]) && !empty($_SESSION[self::PREFIXO.self::NM_SESSAO])){
			self::setIdUsuario($_SESSION[self::PREFIXO.self::NM_SESSAO]);
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
		$usuario = base64_encode(rand(1,9).base64_encode(self::getEmail()));
		$senha = base64_encode(rand(1,9).base64_encode(self::getSenha()));
		setCookie(self::PREFIXO.self::NM_COOKIE_USUARIO, $usuario, $tempo, "/");
		setCookie(self::PREFIXO.self::NM_COOKIE_SENHA, $senha, $tempo, "/");
	}

	/**
	 * [obterCookie Recupera cookie com usuário e senha]
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
					throw new Exception("Não foi possível recuperar usuario logado");
				}
				throw new Exception("Não foi possível recuperar usuario logado");
			}

			$usuario = new Usuario(self::getIdUsuario());
			$usuario->setConexao(self::getConexao());

			if(!$usuario->recuperar()){
				throw new Exception($usuario->getMensagemErro());
			}

			self::setNome($usuario->getNome());
			self::setEmail($usuario->getEmail());

			return true;
			
		} catch (Exception $e) {
			self::setMensagemErro($e->getMessage());
			return false;
		}
	}

	/**
	 * [deslogar Desloga usuário]
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
?>