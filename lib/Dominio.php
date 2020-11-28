<?php
class Dominio{
	/**
	 * [get recupera url baseado no servidor que está (on/local)]
	 *
	 * @access public
	 * @version 1.0
	 * 
	 * @param [string] $type [define qual url sera chamada]
	 * 
	 * @return [string]
	 */
	static public function get($type = null){
		switch($type){
			case 'confirmacao' : 
				if(Server::on()){
					$url = 'https://savermail.000webhostapp.com/';
				}else{
					$url = 'http://localhost/savermail';
				}
				return $url; 
				break;
				
			default : return self::getDefault(); break;
		}
	}

	/**
	 * [getDefault recupera url principal]
	 *
	 * @access private
	 * @version 1.0
	 *
	 * @uses Server::on()
	 * 
	 * @return [string]
	 */
	static private function getDefault(){
		if(Server::on())
		{
			$url = 'https://savermail.000webhostapp.com/crm';
		}
		if(!Server::on())
		{
			$url = 'http://localhost/savermail/public_html/crm';
		}
		return $url;
	}

	// /**
	//  * [getlogin recupera url da página login]
	//  *
	//  * @access private
	//  * @version 1.0
	//  *
	//  * @uses Server::on()
	//  * 
	//  * @return [string]
	//  */
	// static private function getlogin()
	// {
	// 	if(Server::on())
	// 	{
	// 		$url = 'http://login.exemplo.com.br';
	// 	}
	// 	if(!Server::on())
	// 	{
	// 		$url = 'http://192.168.30.245:84/projetos/_novoprojeto/login';
	// 	}
	// 	return $url;
	// }
}