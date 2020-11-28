<?php

class CampanhaContatos {
	private $Conexao;
	private $mensagemErro;

	private $idCampanhaContatos;
	private $dataCadastro;
	private $idContato;
	private $idCampanha;
	private $enviado;
	private $visualizado;
	private $hashVisualizado;
	
	function __construct($id = null) {
		$this->setIdCampanhaContatos($id);
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
	public function getIdCampanhaContatos(){
	  return $this->idCampanhaContatos;
	}
	public function setIdCampanhaContatos($idCampanhaContatos){
		$this->idCampanhaContatos = $idCampanhaContatos;
	  return $this;
	}
	public function getDataCadastro(){
	  return $this->dataCadastro;
	}
	public function setDataCadastro($dataCadastro){
		$this->dataCadastro = $dataCadastro;
	  return $this;
	}
	public function getIdContato(){
	  return $this->idContato;
	}
	public function setIdContato($idContato){
		$this->idContato = $idContato;
	  return $this;
	}
	public function getIdCampanha(){
	  return $this->idCampanha;
	}
	public function setIdCampanha($idCampanha){
		$this->idCampanha = $idCampanha;
	  return $this;
	}
	public function getEnviado(){
	  return $this->enviado;
	}
	public function setEnviado($enviado){
		$this->enviado = $enviado;
	  return $this;
	}
	public function getVisualizado(){
	  return $this->visualizado;
	}
	public function setVisualizado($visualizado){
		$this->visualizado = $visualizado;
	  return $this;
	}
	public function getHashVisualizado(){
	  return $this->hashVisualizado;
	}
	public function setHashVisualizado($hashVisualizado){
		$this->hashVisualizado = $hashVisualizado;
	  return $this;
	}

	/**
	 * [criar CRUD Insert]
	 * @return [boolean]
	 */
	public function criar(){
		$query = sprintf("INSERT INTO campanhacontatos(
												dataCadastro,
												idContato,
												idCampanha,
												enviado,
												visualizado,
												hashVisualizado
											) VALUES (
												'%s',
												%d,
												%d,
												'%s',
												'%s',
												'%s'
											)",
											$this->getConexao()->escape($this->getDataCadastro()),
											$this->getConexao()->escape($this->getIdContato()),
											$this->getConexao()->escape($this->getIdCampanha()),
											$this->getConexao()->escape($this->getEnviado()),
											$this->getConexao()->escape($this->getVisualizado()),
											$this->getConexao()->escape($this->getHashVisualizado())
										);
		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$this->setIdCampanhaContatos($this->getConexao()->getInsertId());
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
												campanhacontatos
											WHERE
												idCampanhaContatos = %d",
												$this->getConexao()->escape($this->getIdCampanhaContatos()));
		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
				
			if(mysqli_num_rows($result) <= 0){
				throw new Exception("Campanha não econtrado");
			}

			$row = mysqli_fetch_assoc($result);
			$this
				->setIdCampanhaContatos($row["idCampanhaContatos"])
				->setDataCadastro($row["dataCadastro"])
				->setIdContato($row["idContato"])
				->setIdCampanha($row["idCampanha"])
				->setEnviado($row["enviado"])
				->setVisualizado($row["visualizado"])
				->setHashVisualizado($row["hashVisualizado"]);
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
		$query = sprintf("SELECT * FROM campanhacontatos %s", (!empty($query) ? $query : null));

		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$data = array();

			if(mysqli_num_rows($result) > 0){
				foreach ($result as $row){
					
					$obj = new CampanhaContatos();
					$obj
						->setIdCampanhaContatos($row["idCampanhaContatos"])
						->setDataCadastro($row["dataCadastro"])
						->setIdContato($row["idContato"])
						->setIdCampanha($row["idCampanha"])
						->setEnviado($row["enviado"])
						->setVisualizado($row["visualizado"])
						->setHashVisualizado($row["hashVisualizado"]);
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

		if(!empty($this->getIdContato())){
			$set[] = sprintf("idContato = %d", $this->getConexao()->escape($this->getIdContato()));
		}

		if(!empty($this->getIdCampanha())){
			$set[] = sprintf("idCampanha = %d", $this->getConexao()->escape($this->getIdCampanha()));
		}

		if(!empty($this->getEnviado())){
			$set[] = sprintf("enviado = '%s'", $this->getConexao()->escape($this->getEnviado()));
		}

		if(!empty($this->getVisualizado())){
			$set[] = sprintf("visualizado = '%s'", $this->getConexao()->escape($this->getVisualizado()));
		}

		if(!empty($this->getHashVisualizado())){
			$set[] = sprintf("hashVisualizado = '%s'", $this->getConexao()->escape($this->getHashVisualizado()));
		}

		try{
			if(empty($set)){
				throw new Exception("Não foi possível alterar");
			}

			$query = sprintf("UPDATE campanhacontatos SET %s WHERE idCampanhaContatos = %d", implode($set, ","), $this->getConexao()->escape($this->getIdCampanhaContatos()));
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

	/**
	 * [geraHash Gera hash para confirmação de visualização]
	 * @return [void]
	 */
	public function geraHash($repetida = null){
		$hash = md5($this->getDataCadastro().$this->getIdContato().$this->getIdCampanha().$repetida);

		$dados = $this->selecionar(sprintf("WHERE hashVisualizado = '%s'", $this->getConexao()->escape($hash)));
		if(count($dados) > 0){
			$this->geraHash("saverMail");
		}

		$this->setHashVisualizado($hash);
	}
}