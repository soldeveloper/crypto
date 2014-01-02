<?php

/**
 * @category	SolDeveloper
 * @package		Crypto
 * @author		Sol Developer <sol.developer@gmail.com>
 * @copyright	Copyright (c) 2013 Sol Developer (https://github.com/soldeveloper/crypto)
 * @license		http://www.gnu.org/copyleft/lesser.html
 */

namespace Crypto;

/**
 * Class for generating a pair of secret and public keys.
 */
class Keys
{

	/**
	 * Key type RSA.
	 */
	const KEYTYPE_RSA = 0;

	/**
	 * Key type DSA.
	 */
	const KEYTYPE_DSA = 1;

	/**
	 * Key type DH.
	 */
	const KEYTYPE_DH = 2;

	/**
	 * Key type EC.
	 */
	const KEYTYPE_EC = 3;

	/**
	 * Hashing algorithm sha512.
	 */
	const DIGEST_ALG_SHA512 = 'sha512';

	/**
	 * Creates and returns a pair of secret and public key.
	 * In case of error throws exception.
	 *
	 * @param int		$keysType			Keys type
	 * @param int		$privateKeyLen		The length of the private key
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	public function create($keysType = self::KEYTYPE_RSA, $privateKeyLen = 512)
	{
		$config = array(
			'digest_alg' => self::DIGEST_ALG_SHA512,
			'private_key_bits' => $privateKeyLen * 8,
			'private_key_type' => $keysType,
		);

		$resource = openssl_pkey_new($config);

		if (false === $resource)
		{
			throw new Exception('Unable to create keys.');
		}

		if (false === openssl_pkey_export($resource, $privateKey))
		{
			throw new Exception('Unable to extract the private key from the resource.');
		}

		$publicKeyInfo = openssl_pkey_get_details($resource);

		if (false === $publicKeyInfo)
		{
			throw new Exception('Unable to extract the public key from the resource.');
		}

		$publicKey = $publicKeyInfo['key'];

		return array(
			'private' => $privateKey,
			'public' => $publicKey
		);
	}

}
