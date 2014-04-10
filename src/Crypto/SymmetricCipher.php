<?php

/**
 * @category		SolDeveloper
 * @package		Crypto
 * @author		Sol Developer <sol.developer@gmail.com>
 * @copyright		Copyright (c) 2014 Sol Developer (https://github.com/soldeveloper/crypto)
 * @license		http://www.gnu.org/copyleft/lesser.html
 */

namespace Crypto;

/**
 * Класс для симметричного шифра.
 */
class SymmetricCipher
{

	/**
	 * Алгоритм шифрования AES-128.
	 */
	const AES_128 = 'aes128';

	/**
	 * Алгоритм шифрования AES-192.
	 */
	const AES_192 = 'aes192';

	/**
	 * Алгоритм шифрования AES-256.
	 */
	const AES_256 = 'aes256';

	/**
	 * Алгоритм шифрования AES-128-CBC.
	 */
	const AES_128_CBC = 'aes-128-cbc';

	/**
	 * Алгоритм шифрования AES-192-CBC.
	 */
	const AES_192_CBC = 'aes-192-cbc';

	/**
	 * Алгоритм шифрования AES-256-CBC.
	 */
	const AES_256_CBC = 'aes-256-cbc';

	/**
	 * Алгоритм шифрования DES.
	 */
	const DES = 'des';

	/**
	 * Алгоритм шифрования DES-3.
	 */
	const DES3 = 'des3';

	/**
	 * Возвращает данные, закодированные с помощью пароля.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string		$cipherAlgorithm		Алгоритм шифрования
	 * @param string		$password				Пароль
	 * @param string		$data				Данные для кодирования
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function encrypt($cipherAlgorithm, $password, $data)
	{
		$salt = openssl_random_pseudo_bytes(32);
		$iv = substr(md5($password . $salt, true), 0, $this->getInitializationVector($cipherAlgorithm));
		if (false === $encryptedData = openssl_encrypt($data, $cipherAlgorithm, $password, true, $iv))
		{
			throw new Exception('Data cannot be encoded.');
		}
		$encryptedData .= $salt;
		return $encryptedData;
	}

	/**
	 * Возвращает декодированные данные, закодированные с помощью пароля.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string		$cipherAlgorithm		Алгоритм шифрования
	 * @param string		$password				Пароль
	 * @param string		$data				Данные для декодирования
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function decrypt($cipherAlgorithm, $password, $data)
	{
		$salt = substr($data, -32);
		$iv = substr(md5($password . $salt, true), 0, $this->getInitializationVector($cipherAlgorithm));
		if (false === $decryptedData = openssl_decrypt(substr($data, 0, -32), $cipherAlgorithm, $password, true, $iv))
		{
			throw new Exception('Data cannot be decoded.');
		}
		return $decryptedData;
	}

	/**
	 * Возвращает вектор инициализации для шифра.
	 *
	 * @param string		$cipherAlgorithm		Алгоритм шифрования
	 *
	 * @return int
	 */
	private function getInitializationVector($cipherAlgorithm)
	{
		switch ($cipherAlgorithm)
		{
			case self::DES:
			case self::DES3:
				return 8;
				break;
		}
		return 16;
	}

}
