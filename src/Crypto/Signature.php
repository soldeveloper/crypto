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
 * Класс работы с цифровыми подписями.
 */
class Signature
{

	/**
	 * Подписывает переданные данные с помощью закрытого ключа.
	 * Возвращает подпись.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string	$data			Подписываемые данные
	 * @param string	$privateKey		Закрытый ключ
	 * @param string	$signatureAlg		Дайджест алгоритм
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function signData($data, $privateKey, $signatureAlg = Digest::SHA1)
	{
		if (false === openssl_sign($data, $signature, $privateKey, $signatureAlg))
		{
			throw new Exception('Error create signature.');
		}
		return $signature;
	}

	/**
	 * Подписывает запрос на создание сертификата.
	 * Возвращает подписанный сертификат.
	 *
	 * @param CSR|string					$csr					Запрос на подпись сертификата
	 * @param string						$privateKey			Закрытый ключ подписывающего
	 * @param int							$days				Количество дней действия подписи(сертификата)
	 * @param int							$serial				Серийный номер сертификата(по данным подписавшего)
	 * @param null	|string|CertificateX509		$certificateCacert		Сертификат подписывающего
	 *
	 * @return CertificateX509
	 */
	public function signX509Certificate($csr, $privateKey, $days = 365, $serial = 0, $certificateCacert = null)
	{
		$configargs = array();
		$x509Certificate = openssl_csr_sign('' . $csr, $certificateCacert, $privateKey, $days, $configargs, $serial);
		return CertificateX509::factory(CertificateX509::CREATE_NEW, $x509Certificate);
	}

	/**
	 * Возвращает результат проверки подписи данных.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string		$data			Подписанные данные
	 * @param string		$signature		Подпись
	 * @param string		$publicKey		Открытый ключ
	 * @param string		$signatureAlg		Дайджест алгоритм
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	public function verifySignData($data, $signature, $publicKey, $signatureAlg = Digest::SHA1)
	{
		if (-1 == $status = openssl_verify($data, $signature, $publicKey, $signatureAlg))
		{
			throw new Exception('Error verify signature.');
		}
		return (1 == $status);
	}

	/**
	 * Возвращает результат проверки подписи сертификата.
	 *
	 * @param CertificateX509|string		$certificate		Проверяемый сертификат
	 * @param string					$privateKey		Закрытый ключ
	 *
	 * @return bool
	 */
	public function verifySignX509Certificate($certificate, $privateKey)
	{
		return openssl_x509_check_private_key($certificate, $privateKey);
	}

}
