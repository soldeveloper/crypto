<?php

/**
 * @category		SolDeveloper
 * @package		Crypto Sample
 * @author		Sol Developer <sol.developer@gmail.com>
 * @copyright		Copyright (c) 2013 Sol Developer (https://github.com/soldeveloper/crypto)
 * @license		http://www.gnu.org/copyleft/lesser.html
 */

require_once 'bootstrap.php';

use Crypto\Keys as CryptoKeys;
use Crypto\Encode as CryptoEncode;
use Crypto\Decode as CryptoDecode;
use Crypto\CertificateX509 as CryptoCertificate;
use Crypto\Exception as CryptoException;

try
{
	$cryptoKeys = new CryptoKeys();
	$cryptoEncode = new CryptoEncode();
	$cryptoDecode = new CryptoDecode();
	$cryptoCertificate = new CryptoCertificate();

	$data = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.';
	echo PHP_EOL . str_repeat('=', 40) . ' Source ' . str_repeat('=', 40);
	echo PHP_EOL . $data;
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Example encoding / decoding a password.
	 */
	$password = 'Hello world';
	$encodeData = $cryptoEncode->encodeByPassword($data, $password);
	$decodeData = $cryptoDecode->decodeByPassword($encodeData, $password);
	echo PHP_EOL . str_repeat('=', 40) . ' Password ' . str_repeat('=', 40);
	echo PHP_EOL . $password;
	echo PHP_EOL . str_repeat('=', 40) . ' Encode data ' . str_repeat('=', 40);
	echo PHP_EOL . $encodeData;
	echo PHP_EOL . str_repeat('=', 40) . ' Decode data ' . str_repeat('=', 40);
	echo PHP_EOL . $decodeData;
	echo PHP_EOL . str_repeat('=', 90);
	echo PHP_EOL . PHP_EOL;

	/**
	 * Example encoding / decoding using the pair of public and secret key.
	 */
	$keys = $cryptoKeys->create();
	$encodeData = $cryptoEncode->encodeByKey($data, $keys['public']);
	$decodeData = $cryptoDecode->decodeByKey($encodeData, $keys['private']);
	echo PHP_EOL . str_repeat('=', 40) . ' Public key ' . str_repeat('=', 40);
	echo PHP_EOL . $keys['public'];
	echo str_repeat('=', 40) . ' Private key ' . str_repeat('=', 40);
	echo PHP_EOL . $keys['private'];
	echo str_repeat('=', 40) . ' Encode password ' . str_repeat('=', 40);
	echo PHP_EOL . $encodeData['password'];
	echo PHP_EOL . str_repeat('=', 40) . ' Encode data ' . str_repeat('=', 40);
	echo PHP_EOL . $encodeData['data'];
	echo PHP_EOL . str_repeat('=', 40) . ' Decode data ' . str_repeat('=', 40);
	echo PHP_EOL . $decodeData;
	echo PHP_EOL . str_repeat('=', 90);
	echo PHP_EOL . PHP_EOL;

	/**
	 * Example creating and verifying digital signatures using a pair of public and private key.
	 */
	$signature = $cryptoEncode->createSignature($data, $keys['private']);
	$chekSignature = $cryptoDecode->verifySignature($decodeData, $signature, $keys['public']);
	echo PHP_EOL . str_repeat('=', 40) . ' Signature ' . str_repeat('=', 40);
	echo PHP_EOL . $signature;
	echo PHP_EOL . str_repeat('=', 40) . ' Check signature ' . str_repeat('=', 40);
	echo PHP_EOL .  var_export($chekSignature, true);
	echo PHP_EOL . str_repeat('=', 90);
	echo PHP_EOL . PHP_EOL;

	/**
	 * An example of the creation and validation of the certificate and
	 * receiving information using a pair of public and secret key.
	 */
	$CACertificate = $cryptoCertificate->create(array(
		'commonName' => 'Sol Developer',
		'emailAddress' => 'sol.developer@gmail.com',
	), $keys['private']);
	$certificate = $cryptoCertificate->create(array(
		'countryName' => 'UK',
		'stateOrProvinceName' => 'Somerset',
		'localityName' => 'Glastonbury',
		'organizationName' => 'The Brain Room Limited',
		'organizationalUnitName' => 'PHP Documentation Team',
		'commonName' => 'Wez Furlong',
		'emailAddress' => 'wez@example.com',
	), $keys['private'], 365, $CACertificate);
	$checkCertificate = $cryptoCertificate->verify($certificate, $keys['private']);
	$certificateIssuer = $cryptoCertificate->getIssuer($certificate);
	$certificateSubject = $cryptoCertificate->getSubject($certificate);
	$certificateName = $cryptoCertificate->getName($certificate);
	echo PHP_EOL . str_repeat('=', 40) . ' Certificate ' . str_repeat('=', 40);
	echo PHP_EOL . $certificate;
	echo PHP_EOL . str_repeat('=', 40) . ' Check certificate ' . str_repeat('=', 40);
	echo PHP_EOL . var_export($checkCertificate, true);
	echo PHP_EOL . str_repeat('=', 40) . ' Certificate name ' . str_repeat('=', 40);
	echo PHP_EOL . $certificateName;
	echo PHP_EOL . str_repeat('=', 40) . ' Certificate Issuer ' . str_repeat('=', 40);
	echo PHP_EOL . var_export($certificateIssuer, true);
	echo PHP_EOL . str_repeat('=', 40) . ' Certificate Subject ' . str_repeat('=', 40);
	echo PHP_EOL . var_export($certificateSubject, true);
	echo PHP_EOL . str_repeat('=', 90);
	echo PHP_EOL . PHP_EOL;

	/**
	 * Example of creating a file .PEM.
	 */
	echo PHP_EOL . str_repeat('=', 40) . ' Create server.pem file' . str_repeat('=', 40);
	echo PHP_EOL;
	$passphrase = 'SecretPassPhrase';
	$keys = $cryptoKeys->create(CryptoKeys::KEYTYPE_RSA, 512, $passphrase);
	$decodedPrivateKey = openssl_pkey_get_private($keys['private'], $passphrase);
	$certificate = $cryptoCertificate->create(array(
		'countryName' => 'UK',
		'stateOrProvinceName' => 'Somerset',
		'localityName' => 'Glastonbury',
		'organizationName' => 'The Brain Room Limited',
		'organizationalUnitName' => 'PHP Documentation Team',
		'commonName' => 'Wez Furlong',
		'emailAddress' => 'wez@example.com',
	), $decodedPrivateKey, 365);
	echo implode(array($certificate, $keys['private']));
	echo PHP_EOL . PHP_EOL;
}
catch (CryptoException $exception)
{
	echo $exception->getMessage() . PHP_EOL;
	echo $exception->getFile() . ' : ' . $exception->getCode() . PHP_EOL;
}
