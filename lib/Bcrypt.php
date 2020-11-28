<?php 
class Bcrypt{
	protected static $_saltPrefix = '2a';

	protected static $_defaultCost = 10;

	protected static $_saltLength = 22;
 
	/**
	 * Gera hash
	 * @return string
	 */
	public static function hash($string, $cost = null) {
		if (empty($cost)) {
			$cost = self::$_defaultCost;
		}
 
		// Salt
		$salt = self::generateRandomSalt();
 
		// Hash string
		$hashString = self::__generateHashString((int)$cost, $salt);
 
		return crypt($string, $hashString);
	}
 
	/**
	 * Compara senha com hash
	 * @param string $string senha digita
	 * @param string $hash HASH que vem do banco para ser comparada
	 * @return boolean
	 */
	public static function check($string, $hash) {
		return (crypt($string, $hash) === $hash);
	}
 
	/**
	 * Gera Salt randomico
	 * @return string
	 */
	public static function generateRandomSalt() {
		// Salt seed
		$seed = uniqid(mt_rand(), true);
 
		// Generate salt
		$salt = base64_encode($seed);
		$salt = str_replace('+', '.', $salt);
 
		return substr($salt, 0, self::$_saltLength);
	}

	/**
	 * Cria sequencia de hash para gera-la
	 * @return string
	 */
	private static function __generateHashString($cost, $salt) {
		return sprintf('$%s$%02d$%s$', self::$_saltPrefix, $cost, $salt);
	}
 
}
?>