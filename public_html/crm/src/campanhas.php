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

	if(isset($_POST["campanha"])){
		$where = sprintf("AND idCampanha = %d", $db->escape($_POST["campanha"]));
	}

	$campanha = new Campanha();
	$campanha->setConexao($db);
	$dados = $campanha->selecionar(sprintf("WHERE idUsuario = %d %s ORDER BY idCampanha DESC", UsuarioLogado::getIdUsuario(), $where));

	if($dados){
		foreach ($dados as $row) {
			$temp["cod"] = $row->getIdCampanha();
			$temp["dataCadastro"] = $row->getDataCadastro();
			$temp["descricao"] = $row->getDescricao();
			$temp["status"] = $row->getStatus();
			$temp["conteudo"] = html_entity_decode($row->getCorpo());
			$temp["totVisualizacao"] = $campanha->qtdVisualizacao($row->getIdCampanha());
			$temp["totContatos"] = $campanha->qtdContatos($row->getIdCampanha()); 
			$data[] = $temp;
		}
	}
	$response = [
		"success" => true, 
		"campanhas" => $data
	];

} catch (Exception $e) {
	$response = [
		"success" => false, 
		"campanhas" => $data, 
		"error" => $e->getMessage()
	];
}finally{
	if($db->getLink()){
		$db->close();
	}
	echo json_encode($response);
}