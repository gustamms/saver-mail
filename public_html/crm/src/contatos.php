<?php
header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";

$where = null;
$data = [];
$db = (new DbConnection())->conn();

try {
	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	UsuarioLogado::setConexao($db);

	if(!UsuarioLogado::recuperar()){
	  throw new Exception("NOT_RECOVER_USER");
	}

	if(isset($_POST["contato"])){
		$where = sprintf("AND idContato = %d", $db->escape($_POST["contato"]));
	}

	$contato = new Contato();
	$contato->setConexao($db);
	$dados = $contato->selecionar(sprintf("WHERE idUsuarioRespCadastrar = %d %s ORDER BY idContato DESC", UsuarioLogado::getIdUsuario(), $where));

	$qtdContatos = count($dados);

	if($dados){
		foreach ($dados as $row) {
			$temp["cod"] = $row->getIdContato();
			$temp["nome"] = $row->getNome();
			$temp["email"] = $row->getEmail();
			$temp["dtCadastro"] = $row->getDataCadastro();
			$temp["qtdContatos"] = $qtdContatos;
			$data[] = $temp;
		}
	}
	$response = [
		"success" => true, 
		"contatos" => $data
	];

} catch (Exception $e) {
	$response = [
		"success" => false, 
		"contatos" => $data, 
		"error" => $e->getMessage()
	];
}finally{
	if($db->getLink()){
		$db->close();
	}
	echo json_encode($response);
}