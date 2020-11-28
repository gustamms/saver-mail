<?php
class DbConnection{

	private $tipo = "mysqli";
	private $obj;
	private $mensagemErro;
	private $chavePublica;

	public function getTipo(){
	  return $this->tipo;
	}
	public function setTipo($tipo){
	  $this->tipo = $tipo;
	  return $this;
	}

	public function getObj(){
	  return $this->obj;
	}
	public function setObj($obj){
	  $this->obj = $obj;
	  return $this;
	}
	
	public function getMensagemErro(){
	  return $this->mensagemErro;
	}
	public function setMensagemErro($mensagemErro){
	  $this->mensagemErro = $mensagemErro;
	  return $this;
	}
	
	public function getChavePublica(){
	  return $this->chavePublica;
	}
	public function setChavePublica($chavePublica){
	  $this->chavePublica = $chavePublica;
	  return $this;
	}
					
	public function __construct($tipo = null){
		
		if($tipo && in_array($tipo, ["mysql","mysqli"])){
			$this->setTipo($tipo);
		}
		return $this;
	}
	
	/**
	 * [descobreConexao Descobre se a conexão é na base geral ou em uma base individual]
	 * @return [boolean]
	 */
	private function descobreConexao(){

		try{

			// mysqli ou mysql?
			$classe = $this->getNomeClasseConexao();

			// conexão com a base geral
			$db = new $classe();

			if(!$db->conectar()){
				throw new Exception($db->getConnecterror());
			}

			// armazena objeto
			$this->setObj($db);

			return true;

		}catch(Exception $e){
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}

	/**
	 * [set Armazena chave pública]
	 * @param [string] $chave [Chave pública]
	 * @return [object]
	 */
	public function set($chave){
		$this->setChavePublica($chave);
		return $this;
	}

	/**
	 * [conn Retorna objeto de conexão ou DbConnection]
	 * @return [object] [Objeto mysql_i, mysql ou DbConnection]
	 */
	public function conn(){

		if(!$this->descobreConexao()){
			$obj = new DbConnection();
			$obj->setMensagemErro($this->getMensagemErro());
			return $obj;
		}
		return $this->getObj();
	}

	/**
	 * [getNomeClasseConexao Recupera o nome da classe de conexão (mysql_i ou mysql)]
	 * @return [string] [Nome da classe de conexão]
	 */
	private function getNomeClasseConexao(){
		switch($this->getTipo()){
			case "mysqli" : $classe = "MysqliCustom"; break;
			// default : $classe = "mysql"; break;
		}
		return $classe;
	}

	/**
	 * [conectar Este método existe somente para não dar erro quando o retorno do método conn() for um objeto desta classe e não um objeto de conexão]
	 * @return [boolean]
	 */
	public function conectar(){
		return false;
	}
}