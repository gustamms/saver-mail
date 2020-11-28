<?php 
class Operador{

	private $Conexao;
	private $mensagemErro;

	private $idOperador;
	private $dataCadastro;
	private $desativado;
	private $nome;
	private $usuario;
	private $senha;
	
	public function __construct($id = null){
		$this->setIdOperador($id);
	}

	public function getConexao(){
	  return $this->Conexao;
	}
	public function setConexao($Conexao){
	  $this->Conexao = $Conexao;
	  return $this;
	}
	
	public function getMensagemErro(){
	  return $this->mensagemErro;
	}
	public function setMensagemErro($mensagemErro){
	  $this->mensagemErro = $mensagemErro;
	  return $this;
	}

	public function getIdOperador(){
	  return $this->idOperador;
	}
	public function setIdOperador($idOperador){
	  $this->idOperador = $idOperador;
	  return $this;
	}
	
	public function getDataCadastro(){
	  return $this->dataCadastro;
	}
	public function setDataCadastro($dataCadastro){
	  $this->dataCadastro = $dataCadastro;
	  return $this;
	}
	
	public function getDesativado(){
	  return $this->desativado;
	}
	public function setDesativado($desativado){
	  $this->desativado = $desativado;
	  return $this;
	}
	
	public function getNome(){
	  return $this->nome;
	}
	public function setNome($nome){
	  $this->nome = $nome;
	  return $this;
	}
	
	public function getUsuario(){
	  return $this->usuario;
	}
	public function setUsuario($usuario){
	  $this->usuario = $usuario;
	  return $this;
	}
	
	public function getSenha(){
	  return $this->senha;
	}
	public function setSenha($senha){
	  $this->senha = $senha;
	  return $this;
	}	

	/**
	 * [criar CRUD Insert]
	 * @return [boolean]
	 */
	public function criar(){
		$query = sprintf("INSERT INTO operador(
												dataCadastro,
												desativado,
												nome,
												usuario,
												senha
											) VALUES (
												%d,
												'%s',
												'%s',
												'%s',
												'%s',
												'%s'
											)",
											$this->getConexao()->escape($this->getDataCadastro()),
											$this->getConexao()->escape($this->getDesativado()),
											$this->getConexao()->escape($this->getNome()),
											$this->getConexao()->escape($this->getUsuario()),
											$this->getConexao()->escape($this->getSenha()));
		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$this->setIdOperador($this->getConexao()->getInsertId());
			return true;

		}catch(Exception $e){
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}
	
	/**
	 * [recuperar recupera dados do operador]
	 * @return [boolean]
	 */
	public function recuperar(){
		$query = sprintf("SELECT
												idOperador,
												dataCadastro,
												desativado,
												nome,
												usuario,
												senha
											FROM
												operador
											WHERE
												idOperador = %d",
												$this->getConexao()->escape($this->getIdOperador()));
		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
				
			if(mysqli_num_rows($result) <= 0){
				throw new Exception("Operador não econtrado");
			}

			$row = mysqli_fetch_assoc($result);
			$this
				->setIdOperador($row["idOperador"])
				->setDataCadastro($row["dataCadastro"])
				->setDesativado($row["desativado"])
				->setNome($row["nome"])
				->setUsuario($row["usuario"])
				->setSenha($row["senha"]);
			mysqli_free_result($result);
			return true;

		}catch(Exception $e){
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}

	/**
	 * [selecionar CRUD Select]
	 * @param [string] $query [query à acrescentar]
	 * @return [array]
	 */
	public function selecionar($query = null){
		$query = sprintf("SELECT * FROM operador %s", (!empty($query) ? $query : null));

		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$data = array();

			if(mysqli_num_rows($result) > 0){
				foreach ($result as $row){
					
					$obj = new Operador();
					$obj
						->setIdOperador($row["idOperador"])
						->setDataCadastro($row["dataCadastro"])
						->setDesativado($row["desativado"])
						->setNome($row["nome"])
						->setUsuario($row["usuario"])
						->setSenha($row["senha"]);
					$data[] = $obj;
				}
				mysqli_free_result($result);
			}
			return $data;

		}catch(Exception $e){
			$this->setMensagemErro($e->getMessage());
			return [];
		}
	}

	/**
	 * [alterar CRUD Update]
	 * @return [boolean]
	 */
	public function alterar(){
		$set = array();

		if(!empty($this->getDesativado())){
			$set[] = sprintf("desativado = '%s'", $this->getConexao()->escape($this->getDesativado()));
		}

		if(!empty($this->getNome())){
			$set[] = sprintf("nome = '%s'", $this->getConexao()->escape($this->getNome()));
		}

		if(!empty($this->getUsuario())){
			$set[] = sprintf("usuario = '%s'", $this->getConexao()->escape($this->getUsuario()));
		}

		if(!empty($this->getSenha())){
			$set[] = sprintf("senha = '%s'", $this->getConexao()->escape($this->getSenha()));
		}

		try{
			if(empty($set)){
				throw new Exception("Não foi possível alterar");
			}

			$query = sprintf("UPDATE operador SET %s WHERE idOperador = %d", implode($set, ","), $this->getConexao()->escape($this->getIdOperador()));
			$result = mysqli_query($this->getConexao()->getLink(), $query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			return true;

		}catch(Exception $e){
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}
}