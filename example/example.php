<?php

/**
 * @category		SolDeveloper
 * @package		Crypto
 * @author		Sol Developer <sol.developer@gmail.com>
 * @copyright		Copyright (c) 2014 Sol Developer (https://github.com/soldeveloper/crypto)
 * @license		http://www.gnu.org/copyleft/lesser.html
 * @sample
 */

require_once 'bootstrap.php';

use Crypto\Exception;
use Crypto\SymmetricCipher;
use Crypto\AsymmetricCipher;
use Crypto\KeyStore;
use Crypto\Digest;
use Crypto\CSR;
use Crypto\Signature;

try
{

	/**
	 * The source text for encoding.
	 */
	$sourceText = 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.';
	echo PHP_EOL . str_repeat('=', 40) . ' Source text ' . str_repeat('=', 40);
	echo PHP_EOL . $sourceText;
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Access to a symmetric cipher.
	 */
	$symmetricCipher = new SymmetricCipher();

	/**
	 * Password.
	 */
	$password = 'Hello world !!!';

	/**
	 * Data Encryption.
	 */
	$cipherText = $symmetricCipher->encrypt(SymmetricCipher::AES_256, $password, $sourceText);
	echo PHP_EOL . str_repeat('=', 40) . ' Encrypted text ' . str_repeat('=', 40);
	echo PHP_EOL . $cipherText;
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Data Decryption.
	 */
	$decryptedText = $symmetricCipher->decrypt(SymmetricCipher::AES_256, $password, $cipherText);
	echo PHP_EOL . str_repeat('=', 40) . ' Decrypted text ' . str_repeat('=', 40);
	echo PHP_EOL . $decryptedText;
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Create a public and private key.
	 */
	$keyStorage = KeyStore::factory(KeyStore::CREATE_NEW, array(
		'digest' => Digest::SHA512,
		'keyType' => KeyStore::KEYTYPE_RSA,
		'keyBits' => 384, // Minimum
		'passPhrase' => null,
	));

	/**
	 * Create a public and private key.
	 */
	$keyStorage2 = KeyStore::factory(KeyStore::CREATE_NEW, array(
		'digest' => Digest::SHA512,
		'keyType' => KeyStore::KEYTYPE_RSA,
		'keyBits' => 384, // Minimum
		'passPhrase' => null,
	));

	echo PHP_EOL . str_repeat('=', 40) . ' Public key #1 ' . str_repeat('=', 40);
	echo PHP_EOL . $keyStorage->getPublicKey();
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	echo PHP_EOL . str_repeat('=', 40) . ' Public key details #1 ' . str_repeat('=', 40);
	echo PHP_EOL . var_export($keyStorage->getPublicKeyDetails(), true);
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	echo PHP_EOL . str_repeat('=', 40) . ' Private key #1 ' . str_repeat('=', 40);
	echo PHP_EOL . $keyStorage->getPrivateKey();
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	echo PHP_EOL . str_repeat('=', 40) . ' Private key details #1 ' . str_repeat('=', 40);
	echo PHP_EOL . var_export($keyStorage->getPrivateKeyDetails(), true);
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	echo PHP_EOL . str_repeat('=', 40) . ' Public key #2 ' . str_repeat('=', 40);
	echo PHP_EOL . $keyStorage2->getPublicKey();
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	echo PHP_EOL . str_repeat('=', 40) . ' Public key details #2 ' . str_repeat('=', 40);
	echo PHP_EOL . var_export($keyStorage2->getPublicKeyDetails(), true);
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	echo PHP_EOL . str_repeat('=', 40) . ' Private key #2 ' . str_repeat('=', 40);
	echo PHP_EOL . $keyStorage2->getPrivateKey();
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	echo PHP_EOL . str_repeat('=', 40) . ' Private key details #2 ' . str_repeat('=', 40);
	echo PHP_EOL . var_export($keyStorage2->getPrivateKeyDetails(), true);
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	$asymmetricCipher = new AsymmetricCipher();

	$sourceText = '1234567890';
	$sourceText .= '1234567890';
	$sourceText .= '1234567890';
	$sourceText .= '1234567';

	/**
	 * Encrypts data with private key.
	 */
	$text = $asymmetricCipher->encryptByPrivateKey($sourceText, $keyStorage->getPrivateKey());
	echo PHP_EOL . str_repeat('=', 40) . ' Encrypt By Private Key  ' . str_repeat('=', 40);
	echo PHP_EOL . $text;
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Decrypts data with public key.
	 */
	$text = $asymmetricCipher->decryptByPublicKey($text, $keyStorage->getPublicKey());
	echo PHP_EOL . str_repeat('=', 40) . ' Decrypt By Public Key  ' . str_repeat('=', 40);
	echo PHP_EOL . $text;
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Encrypts data with public key.
	 */
	$text = $asymmetricCipher->encryptByPublicKey($sourceText, $keyStorage->getPublicKey());
	echo PHP_EOL . str_repeat('=', 40) . ' Encrypt By Public Key  ' . str_repeat('=', 40);
	echo PHP_EOL . $text;
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Decrypts data with private key.
	 */
	$text = $asymmetricCipher->decryptByPrivateKey($text, $keyStorage->getPrivateKey());
	echo PHP_EOL . str_repeat('=', 40) . ' Decrypt By Private Key  ' . str_repeat('=', 40);
	echo PHP_EOL . $text;
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Creating and verifying digital signatures using a pair of public and private key.
	 */
	$signature = new Signature();
	$sign = $signature->signData($sourceText, $keyStorage->getPrivateKey());
	echo PHP_EOL . str_repeat('=', 40) . ' Data signature by private key ' . str_repeat('=', 40);
	echo PHP_EOL . $sign;
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	echo PHP_EOL . str_repeat('=', 40) . ' Verify signature by public key ' . str_repeat('=', 40);
	echo PHP_EOL . var_export($signature->verifySignData($sourceText, $sign, $keyStorage->getPublicKey()), true);
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Create request to create certificate #1.
	 */
	$csr1 = CSR::factory(CSR::CREATE_NEW, array(
		'commonName' => 'Sol Developer',
		'emailAddress' => 'sol.developer@gmail.com',
	), $keyStorage->getPrivateKey());

	/**
	 * Create request to create certificate #2.
	 */
	$csr2 = CSR::factory(CSR::CREATE_NEW, array(
		'countryName' => 'US',								// AU
		'stateOrProvinceName' => 'Somerset',					// Some-State
		'localityName' => 'Glastonbury',						// null
		'organizationName' => 'The Brain Room Limited',		// Internet Widgits Pty Ltd
		'organizationalUnitName' => 'PHP Documentation Team',	// null
		'commonName' => 'Wez Furlong',						// null
		'emailAddress' => 'wez@example.com',					// null
	), $keyStorage2->getPrivateKey());

	echo PHP_EOL . str_repeat('=', 40) . ' CSR #1  ' . str_repeat('=', 40);
	echo PHP_EOL . $csr1->getCSR();
	echo PHP_EOL . $csr1->getPublicKey();
	$dn = $csr1->getDistinguishedName();
	echo PHP_EOL . var_export(array(
			'countryName' => $dn->getCountryName(),
			'stateOrProvinceName' => $dn->getStateOrProvinceName(),
			'localityName' => $dn->getLocalityName(),
			'organizationName' => $dn->getOrganizationName(),
			'organizationalUnitName' => $dn->getOrganizationalUnitName(),
			'commonName' => $dn->getCommonName(),
			'emailAddress' => $dn->getEmailAddress(),
		), true);
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	echo PHP_EOL . str_repeat('=', 40) . ' CSR #2  ' . str_repeat('=', 40);
	echo PHP_EOL . $csr2->getCSR();
	echo PHP_EOL . $csr2->getPublicKey();
	$dn = $csr2->getDistinguishedName();
	echo PHP_EOL . var_export(array(
			'countryName' => $dn->getCountryName(),
			'stateOrProvinceName' => $dn->getStateOrProvinceName(),
			'localityName' => $dn->getLocalityName(),
			'organizationName' => $dn->getOrganizationName(),
			'organizationalUnitName' => $dn->getOrganizationalUnitName(),
			'commonName' => $dn->getCommonName(),
			'emailAddress' => $dn->getEmailAddress()
		), true);
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Create a self-signed certificate.
	 */
	$certificateX509_1 = $signature->signX509Certificate($csr1, $keyStorage->getPrivateKey(), 365, 11);
	echo PHP_EOL . str_repeat('=', 40) . ' Certificate #1 ' . str_repeat('=', 40);
	echo PHP_EOL . PHP_EOL . $certificateX509_1->getCertificate();
	echo PHP_EOL . 'Subjec: ' . var_export($certificateX509_1->getSubjectDistinguishedName(), true) . PHP_EOL;
	echo PHP_EOL . 'Issuer: ' . var_export($certificateX509_1->getIssuerDistinguishedName(), true) . PHP_EOL;
	echo PHP_EOL . 'Valid: ' . date('Y-m-d H:i:s', $certificateX509_1->getValidFromTimeStamp()) . ' - ' . date('Y-m-d H:i:s', $certificateX509_1->getValidToTimeStamp());
	echo PHP_EOL . 'Serial: ' . $certificateX509_1->getSerialNumber();
	echo PHP_EOL . 'Hash: ' . $certificateX509_1->getHash();
	echo PHP_EOL . PHP_EOL . $certificateX509_1->getPublicKey();
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Create a certificate signed by another certificate.
	 */
	$certificateX509_2 = $signature->signX509Certificate($csr2, $keyStorage->getPrivateKey(), 365, 22, $certificateX509_1);
	echo PHP_EOL . str_repeat('=', 40) . ' Certificate #2 ' . str_repeat('=', 40);
	echo PHP_EOL . PHP_EOL . $certificateX509_2->getCertificate();
	echo PHP_EOL . 'Subjec: ' . var_export($certificateX509_2->getSubjectDistinguishedName(), true) . PHP_EOL;
	echo PHP_EOL . 'Issuer: ' . var_export($certificateX509_2->getIssuerDistinguishedName(), true) . PHP_EOL;
	echo PHP_EOL . 'Valid: ' . date('Y-m-d H:i:s', $certificateX509_2->getValidFromTimeStamp()) . ' - ' . date('Y-m-d H:i:s', $certificateX509_2->getValidToTimeStamp());
	echo PHP_EOL . 'Serial: ' . $certificateX509_2->getSerialNumber();
	echo PHP_EOL . 'Hash: ' . $certificateX509_2->getHash();
	echo PHP_EOL . PHP_EOL . $certificateX509_2->getPublicKey();
	echo PHP_EOL . str_repeat('=', 90) . PHP_EOL . PHP_EOL;

	/**
	 * Verification certificate.
	 */
	echo PHP_EOL . str_repeat('=', 30) . ' Verify certificate #1 by Private Key #1' . str_repeat('=', 30);
	echo PHP_EOL . var_export($signature->verifySignX509Certificate($certificateX509_1, $keyStorage->getPrivateKey()), true);

	echo PHP_EOL . str_repeat('=', 30) . ' Verify certificate #2 by Private Key #2 ' . str_repeat('=', 30);
	echo PHP_EOL . var_export($signature->verifySignX509Certificate($certificateX509_2, $keyStorage2->getPrivateKey()), true);

	echo PHP_EOL . PHP_EOL;

}
catch (Exception $exception)
{
	echo $exception->getMessage() . PHP_EOL;
	echo $exception->getFile() . ' : ' . $exception->getCode() . PHP_EOL;
}
