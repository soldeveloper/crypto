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
 * Идентификационные данные.
 */
class DistinguishedName
{

	/**
	 * Детали.
	 *
	 * @var array
	 */
	private $details = null;

	/**
	 * Устанавливает входные значения.
	 *
	 * @param array		$details		Детали
	 */
	function __construct(array $details)
	{
		$this
			->setDetails($details);
	}

	/**
	 * Возвращает название страны.
	 *
	 * @return null|string
	 */
	public function getCountryName()
	{
		return $this->getDetails('C');
	}

	/**
	 * Возвращает имя штата или провинции.
	 *
	 * @return null|string
	 */
	public function getStateOrProvinceName()
	{
		return $this->getDetails('ST');
	}

	/**
	 * Возвращает локальное название.
	 *
	 * @return null|string
	 */
	public function getLocalityName()
	{
		return $this->getDetails('L');
	}

	/**
	 * Возвращает название организации.
	 *
	 * @return null|string
	 */
	public function getOrganizationName()
	{
		return $this->getDetails('O');
	}

	/**
	 * Возвращает название подразделения.
	 *
	 * @return null|string
	 */
	public function getOrganizationalUnitName()
	{
		return $this->getDetails('OU');
	}

	/**
	 * Возвращает собственное название.
	 *
	 * @return null|string
	 */
	public function getCommonName()
	{
		return $this->getDetails('CN');
	}

	/**
	 * Возвращает электронный адрес.
	 *
	 * @return null|string
	 */
	public function getEmailAddress()
	{
		return $this->getDetails('emailAddress');
	}

	/**
	 * Возвращает значение по ключу из списка деталей.
	 *
	 * @param string		$key		Ключ
	 *
	 * @return null|string
	 */
	private function getDetails($key)
	{
		if (isset($this->details[$key]))
		{
			return $this->details[$key];
		}
		return null;
	}

	/**
	 * Устанавливает список деталей.
	 * Возвращает текущий объект.
	 *
	 * @param array	$details		Детали
	 *
	 * @return self
	 */
	private function setDetails(array $details)
	{
		$this->details = $details;
		return $this;
	}

}
