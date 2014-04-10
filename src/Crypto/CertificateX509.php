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
 * Сертификат.
 */
class CertificateX509
{

	/**
	 * Импорт сертификата из файла.
	 */
	const IMPORT_FROM_FILE = 1;

	/**
	 * Создание нового сертификата.
	 */
	const CREATE_NEW = 2;

	/**
	 * Импорт сертификата из параметров.
	 */
	const IMPORT_FROM_PARAMS = 3;

	/**
	 * Сертификат.
	 *
	 * @var string
	 */
	private $certificate = null;

	/**
	 * Информация про сертификат.
	 *
	 * @var array
	 */
	private $information = null;

	/**
	 * Устанавливает входные значения.
	 *
	 * @param string		$certificate		Сертификат
	 */
	private function __construct($certificate)
	{
		$this
			->setCertificate($certificate);
	}

	/**
	 * Фабрика для создания сертификата.
	 * В случае ошибки бросает исключение.
	 *
	 * @param int					$type				Способ создания сертификата
	 * @param string|resource		$x509Certificate		Данные о сертификате
	 *
	 * @return CertificateX509
	 *
	 * @throws Exception
	 */
	static public function factory($type, $x509Certificate)
	{
		switch ($type)
		{
			case self::IMPORT_FROM_FILE:
				/**
				 * Импорт сертификата из файла.
				 */
				$certificate = @file_get_contents($x509Certificate);
				if (false === $certificate)
				{
					throw new Exception('Cannot read the certificate.');
				}
				break;
			case self::CREATE_NEW:
				/**
				 * Создание нового сертификата.
				 */
			if (false === openssl_x509_export($x509Certificate, $certificate))
				{
					throw new Exception('Unable to get certificate.');
				}
				break;
			case self::IMPORT_FROM_PARAMS:
				/**
				 * Импорт сертификата из параметра.
				 */
			$certificate = $x509Certificate;
				break;
			default:
				throw new Exception('Unknown method create a certificate.');
				break;
		}
		return new self($certificate);
	}

	/**
	 * Экспорт сертификата в файл.
	 * Возвращает размер записанной информации.
	 * В случае ошибки бросает исключение.
	 *
	 * @param string		$pathToFile		Путь к файлу
	 *
	 * @return int
	 *
	 * @throws Exception
	 */
	public function exportToFile($pathToFile)
	{
		$length = @file_put_contents($pathToFile, $this->getCertificate());
		if (false === $length)
		{
			throw new Exception('Error exporting certificate.');
		}
		return $length;
	}

	/**
	 * Возвращает сертификат.
	 *
	 * @return string
	 */
	public function getCertificate()
	{
		return $this->certificate;
	}

	/**
	 * Возвращает информацию о сертификате.
	 *
	 * @return array
	 */
	public function getInformation()
	{
		return $this->information;
	}

	/**
	 * Устанавливает сертификат.
	 * Возвращает текущий объект.
	 *
	 * @param string		$certificate		Сертификат
	 *
	 * @return self
	 */
	private function setCertificate($certificate)
	{
		$this->certificate = $certificate;
		$this->information = openssl_x509_parse($this->getCertificate());
		return $this;
	}

	/**
	 * Возвращает идентификационные данные для субъекта сертификата.
	 *
	 * @return DistinguishedName
	 */
	public function getSubjectDistinguishedName()
	{
		$subject = $this->getInformation()['subject'];
		return new DistinguishedName($subject);
	}

	/**
	 * Возвращает идентификационные данные для эмитента сертификата.
	 *
	 * @return DistinguishedName
	 */
	public function getIssuerDistinguishedName()
	{
		$issuer = $this->getInformation()['issuer'];
		return new DistinguishedName($issuer);
	}

	/**
	 * Возвращает временную метку начала действия сертификата.
	 *
	 * @return int
	 */
	public function getValidFromTimeStamp()
	{
		return $this->getInformation()['validFrom_time_t'];
	}

	/**
	 * Возвращает временную метку окончания действия сертификата.
	 *
	 * @return int
	 */
	public function getValidToTimeStamp()
	{
		return $this->getInformation()['validTo_time_t'];
	}

	/**
	 * Возвращает номер сертификата.
	 *
	 * @return int
	 */
	public function getSerialNumber()
	{
		return $this->getInformation()['serialNumber'];
	}

	/**
	 * Возвращает хеш для сертификата.
	 *
	 * @return string
	 */
	public function getHash()
	{
		return $this->getInformation()['hash'];
	}

	/**
	 * Возвращает открытый ключ сертификата.
	 *
	 * @return string
	 */
	public function getPublicKey()
	{
		return openssl_pkey_get_details(openssl_pkey_get_public($this->getCertificate()))['key'];
	}

	/**
	 * Возвращает сертификат.
	 *
	 * @return string|null
	 */
	public function __toString()
	{
		return $this->getCertificate();
	}

}
