<?php
class MysqliCustom extends Conexao{
	/**
	 * [se conecta ao banco de dados]
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @uses mysqli::data()
	 * @uses mysqli::open()
	 * @uses mysqli::dataetl()
	 *
	 * @param [boolean] $etl [conecta-se na base ETL?]
	 * 
	 * @return [boolean]
	 */
	public function conectar($etl = false)
	{
		if(!$etl)
		{
			$this->data();
		}
		if($etl)
		{
			$this->dataetl();
		}
		return $this->open();
	}

	/**
	 * [define quais dados usará para se conectar]
	 *
	 * @access private
	 * @since 1.0
	 *
	 * @uses Server::on()
	 * @uses conexao::host
	 * @uses conexao::user
	 * @uses conexao::pass
	 * @uses conexao::banco
	 * 
	 * @return [void]
	 */
	private function data()
	{
		if(Server::on()){
			$this->host = 'localhost';
			$this->user = 'id15513956_gustavomendes';
			$this->pass = 'nP233TQfLDu*IpsxG)NB';
			$this->banco = 'id15513956_savermail';
		}else{
			$this->host = 'localhost';
			$this->user = 'root';
			$this->pass = '';
			$this->banco = 'savermail';
		}
	}

	/**
	 * [abre conexao com banco de dados]
	 *
	 * @access private
	 * @since 1.0
	 *
	 * @uses conexao::link
	 * @uses conexao::host
	 * @uses conexao::user
	 * @uses conexao::pass
	 * @uses conexao::banco
	 * 
	 * @return [boolean]
	 */
	private function open()
	{
		$this->setLink(@mysqli_connect($this->host, $this->user, $this->pass, $this->banco));

		if(!$this->getLink())
		{
			$this->setConnecterror(mysqli_connect_error());
			return false;
		}
		return true;
	}

	/**
	 * [fecha conexao com banco de dados]
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @uses conexao::link
	 * 
	 * @return [boolean]
	 */
	public function close()
	{
		return mysqli_close($this->getLink());
	}

	/**
	 * [startCommit inicia commit]
	 * @return [void]
	 */
	public function startCommit()
	{
		mysqli_autocommit($this->getLink(), false);
	}

	/**
	 * [rollback aplica rollback]
	 * @return [void]
	 */
	public function rollback()
	{
		mysqli_rollback($this->getLink());
	}

	/**
	 * [commit aplica commit]
	 * @return [void]
	 */
	public function commit()
	{
		mysqli_commit($this->getLink());
	}

	/**
	 * [getError retorna erro da conexão]
	 * @return [string]
	 */
	public function getError()
	{
		return mysqli_error($this->getLink());
	}

	/**
	 * [getInsertId retorna último id inserido]
	 * @return [int]
	 */
	public function getInsertId()
	{
		return mysqli_insert_id($this->getLink());
	}

	/**
	 * [query executa query]
	 * @param [string] $query [query que deseja executar]
	 * @return [boolean]
	 */
	public function query($query)
	{
		return mysqli_query($this->getLink(), $query);
	}

	/**
	 * [escape aplica mysqli_real_escape_string]
	 * @param [mixed] $val [valor que passará pela função escape_string]
	 * @return [mixed] [valor "escapado"]
	 */
	public function escape($val)
	{
		return mysqli_real_escape_string($this->getLink(), $val);
	}
}