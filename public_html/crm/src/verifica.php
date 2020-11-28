<?php
$db = (new DbConnection())->conn();

try {

	if(!$db->conectar()){
		throw new Exception("Não foi possível se conectar ao banco de dados");
	}

	UsuarioLogado::setConexao($db);

	if(!UsuarioLogado::recuperar()){
		throw new Exception();
	}	

	if(in_array(Page::$page, ["login","criar-conta",""])){
		header(sprintf("Location: %s", Dominio::get()."/dashboard"));
	}

}catch(Exception $e){
	
	if(!in_array(Page::$page, ["login","criar-conta",""])){
		header(sprintf("Location: %s", Dominio::get()));
	}

}finally{
	if($db->getLink()){
		$db->close();
	}
}