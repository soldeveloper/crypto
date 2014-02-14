<?php

/**
 * @category		SolDeveloper
 * @package		Crypto
 * @author		Sol Developer <sol.developer@gmail.com>
 * @copyright		Copyright (c) 2013 Sol Developer (https://github.com/soldeveloper/crypto)
 * @license		http://www.gnu.org/copyleft/lesser.html
 */

namespace Crypto;

/**
 * Сonfiguration for encoding and decoding.
 */
abstract class Properties
{

	/**
	 * Signature algorithm SHA1.
	 */
	const ALGO_SHA1 = 1;

	/**
	 * Signature algorithm MD5.
	 */
	const ALGO_MD5 = 2;

	/**
	 * Signature algorithm MD4.
	 */
	const ALGO_MD4 = 3;

	/**
	 * Encryption algorithm AES-128.
	 */
	const CIPHER_AES_128 = 'aes128';

	/**
	 * Encryption algorithm AES-192.
	 */
	const CIPHER_AES_192 = 'aes192';

	/**
	 * Encryption algorithm AES-256.
	 */
	const CIPHER_AES_256 = 'aes256';

	/**
	 * Encryption algorithm AES-128-CBC.
	 */
	const CIPHER_AES_128_CBC = 'aes-128-cbc';

	/**
	 * Encryption algorithm AES-192-CBC.
	 */
	const CIPHER_AES_192_CBC = 'aes-192-cbc';

	/**
	 * Encryption algorithm AES-256-CBC.
	 */
	const CIPHER_AES_256_CBC = 'aes-256-cbc';

	/**
	 * Encryption algorithm DES.
	 */
	const CIPHER_DES = 'des';

	/**
	 * Encryption algorithm DES-3.
	 */
	const CIPHER_DES3 = 'des3';

	/**
	 * Default configuration.
	 *
	 * @var array
	 */
	private $properties = array(
		'base64Wrapper' => true,
		'cipher' => self::CIPHER_AES_256_CBC,
		'signatureAlgo' => self::ALGO_SHA1,
	);

	/**
	 * Sets the input values.
	 *
	 * @param array		$properties		Custom configuration
	 */
	function __construct(array $properties = array())
	{
		$this->mergeProperties($properties);
	}

	/**
	 * Configures configuration for encoding and decoding information.
	 * Gets the current object.
	 *
	 * @param array		$properties		Custom configuration
	 *
	 * @return self
	 */
	private function mergeProperties(array $properties = array())
	{
		$this->properties = array_merge($this->properties, $properties);
		return $this;
	}

	/**
	 * Returns the configuration for encoding and decoding.
	 *
	 * @return array
	 */
	private function getProperties()
	{
		return $this->properties;
	}

	/**
	 * Returns a flag to use wrappers Base64.
	 *
	 * @return bool
	 */
	protected function useBase64Wrapper()
	{
		return (true === $this->getProperties()['base64Wrapper']);
	}

	/**
	 * Gets the type of cipher.
	 *
	 * @return string
	 */
	protected function getCipher()
	{
		return $this->getProperties()['cipher'];
	}

	/**
	 * Returns the signature algorithm.
	 *
	 * @return int
	 */
	protected function getSignatureAlgo()
	{
		return (int)$this->getProperties()['signatureAlgo'];
	}

	/**
	 * Returns the initialization vector for the cipher.
	 *
	 * @return int
	 */
	protected function getInitializationVector()
	{
		switch ($this->getCipher())
		{
			case self::CIPHER_DES:
			case self::CIPHER_DES3:
				return 8;
				break;
		}
		return 16;
	}

}
