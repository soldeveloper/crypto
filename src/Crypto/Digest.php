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
 * Класс дайджест алгоритмов.
 */
class Digest
{

	/**
	 * Алгоритм хэширования SHA512.
	 */
	const SHA512 = 'sha512';

	/** * Хеши только для подписи * **/

	/**
	 * Алгоритм хэширования SHA1.
	 */
	const SHA1 = 'sha1';

	/**
	 * Алгоритм хэширования MD5.
	 */
	const MD5 = 'md5';

	/**
	 * Алгоритм хэширования DSA.
	 */
	const DSA = 'DSA';

}
