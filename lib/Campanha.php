<?php

class Campanha {
	private $Conexao;
	private $mensagemErro;

	private $idCampanha;
	private $dataCadastro;
	private $descricao;
	private $idUsuario;
	private $status;
	private $corpo;

	function __construct($id = null){
		$this->setIdCampanha($id);
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
	public function getIdCampanha(){
	  return $this->idCampanha;
	}
	public function setIdCampanha($idCampanha){
		$this->idCampanha = $idCampanha;
	  return $this;
	}
	public function getDataCadastro(){
	  return $this->dataCadastro;
	}
	public function setDataCadastro($dataCadastro){
		$this->dataCadastro = $dataCadastro;
	  return $this;
	}
	public function getDescricao(){
	  return $this->descricao;
	}
	public function setDescricao($descricao){
		$this->descricao = $descricao;
	  return $this;
	}
	public function getIdUsuario(){
	  return $this->idUsuario;
	}
	public function setIdUsuario($idUsuario){
		$this->idUsuario = $idUsuario;
	  return $this;
	}
	public function getStatus(){
	  return $this->status;
	}
	public function setStatus($status){
		$this->status = $status;
	  return $this;
	}
	public function getCorpo(){
	  return $this->corpo;
	}
	public function setCorpo($corpo){
		$this->corpo = $corpo;
	  return $this;
	}

	/**
	 * [criar CRUD Insert]
	 * @return [boolean]
	 */
	public function criar(){
		$query = sprintf("INSERT INTO campanha(
												dataCadastro,
												descricao,
												idUsuario,
												status,
												corpo
											) VALUES (
												'%s',
												'%s',
												%d,
												'%s',
												'%s'
											)",
											$this->getConexao()->escape($this->getDataCadastro()),
											$this->getConexao()->escape($this->getDescricao()),
											$this->getConexao()->escape($this->getIdUsuario()),
											$this->getConexao()->escape($this->getStatus()),
											$this->getConexao()->escape($this->getCorpo())
										);
		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$this->setIdCampanha($this->getConexao()->getInsertId());
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
												campanha
											WHERE
												idCampanha = %d",
												$this->getConexao()->escape($this->getIdCampanha()));
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
				->setIdCampanha($row["idCampanha"])
				->setDataCadastro($row["dataCadastro"])
				->setDescricao($row["descricao"])
				->setIdUsuario($row["idUsuario"])
				->setStatus($row["status"])
				->setCorpo($row["corpo"]);
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
		$query = sprintf("SELECT * FROM campanha %s", (!empty($query) ? $query : null));

		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$data = array();

			if(mysqli_num_rows($result) > 0){
				foreach ($result as $row){
					
					$obj = new Campanha();
					$obj
						->setIdCampanha($row["idCampanha"])
						->setDataCadastro($row["dataCadastro"])
						->setDescricao($row["descricao"])
						->setIdUsuario($row["idUsuario"])
						->setStatus($row["status"])
						->setCorpo($row["corpo"]);
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

		if(!empty($this->getDescricao())){
			$set[] = sprintf("descricao = '%s'", $this->getConexao()->escape($this->getDescricao()));
		}

		if(!empty($this->getIdUsuario())){
			$set[] = sprintf("idUsuario = %d", $this->getConexao()->escape($this->getIdUsuario()));
		}

		if(!empty($this->getStatus())){
			$set[] = sprintf("status = '%s'", $this->getConexao()->escape($this->getStatus()));
		}

		if(!empty($this->getCorpo())){
			$set[] = sprintf("corpo = '%s'", $this->getConexao()->escape($this->getCorpo()));
		}

		try{
			if(empty($set)){
				throw new Exception("Não foi possível alterar");
			}

			$query = sprintf("UPDATE campanha SET %s WHERE idCampanha = %d", implode($set, ","), $this->getConexao()->escape($this->getIdCampanha()));
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
	 * [qtdVisualizacao Quantidade de pessoas que visualizaram a campanha]
	 * @param [string] $campanha [Id da campanha]
	 * @return [int]
	 */
	public function qtdVisualizacao($campanha){
		try {

			$campanhaContato = new CampanhaContatos();
			$campanhaContato->setConexao($this->getConexao());
			$dados = $campanhaContato->selecionar(sprintf("WHERE idCampanha = %d AND visualizado = 'S'", $campanhaContato->getConexao()->escape($campanha)));

			return count($dados);

		} catch (Exception $e) {
			$this->setMensagemErro($e->getMessage());
			return 0;
		}
	}

	/**
	 * [qtdContatos Quantidade de pessoas vinculadas na campanha]
	 * @param [string] $campanha [Id da campanha]
	 * @return [int]
	 */
	public function qtdContatos($campanha){
		try {
			
			$campanhaContato = new CampanhaContatos();
			$campanhaContato->setConexao($this->getConexao());
			$dados = $campanhaContato->selecionar(sprintf("WHERE idCampanha = %d", $campanhaContato->getConexao()->escape($campanha)));

			return count($dados);

		} catch (Exception $e) {
			$this->setMensagemErro($e->getMessage());
			return 0;
		}
	}

	/**
	 * [vinculaContatos Vincula contatos a campanha]
	 * @param [string] $contatos [Id dos contatos]
	 * @return [int]
	 */
	public function vinculaContatos($contatos){
		try {

			foreach ($contatos as $row) {
				$campanhaContatos = new CampanhaContatos();
				$campanhaContatos
					->setConexao($this->getConexao())
					->setDataCadastro((new DateTime("now", new DateTimeZone('America/Sao_Paulo')))->format("Y-m-d H:i:s"))
					->setIdContato($row)
					->setIdCampanha($this->getIdCampanha())
					->setEnviado("N")
					->setVisualizado("N");

				$campanhaContatos->geraHash();
				
				if(!$campanhaContatos->criar()){
					throw new Exception($campanhaContatos->getMensagemErro());
				}
			}

			return true;

		} catch (Exception $e) {
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}

	/**
	 * [qtdVisualizacoes Retorna a quantidade de visualizações]
	 * @return [int]
	 */
	public function qtdVisualizacoes(){
		$query = sprintf("SELECT
												COUNT(*)
											FROM
												campanhacontatos cc
												INNER JOIN campanha ON cc.idCampanha = campanha.idCampanha
											WHERE
												campanha.idUsuario = %d
												AND cc.visualizado = 'S'",
											$this->getConexao()->escape($this->getIdUsuario())
										);

		try {

			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}

			$row = mysqli_fetch_assoc($result);
			mysqli_free_result($result);

			return array_shift($row);
				
		} catch (Exception $e) {
			$this->setMensagemErro($e->getMessage());
			return 0;
		}
	}

	/**
	 * [qtdCampanhas Retorna a quantidade de campanhas]
	 * @return [int]
	 */
	public function qtdCampanhas(){
		$query = sprintf("SELECT
												COUNT(*)
											FROM
												campanha
											WHERE
												campanha.idUsuario = %d
												AND campanha.`status` != 'C'",
											$this->getConexao()->escape($this->getIdUsuario())
										);

		try {

			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}

			$row = mysqli_fetch_assoc($result);
			mysqli_free_result($result);

			return array_shift($row);
				
		} catch (Exception $e) {
			$this->setMensagemErro($e->getMessage());
			return 0;
		}
	}

	/**
	 * [alteraContatos Altera o vínculo de contatos a campanha]
	 * @param [string] $contatos [Id dos contatos]
	 * @return [boolean]
	 */
	public function alteraContatos($contatos){
		try {
				
			var_dump($contatos);
			exit;



			return false;

		} catch (Exception $e) {
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}
}