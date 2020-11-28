<?php
class Tools{

	/**
	 * [remove caracteres especiais da string]
	 *
	 * @access public
	 * @version 1.0
	 * 
	 * @param [string] $str [variavel que sera limpa]
	 * 
	 * @return [string] [string limpa]
	 */
	static public function limparstr($str){
		return str_replace(array('.', ',', ';', ':', '`', '´', '~', '-', '_', '*', '(', ')', '[', ']', '{', '}', '^', '?', '!', '>', '<', ' ', '/', '\\'), null, $str);
	}

	/**
	 * [is_telefone limpa e informa se e um telefone valido]
	 *
	 * @version 1.0
	 *
	 * @uses tools::limparstr()
	 * 
	 * @param [string] $num [numero que sera validado]
	 * @param [boolean] $comDDD [faz a verificacao contando DDD?]
	 * 
	 * @return [mixed]
	 */
	static public function is_telefone($num, $comDDD = true){		
		//limites para quantidade de caracteres (se tiver validacao com DDD aumenta limites)
		$min = ($comDDD) ? 10 : 8;
		$max = ($comDDD) ? 11 : 9;

		//retira caracteres especiais
		$num = self::limparstr($num);
		
		//retira inicio com zero
		if(substr($num, 0, 1) == '0')
			$num = substr($num, 1);
		
		//quantidade caracteres
		$caracteres = strlen($num);
		
		//menos que o permitido
		if($caracteres < $min)
			return false;

		//mais que o permitido
		if($caracteres > $max)
			return false;
		
		//telefone validado e limpo
		return($num);
	}

	/**
	 * [informa se é um ID valido]
	 *
	 * @access public
	 * @version 1.0
	 * 
	 * @param [int] $int [valor que sera verificado]
	 * 
	 * @return [boolean]
	 */
	static public function is_id($int){
		return (preg_match('/^[1-9][0-9]*$/', $int) === 1 ? true : false);
	}

	/**
	 * [informa se é uma data válida no formato desejado]
	 *
	 * @access public
	 * @version 1.0
	 * 
	 * @param [string] $date [variavel que será validada]
	 * @param [string] $format [formato em que $date deve estar]
	 * 
	 * @return [boolean]
	 */
	static public function is_date($date, $format = 'Y-m-d H:i:s'){
		$d = DateTime::createFromFormat($format, $date, new DateTimeZone("America/Sao_Paulo"));
		return $d && $d->format($format) == $date;
	}

	/**
	 * [formata string nos padroes para nome]
	 *
	 * @access public
	 * @version 1.0
	 *
	 * @uses utf8_decode()
	 * @uses strtr()
	 * @uses strip_tags()
	 * @uses trim()
	 * @uses preg_replace()
	 * @uses utf8_encode()
	 * @uses strtoupper()
	 * 
	 * @param [string] $str [string que sera formatada]
	 * 
	 * @return [string]
	 */
	static public function nameFormat($str)	{

		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ"!@#$%&*()-_+={[}]/?;:.,\\\'<>';
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                              ';
		$str = utf8_decode($str);
		$str = strtr($str, utf8_decode($a), $b);
		$str = strip_tags(trim($str));
		$str = preg_replace('/[0-9]/', null, $str);
		$str = utf8_encode($str);
		$str = trim($str);
		$str = preg_replace('/\s(?=\s)/', null, $str);
		return strtoupper($str);
	}
}