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
 * Класс хранилища ключей.
 */
class KeyStore
{

	/**
	 * Импорт ключей из файла.
	 */
	const IMPORT_FROM_FILE = 1;

	/**
	 * Создание новых ключей.
	 */
	const CREATE_NEW = 2;

	/**
	 * Импорт ключей из параметров.
	 */
	const IMPORT_FROM_PARAMS = 3;

	/**
	 * Тип ключа RSA.
	 */
	const KEYTYPE_RSA = 0;

	/**
	 * Тип ключа DSA.
	 */
	const KEYTYPE_DSA = 1;

	/**
	 * Тип ключа DH.
	 */
	const KEYTYPE_DH = 2;

	/**
	 * Тип ключа EC.
	 */
	const KEYTYPE_EC = 3;

	/**
	 * Тип открытого ключа.
	 */
	const PUBLIC_KEY = 1;

	/**
	 * Тип закрытого ключа.
	 */
	const PRIVATE_KEY = 2;

	/**
	 * Закрытый ключ.
	 *
	 * @var string
	 */
	private $privateKey = null;

	/**
	 * Открытый ключ.
	 *
	 * @var string
	 */
	private $publicKey = null;

	/**
	 * Устанавливает входные значения.
	 *
	 * @param string	$privateKey		Закрытый ключ
	 * @param string	$publicKey		Открытый ключ
	 */
	private function __construct($privateKey, $publicKey)
	{
		$this
			->setPrivateKey($privateKey)
			->setPublicKey($publicKey);
	}

	/**
	 * Фабрика для создания хранилища ключей.
	 * В случае ошибки бросает исключение.
	 *
	 * @param int		$type		Способ создания ключей
	 * @param array	$params		Параметры для создания ключей
	 *
	 * @return KeyStore
	 *
	 * @throws Exception
	 */
	static public function factory($type, array $params)
	{
		$privateKey = null;
		$publicKey = null;
		switch ($type)
		{
			case self::CREATE_NEW:
				/**
				 * Создание новых ключей.
				 */
				$config = array(
					'digest_alg' => $params['digest'],
					'private_key_bits' => intval($params['keyBits']),
					'private_key_type' => $params['keyType'],
				);
				$passPhrase = isset($params['passPhrase']) ? $params['passPhrase'] : null;
				$resource = openssl_pkey_new($config);
				if (false === $resource)
				{
					throw new Exception('Unable to create keys.');
				}
				if (false === @openssl_pkey_export($resource, $privateKey, $passPhrase))
				{
					throw new Exception('Unable to extract the private key from the resource.');
				}
				$publicKeyInfo = openssl_pkey_get_details($resource);
				if (false === $publicKeyInfo)
				{
					throw new Exception('Unable to extract the public key from the resource.');
				}
				$publicKey = $publicKeyInfo['key'];
				break;
			case self::IMPORT_FROM_FILE:
				/**
				 * Импорт ключей из файла.
				 */
				if (isset($params['private']))
				{
					$privateKey = @file_get_contents($params['private']);
					if (false === $privateKey)
					{
						throw new Exception('Cannot read the private key.');
					}
				}
				if (isset($params['public']))
				{
					$publicKey = @file_get_contents($params['public']);
					if (false === $publicKey)
					{
						throw new Exception('Cannot read the public key.');
					}
				}
				break;
			case self::IMPORT_FROM_PARAMS:
				/**
				 * Импорт ключей из параметров.
				 */
				if (isset($params['privateKey']))
				{
					$privateKey = $params['privateKey'];
				}
				if (isset($params['publicKey']))
				{
					$publicKey = $params['publicKey'];
				}
				break;
			default:
				throw new Exception('Unknown method create a keystore.');
				break;
		}
		return new self($privateKey, $publicKey);
	}

	/**
	 * Возвращает секретный ключ.
	 * В случае ошибки бросает исключение.
	 *
	 * @param null|string		$passPhrase		Ключевая фраза
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function getPrivateKey($passPhrase = null)
	{
		if (is_null($this->privateKey))
		{
			throw new Exception('Private key not found.');
		}
		if (!is_string($passPhrase))
		{
			return $this->privateKey;
		}
		$resource = openssl_pkey_get_private($this->privateKey, $passPhrase);
		if (false === @openssl_pkey_export($resource, $privateKey))
		{
			throw new Exception('Unable to extract the private key from the resource.');
		}
		return $privateKey;
	}

	/**
	 * Возвращает открытый ключ.
	 * В случае ошибки бросает исключение.
	 *
	 * @throws Exception
	 *
	 * @return string
	 */
	public function getPublicKey()
	{
		if (is_null($this->publicKey))
		{
			throw new Exception('Public key not found.');
		}
		return $this->publicKey;
	}

	/**
	 * Возвращает детали секретного ключа.
	 * В случае ошибки бросает исключение.
	 *
	 * @param null|string		$passPhrase		Ключевая фраза
	 *
	 * @throws Exception
	 *
	 * @return array
	 */
	public function getPrivateKeyDetails($passPhrase = null)
	{
		if (is_null($this->privateKey))
		{
			throw new Exception('Private key not found.');
		}
		return openssl_pkey_get_details(openssl_pkey_get_private($this->privateKey, $passPhrase));
	}

	/**
	 * Возвращает детали открытого ключа.
	 * В случае ошибки бросает исключение.
	 *
	 * @throws Exception
	 *
	 * @return array
	 */
	public function getPublicKeyDetails()
	{
		if (is_null($this->publicKey))
		{
			throw new Exception('Public key not found.');
		}
		return openssl_pkey_get_details(openssl_pkey_get_public($this->publicKey));
	}

	/**
	 * Экспорт ключей в файл.
	 * Возвращает размер записанной информации.
	 * В случае ошибки бросает исключение.
	 *
	 * @param int		$keyType			Тип ключа
	 * @param string	$pathToFile		Путь к файлу
	 *
	 * @return int
	 *
	 * @throws Exception
	 */
	public function exportToFile($keyType, $pathToFile)
	{
		switch ($keyType)
		{
			case self::PUBLIC_KEY:
				$content = $this->getPublicKey();
				break;
			case self::PRIVATE_KEY:
				$content = $this->getPrivateKey();
				break;
			default:
				throw new Exception('Unknown key.');
				break;
		}
		$length = @file_put_contents($pathToFile, $content);
		if (false === $length)
		{
			throw new Exception('Error exporting keys.');
		}
		return $length;
	}

	/**
	 * Устанавливает закрытый ключ.
	 * Возвращает текущий объект.
	 *
	 * @param string		$privateKey		Закрытый ключ
	 *
	 * @return self
	 */
	private function setPrivateKey($privateKey)
	{
		$this->privateKey = $privateKey;
		return $this;
	}

	/**
	 * Устанавливает открытый ключ.
	 * Возвращает текущий объект.
	 *
	 * @param string		$publicKey		Открытый ключ
	 *
	 * @return self
	 */
	private function setPublicKey($publicKey)
	{
		$this->publicKey = $publicKey;
		return $this;
	}

}
