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

	if(!isset($_POST["campanha"]) || empty($_POST["campanha"])){
		throw new Exception("Não foi possível retornar os contatos da campanha");
	}

	$contatosCampanha = new CampanhaContatos();
	$contatosCampanha->setConexao($db);
	$dados = $contatosCampanha->selecionar(sprintf("WHERE idCampanha = %d", $db->escape($_POST["campanha"])));

	if($dados){
		foreach ($dados as $row) {
			$temp["cod"] = $row->getIdContato();
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