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
 * Class for decoding information and verification of the electronic signature.
 */
class Decode extends Properties
{

	/**
	 * Returns the decoded information encrypted with a password.
	 * In case of error throws exception.
	 *
	 * @param string	$data		Information
	 * @param string	$password		Password
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function decodeByPassword($data, $password)
	{
		if ($this->useBase64Wrapper())
		{
			$data = base64_decode($data);
		}
		$salt = substr($data, -32);
		$iv = substr(md5($password . $salt, true), 0, $this->getInitializationVector());
		if (false === $decryptedData = openssl_decrypt(substr($data, 0, -32), $this->getCipher(), $password, true, $iv))
		{
			throw new Exception('Data cannot be decoded.');
		}
		return $decryptedData;
	}

	/**
	 * Returns information encoded with the public key.
	 * In case of error throws exception.
	 *
	 * @param array		$encryptedData		Encrypted password and data
	 * @param string		$privateKey		The secret key
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function decodeByKey(array $encryptedData, $privateKey)
	{
		if ($this->useBase64Wrapper())
		{
			$encryptedData['password'] = base64_decode($encryptedData['password']);
		}
		if (false === openssl_private_decrypt($encryptedData['password'], $password, $privateKey))
		{
			throw new Exception('Error of private key cryptography.');
		}
		return $this->decodeByPassword($encryptedData['data'], $password);
	}

	/**
	 * Returns the verification status of electronic signature.
	 * In case of error throws exception.
	 *
	 * @param string	$data			Information
	 * @param string	$signature		Signature
	 * @param string	$publicKey		Public key
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	public function verifySignature($data, $signature, $publicKey)
	{
		if ($this->useBase64Wrapper())
		{
			$signature = base64_decode($signature);
		}
		if (-1 == $status = openssl_verify($data, $signature, $publicKey, $this->getSignatureAlgo()))
		{
			throw new Exception('Error verify signature.');
		}
		return (1 == $status);
	}

}
