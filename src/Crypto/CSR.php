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
 * Запрос на подпись сертификата.
 */
class CSR
{

	/**
	 * Создание нового запроса.
	 */
	const CREATE_NEW = 1;

	/**
	 * Запрос на подпись.
	 *
	 * @var string
	 */
	private $csr = null;

	/**
	 * Данные запроса на подпись.
	 *
	 * @var DistinguishedName
	 */
	private $distinguishedName = null;

	/**
	 * Устанавливает входные значения.
	 *
	 * @param $csr
	 */
	private function __construct($csr)
	{
		$this
			->setCSRDetails($csr);
	}

	/**
	 * Фабрика создания запроса на подпись сертификата.
	 * В случае ошибки бросает исключение.
	 *
	 * @param int		$type			Способ создания запроса
	 * @param array	$subject			Данные запроса
	 * @param string	$privateKey		Закрытый ключ
	 *
	 * @return CSR
	 *
	 * @throws Exception
	 */
	static public function factory($type, array $subject, $privateKey)
	{
		switch ($type)
		{
			case self::CREATE_NEW:
				openssl_csr_export(openssl_csr_new($subject, $privateKey), $csr);
				break;
			default:
				throw new Exception('Unknown method create certificate.');
				break;
		}
		return new self($csr);
	}

	/**
	 * Возвращает открытый ключ запроса.
	 *
	 * @return string
	 */
	public function getPublicKey()
	{
		return openssl_pkey_get_details(openssl_csr_get_public_key($this->getCSR()))['key'];
	}

	/**
	 * Возвращает запрос на подпись сертификата.
	 *
	 * @return string
	 */
	public function getCSR()
	{
		return $this->csr;
	}

	/**
	 * Возвращает данные запроса на подпись.
	 *
	 * @return DistinguishedName
	 */
	public function getDistinguishedName()
	{
		return $this->distinguishedName;
	}

	/**
	 * Устанавливает данные запроса на подпись.
	 * Возвращает текущий объект.
	 *
	 * @param string		$csr		Запрос на подпись
	 *
	 * @return self
	 */
	private function setCSRDetails($csr)
	{
		$this->csr = $csr;
		$this->distinguishedName = new DistinguishedName(openssl_csr_get_subject($csr));
		return $this;
	}

	/**
	 * Возвращает запрос на подпись сертификата.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getCSR();
	}

}
