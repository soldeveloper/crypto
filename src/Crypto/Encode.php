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
 * Class to encode information and creating an electronic signature.
 */
class Encode extends Properties
{

	/**
	 * Returns the encoded information with a password.
	 * In case of error throws exception.
	 *
	 * @param string		$data		Information
	 * @param string		$password		Password
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function encodeByPassword($data, $password)
	{
		$salt = openssl_random_pseudo_bytes(32);
		$iv = substr(md5($password . $salt, true), 0, $this->getInitializationVector());
		if (false === $encryptedData = openssl_encrypt($data, $this->getCipher(), $password, true, $iv))
		{
			throw new Exception('Error when encoding data.');
		}
		$encryptedData .= $salt;
		if ($this->useBase64Wrapper())
		{
			$encryptedData = base64_encode($encryptedData);
		}
		return $encryptedData;
	}

	/**
	 * Returns the password encrypted with the public key and the
	 * encoded information encrypted with a password.
	 * In case of error throws exception.
	 *
	 * @param string	$data			Information
	 * @param string	$publicKey		Public key
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	public function encodeByKey($data, $publicKey)
	{
		$password = openssl_random_pseudo_bytes(128);
		$encryptedData = $this->encodeByPassword($data, $password);
		if (false === openssl_public_encrypt($password, $encryptedPassword, $publicKey))
		{
			throw new Exception('Error of public key cryptography.');
		}
		if ($this->useBase64Wrapper())
		{
			$encryptedPassword = base64_encode($encryptedPassword);
		}
		return array(
			'data' => $encryptedData,
			'password' => $encryptedPassword
		);
	}

	/**
	 * Creates and returns an electronic signature for information.
	 * In case of error throws exception.
	 *
	 * @param string	$data			Information
	 * @param string	$privateKey		The secret key
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function createSignature($data, $privateKey)
	{
		if (false === openssl_sign($data, $signature, $privateKey, $this->getSignatureAlgo()))
		{
			throw new Exception('Error create signature.');
		}
		if ($this->useBase64Wrapper())
		{
			$signature = base64_encode($signature);
		}
		return $signature;
	}

}
