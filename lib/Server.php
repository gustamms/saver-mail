<?php
class Server{
	/**
	 * [dominio do server online]
	 * @var [string]
	 * @since 1.0
	 */
	static private $dominio = 'savermail.000webhostapp.com';

	/**
	 * [retorna se está no server online ou local]
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @uses server::dominio
	 * 
	 * @return [boolean]
	 */
	static public function on(){
		if(strpos($_SERVER['HTTP_HOST'], self::$dominio) !== false)
		{
			return true;
		}
		return false;
	}
}