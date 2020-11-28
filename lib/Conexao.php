<?php
class Conexao{	
	/**
	 * host de onde esta o banco de dados
	 * @var string
	 * @since 1.0
	 */
	protected $host;

	/**
	 * usuario do banco de dados
	 * @var string
	 * @since 1.0
	 */
	protected $user;

	/**
	 * senha do banco de dados
	 * @var string
	 * @since 1.0
	 */
	protected $pass;

	/**
	 * nome do banco de dados
	 * @var string
	 * @since 1.0
	 */
	protected $banco;
	
	/**
	 * [objeto da conexao com banco de dados]
	 * @var object
	 * @since 1.0
	 * @version 1.1
	 */
	public $link;

	/**
	 * [erro de conexao (caso exista)]
	 * @var [string]
	 * @since 1.1
	 */
	public $connecterror;

	public function getBanco(){
	  return $this->banco;
	}
	public function setBanco($banco){
	  $this->banco = $banco;
	  return $this;
	}
	
	public function getLink(){
		return $this->link;
	}
	public function setLink($novoLink){
		$this->link = $novoLink;
	}

	public function getConnecterror(){
	  return $this->connecterror;
	}
	public function setConnecterror($connecterror){
	  $this->connecterror = $connecterror;
	  return $this;
	}
}