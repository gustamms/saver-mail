<?php

/**
 * 
 */
class Contato {
	private $conexao;
	private $mensagemErro;

	private $idContato;
	private $dataCadastro;
	private $nome;
	private $email;
	private $idUsuarioRespCadastrar;

	function __construct($id = null){
		$this->setIdContato($id);
	}

	public function getConexao(){
	  return $this->conexao;
	}
	public function setConexao($conexao){
		$this->conexao = $conexao;
	  return $this;
	}
	public function getMensagemErro(){
	  return $this->mensagemErro;
	}
	public function setMensagemErro($mensagemErro){
		$this->mensagemErro = $mensagemErro;
	  return $this;
	}
	public function getIdContato(){
	  return $this->idContato;
	}
	public function setIdContato($idContato){
		$this->idContato = $idContato;
	  return $this;
	}
	public function getDataCadastro(){
	  return $this->dataCadastro;
	}
	public function setDataCadastro($dataCadastro){
		$this->dataCadastro = $dataCadastro;
	  return $this;
	}
	public function getNome(){
	  return $this->nome;
	}
	public function setNome($nome){
		$this->nome = $nome;
	  return $this;
	}
	public function getEmail(){
	  return $this->email;
	}
	public function setEmail($email){
		$this->email = $email;
	  return $this;
	}
	public function getIdUsuarioRespCadastrar(){
	  return $this->idUsuarioRespCadastrar;
	}
	public function setIdUsuarioRespCadastrar($idUsuarioRespCadastrar){
		$this->idUsuarioRespCadastrar = $idUsuarioRespCadastrar;
	  return $this;
	}

	/**
	 * [criar CRUD Insert]
	 * @return [boolean]
	 */
	public function criar(){
		$query = sprintf("INSERT INTO contato(
												dataCadastro,
												nome,
												email,
												idUsuarioRespCadastrar
											) VALUES (
												'%s',
												'%s',
												'%s',
												%d
											)",
											$this->getConexao()->escape($this->getDataCadastro()),
											$this->getConexao()->escape($this->getNome()),
											$this->getConexao()->escape($this->getEmail()),
											$this->getConexao()->escape($this->getIdUsuarioRespCadastrar()));
		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$this->setIdContato($this->getConexao()->getInsertId());
			return true;

		}catch(Exception $e){
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}
	
	/**
	 * [recuperar recupera dados do contato]
	 * @return [boolean]
	 */
	public function recuperar(){
		$query = sprintf("SELECT
												*
											FROM
												contato
											WHERE
												idContato = %d",
												$this->getConexao()->escape($this->getIdContato()));
		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
				
			if(mysqli_num_rows($result) <= 0){
				throw new Exception("Contato não econtrado");
			}

			$row = mysqli_fetch_assoc($result);
			$this
				->setIdContato($row["idContato"])
				->setDataCadastro($row["dataCadastro"])
				->setNome($row["nome"])
				->setEmail($row["email"])
				->setIdUsuarioRespCadastrar($row["idUsuarioRespCadastrar"]);
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
		$query = sprintf("SELECT * FROM contato %s", (!empty($query) ? $query : null));

		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$data = array();

			if(mysqli_num_rows($result) > 0){
				foreach ($result as $row){
					
					$obj = new Contato();
					$obj
						->setIdContato($row["idContato"])
						->setDataCadastro($row["dataCadastro"])
						->setNome($row["nome"])
						->setEmail($row["email"])
						->setIdUsuarioRespCadastrar($row["idUsuarioRespCadastrar"]);
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

		if(!empty($this->getDataCadastro())){
			$set[] = sprintf("dataCadastro = '%s'", $this->getConexao()->escape($this->getDataCadastro()));
		}

		if(!empty($this->getNome())){
			$set[] = sprintf("nome = '%s'", $this->getConexao()->escape($this->getNome()));
		}

		if(!empty($this->getEmail())){
			$set[] = sprintf("email = '%s'", $this->getConexao()->escape($this->getEmail()));
		}

		if(!empty($this->getIdUsuarioRespCadastrar())){
			$set[] = sprintf("idUsuarioRespCadastrar = '%s'", $this->getConexao()->escape($this->getIdUsuarioRespCadastrar()));
		}

		try{
			if(empty($set)){
				throw new Exception("Não foi possível alterar");
			}

			$query = sprintf("UPDATE contato SET %s WHERE idContato = %d", implode($set, ","), $this->getConexao()->escape($this->getIdContato()));
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
?>