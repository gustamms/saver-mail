<?php
header("Content-Type: application/json; charset=utf-8");
require "../../lib/autoload.php";

$return = true;

$db = (new DbConnection())->conn();
if(!$db->conectar()){
	$return = false;
}

if($return){
	$campanha = new CampanhaContatos();
	$campanha->setConexao($db);
	$dados = $campanha->selecionar(sprintf("WHERE hashVisualizado = '%s'", $db->escape($_GET["hash"])));
	if(count($dados) >= 1){
		foreach ($dados as $row) {
			$campanha
				->setIdCampanhaContatos($row->getIdCampanhaContatos())
				->setVisualizado("S");
			$campanha->alterar();
		}
	}
}