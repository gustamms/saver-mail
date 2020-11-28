<?php

/**
 * 
 */
class EnvioEmails {
	private $Conexao;
	private $mensagemErro;

	private $idEnvioEmails;
	private $dataCadastro;
	private $idUsuario;
	private $idCampanha;
	private $idContato;
	private $status;
	
	function __construct($id = null){
		$this->setIdEnvioEmails($id);
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
	public function getIdEnvioEmails(){
	  return $this->idEnvioEmails;
	}
	public function setIdEnvioEmails($idEnvioEmails){
		$this->idEnvioEmails = $idEnvioEmails;
	  return $this;
	}
	public function getDataCadastro(){
	  return $this->dataCadastro;
	}
	public function setDataCadastro($dataCadastro){
		$this->dataCadastro = $dataCadastro;
	  return $this;
	}
	public function getIdUsuario(){
	  return $this->idUsuario;
	}
	public function setIdUsuario($idUsuario){
		$this->idUsuario = $idUsuario;
	  return $this;
	}
	public function getIdCampanha(){
	  return $this->idCampanha;
	}
	public function setIdCampanha($idCampanha){
		$this->idCampanha = $idCampanha;
	  return $this;
	}
	public function getIdContato(){
	  return $this->idContato;
	}
	public function setIdContato($idContato){
		$this->idContato = $idContato;
	  return $this;
	}
	public function getStatus(){
	  return $this->status;
	}
	public function setStatus($status){
		$this->status = $status;
	  return $this;
	}

	/**
	 * [criar CRUD Insert]
	 * @return [boolean]
	 */
	public function criar(){
		$query = sprintf("INSERT INTO envioemails(
												dataCadastro,
												idUsuario,
												idCampanha,
												idContato,
												status
											) VALUES (
												'%s',
												%d,
												%d,
												%d,
												'%s'
											)",
											$this->getConexao()->escape($this->getDataCadastro()),
											$this->getConexao()->escape($this->getIdUsuario()),
											$this->getConexao()->escape($this->getIdCampanha()),
											$this->getConexao()->escape($this->getIdContato()),
											$this->getConexao()->escape($this->getStatus())
										);
		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$this->setIdEnvioEmails($this->getConexao()->getInsertId());
			return true;

		}catch(Exception $e){
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}
	
	/**
	 * [recuperar Recupera os dados da campanha]
	 * @return [boolean]
	 */
	public function recuperar(){
		$query = sprintf("SELECT
												*
											FROM
												envioemails
											WHERE
												idEnvioEmails = %d",
												$this->getConexao()->escape($this->getIdEnvioEmails()));
		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
				
			if(mysqli_num_rows($result) <= 0){
				throw new Exception("Envio não econtrado");
			}

			$row = mysqli_fetch_assoc($result);
			$this
				->setIdEnvioEmails($row["idEnvioEmails"])
				->setDataCadastro($row["dataCadastro"])
				->setIdUsuario($row["idUsuario"])
				->setIdCampanha($row["idCampanha"])
				->setIdContato($row["idContato"])
				->setStatus($row["status"]);
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
		$query = sprintf("SELECT * FROM envioemails %s", (!empty($query) ? $query : null));

		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$data = array();

			if(mysqli_num_rows($result) > 0){
				foreach ($result as $row){
					
					$obj = new EnvioEmails();
					$obj
						->setIdEnvioEmails($row["idEnvioEmails"])
						->setDataCadastro($row["dataCadastro"])
						->setIdUsuario($row["idUsuario"])
						->setIdCampanha($row["idCampanha"])
						->setIdContato($row["idContato"])
						->setStatus($row["status"]);
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

		if(!empty($this->getIdUsuario())){
			$set[] = sprintf("idUsuario = '%s'", $this->getConexao()->escape($this->getIdUsuario()));
		}

		if(!empty($this->getIdCampanha())){
			$set[] = sprintf("idCampanha = %d", $this->getConexao()->escape($this->getIdCampanha()));
		}

		if(!empty($this->setIdContato())){
			$set[] = sprintf("idContato = %d", $this->getConexao()->escape($this->setIdContato()));
		}

		if(!empty($this->setStatus())){
			$set[] = sprintf("status = '%s'", $this->getConexao()->escape($this->setStatus()));
		}

		try{
			if(empty($set)){
				throw new Exception("Não foi possível alterar");
			}

			$query = sprintf("UPDATE envioemails SET %s WHERE idEnvioEmails = %d", implode($set, ","), $this->getConexao()->escape($this->getIdEnvioEmails()));
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