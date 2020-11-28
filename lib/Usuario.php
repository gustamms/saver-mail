<?php 

/**
 * 
 */
class Usuario{
	
	private $mensagemErro;
	private $conexao;

	private $idUsuario;
	private $dataCadastro;
	private $nome;
	private $email;
	private $senha;
	private $confirmouEmail;
	private $linkConfirmacao;

	function __construct($id = null){
		$this->setIdUsuario($id);
	}

	public function getMensagemErro(){
	  return $this->mensagemErro;
	}
	public function setMensagemErro($mensagemErro){
		$this->mensagemErro = $mensagemErro;
	  return $this;
	}
	public function getConexao(){
	  return $this->conexao;
	}
	public function setConexao($conexao){
		$this->conexao = $conexao;
	  return $this;
	}
	public function getIdUsuario(){
	  return $this->idUsuario;
	}
	public function setIdUsuario($idUsuario){
		$this->idUsuario = $idUsuario;
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
	public function getSenha(){
	  return $this->senha;
	}
	public function setSenha($senha){
		$this->senha = $senha;
	  return $this;
	}
	public function getConfirmouEmail(){
	  return $this->confirmouEmail;
	}
	public function setConfirmouEmail($confirmouEmail){
		$this->confirmouEmail = $confirmouEmail;
	  return $this;
	}
	public function getLinkConfirmacao(){
	  return $this->linkConfirmacao;
	}
	public function setLinkConfirmacao($linkConfirmacao){
		$this->linkConfirmacao = $linkConfirmacao;
	  return $this;
	}

	/**
	 * [criar CRUD Insert]
	 * @return [boolean]
	 */
	public function criar(){
		$query = sprintf("INSERT INTO usuario (
												dataCadastro,
												nome,
												email,
												senha,
												confirmouEmail,
												linkConfirmacao
											)VALUES(
												'%s',
												'%s',
												'%s',
												'%s',
												'%s',
												'%s'
											)",
											$this->getConexao()->escape($this->getDataCadastro()),
											$this->getConexao()->escape($this->getNome()),
											$this->getConexao()->escape($this->getEmail()),
											$this->getConexao()->escape($this->getSenha()),
											$this->getConexao()->escape($this->getConfirmouEmail()),
											(empty($this->getLinkConfirmacao()) ? 'NULL' : $this->getConexao()->escape($this->getLinkConfirmacao()))
										);
		try {
			$result = $this->getConexao()->query($query);
			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$this->setIdUsuario($this->getConexao()->getInsertId());
			return true;

		} catch (Exception $e) {
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}

	/**
	 * [recuperar CRUD Insert]
	 * @return [boolean]
	 */
	public function recuperar(){
		$query = sprintf("SELECT
												*
											FROM
												usuario
											WHERE
												idUsuario = %d",
											$this->getConexao()->escape($this->getIdUsuario())
										);
		try {
			$result = $this->getConexao()->query($query);
			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			
			if(mysqli_num_rows($result) <= 0){
				throw new Exception("Usuario não encontrado");
			}

			$row = mysqli_fetch_assoc($result);
			$this
				->setIdUsuario($row["idUsuario"])
				->setDataCadastro($row["dataCadastro"])
				->setNome($row["nome"])
				->setEmail($row["email"])
				->setSenha($row["senha"])
				->setConfirmouEmail($row["confirmouEmail"])
				->setLinkConfirmacao($row["linkConfirmacao"]);

			mysqli_free_result($result);
			return true;

			return true;

		} catch (Exception $e) {
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

		$query = sprintf("SELECT * FROM usuario %s", (!empty($query) ? $query : null));

		try{
			$result = $this->getConexao()->query($query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			$data = array();

			if(mysqli_num_rows($result) > 0){
				foreach ($result as $row){
					
					$obj = new Usuario();
					$obj
						->setIdUsuario($row["idUsuario"])
						->setDataCadastro($row["dataCadastro"])
						->setNome($row["nome"])
						->setEmail($row["email"])
						->setSenha($row["senha"])
						->setConfirmouEmail($row["confirmouEmail"])
						->setLinkConfirmacao($row["linkConfirmacao"]);
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
	 * [alterar CRUD Select]
	 * @param [string] $query [query à acrescentar]
	 * @return [array]
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
		if(!empty($this->getSenha())){
			$set[] = sprintf("senha = '%s'", $this->getConexao()->escape($this->getSenha()));
		}
		if(!empty($this->getConfirmouEmail())){
			$set[] = sprintf("confirmouEmail = '%s'", $this->getConexao()->escape($this->getConfirmouEmail()));
		}
		if(!empty($this->getLinkConfirmacao())){
			if($this->getLinkConfirmacao() == 'NULL'){
				$set[] = sprintf("linkConfirmacao = %s", $this->getConexao()->escape($this->getLinkConfirmacao()));
			}else{
				$set[] = sprintf("linkConfirmacao = '%s'", $this->getConexao()->escape($this->getLinkConfirmacao()));
			}
		}

		try {
			$query = sprintf("UPDATE usuario SET %s WHERE idUsuario = %d", implode($set, ","), $this->getConexao()->escape($this->getIdUsuario()));
			$result = mysqli_query($this->getConexao()->getLink(), $query);

			if(!$result){
				throw new Exception($this->getConexao()->getError());
			}
			return true;
		} catch (Exception $e) {
			$this->setMensagemErro($e->getMessage());
			return false;
		}
	}
}
?>