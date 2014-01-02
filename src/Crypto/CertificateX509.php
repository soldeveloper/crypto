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
 * Class to work with a certificate X509.
 */
class CertificateX509
{

	/**
	 * Creates and returns a certificate X509.
	 * In case of error throws exception.
	 *
	 * @param array			$subject		Subject
	 * @param string		$privateKey		The secret key
	 * @param int			$days			How many days the certificate is valid
	 * @param string		$CACertificate	Signing certificate
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function create(array $subject, $privateKey, $days = 365, $CACertificate = null)
	{
		$csr = openssl_csr_new($subject, $privateKey);
		$x509Certificate = openssl_csr_sign($csr, $CACertificate, $privateKey, $days);
		if (false === $x509Certificate)
		{
			throw new Exception('An error occurred while creating the certificate.');
		}
		if (false === openssl_x509_export($x509Certificate, $certificate))
		{
			throw new Exception('Unable to get certificate.');
		}
		return $certificate;
	}

	/**
	 * Verifies the certificate.
	 * Returns a check status.
	 *
	 * @param string	$certificate	Certificate
	 * @param string	$privateKey		The secret key
	 *
	 * @return bool
	 */
	public function verify($certificate, $privateKey)
	{
		return openssl_x509_check_private_key($certificate, $privateKey);
	}

	/**
	 * Returns information about the issuer.
	 *
	 * @param string	$certificate	Certificate
	 *
	 * @return array
	 */
	public function getIssuer($certificate)
	{
		return openssl_x509_parse($certificate, false)['issuer'];
	}

	/**
	 * Returns information about the subject.
	 *
	 * @param string	$certificate	Certificate
	 *
	 * @return array
	 */
	public function getSubject($certificate)
	{
		return openssl_x509_parse($certificate, false)['subject'];
	}

	/**
	 * Returns the name of the certificate.
	 *
	 * @param string	$certificate	Certificate
	 *
	 * @return string
	 */
	public function getName($certificate)
	{
		return openssl_x509_parse($certificate, false)['name'];
	}

}
