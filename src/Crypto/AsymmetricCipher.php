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
 * Класс для асимметричного шифра.
 */
class AsymmetricCipher
{

	/**
	 * Стандарт спецификации PKCS#1.
	 */
	const PADDING_PKCS1 = 1;

	/**
	 * Возвращает данные, закодированные с помощью открытого ключа.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string	$data			Данные для кодирования
	 * @param string	$publicKey		Открытый ключ
	 * @param int		$padding			Тип спецификации криптографии
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function encryptByPublicKey($data, $publicKey, $padding = self::PADDING_PKCS1)
	{
		$keyDetails = openssl_pkey_get_details(openssl_pkey_get_public($publicKey));
		$this->checkingSizeDataForEncoding($data, $keyDetails, $padding);
		if (false === openssl_public_encrypt($data, $encryptData, $publicKey, $padding))
		{
			throw new Exception('Fail encrypt from the public key.');
		}
		return $encryptData;
	}

	/**
	 * Возвращает декодированные данные, закодированные с помощью открытого ключа.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string	$data			Данные для декодирования
	 * @param string	$privateKey		Закрытый ключ
	 * @param int		$padding			Тип спецификации криптографии
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function decryptByPrivateKey($data, $privateKey, $padding = self::PADDING_PKCS1)
	{
		if (false === openssl_private_decrypt($data, $decryptData, $privateKey, $padding))
		{
			throw new Exception('Fail decrypt by the private key.');
		}
		return $decryptData;
	}

	/**
	 * Возвращает данные, закодированные с помощью закрытого ключа.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string	$data			Данные для кодирования
	 * @param string	$privateKey		Закрытый ключ
	 * @param int		$padding			Тип спецификации криптографии
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function encryptByPrivateKey($data, $privateKey, $padding = self::PADDING_PKCS1)
	{
		$keyDetails = openssl_pkey_get_details(openssl_pkey_get_private($privateKey));
		$this->checkingSizeDataForEncoding($data, $keyDetails, $padding);
		if (false === openssl_private_encrypt($data, $encryptData, $privateKey, $padding))
		{
			throw new Exception('Fail encrypt from the private key.');
		}
		return $encryptData;
	}

	/**
	 * Возвращает декодированные данные, закодированные с помощью закрытого ключа.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string	$data			Данные для декодирования
	 * @param string	$publicKey		Открытый ключ
	 * @param int		$padding			Тип спецификации криптографии
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function decryptByPublicKey($data, $publicKey, $padding = self::PADDING_PKCS1)
	{
		if (false === openssl_public_decrypt($data, $decryptData, $publicKey, $padding))
		{
			throw new Exception('Fail decrypt by the public key.');
		}
		return $decryptData;
	}

	/**
	 * Проверяет размер кодируемых данных с учетом длины ключа и типа спецификации криптографии.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string	$data			Данные для кодирования
	 * @param array	$keyDetails		Детали ключа
	 * @param int		$padding			Тип спецификации криптографии
	 *
	 * @throws Exception
	 */
	private function checkingSizeDataForEncoding($data, $keyDetails, $padding)
	{
		switch ($padding)
		{
			case self::PADDING_PKCS1:
				$paddingSize = 11;
				break;
			default:
				throw new Exception('Error while checking the data size for encryption using the key.');
				break;
		}
		if (strlen($data)  > ($keyDetails['bits'] / 8) - $paddingSize)
		{
			throw new Exception('Error in data size alignment.');
		}
	}

}
