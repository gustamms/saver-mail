<?php
// header("Content-Type: application/json; charset=utf-8");
require "../../../lib/autoload.php";
// error_reporting(0);

$data = [];
$db = (new DbConnection())->conn();

try {
	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	if(!isset($_POST['post']) || empty($_POST['post'])){
		throw new Exception("Não foi possível realizar a operação desejada");
	}

	$post = json_decode($_POST["post"]);

	if(empty($post->usuario)){
		throw new Exception("Digite uma descrição");
	}

	$query = sprintf("SELECT * FROM usuario WHERE email = '%s'", $db->escape($post->usuario));

	$result = $db->query($query);

	if(!$result){
		throw new Exception("Não foi possível localizar");
	}

	if(mysqli_num_rows($result) == 0){
		throw new Exception("Usuário não existe");
	}

	$query = sprintf("SELECT
										link.*
									FROM 
										usuario 
										INNER JOIN link ON usuario.idUsuario = link.idUsuarioRespCadastro
									WHERE 
										usuario.email = '%s'",
										$db->escape($post->usuario)
									);

	$result = $db->query($query);

	if(!$result){
		throw new Exception("Não foi possível localizar");
	}

	$response = ["success" => true];

} catch (Exception $e) {
	$response = ["success" => false, "error" => $e->getMessage()];
}finally{
	if($db->getLink()){
		$db->close();
	}
	echo json_encode($response);
}