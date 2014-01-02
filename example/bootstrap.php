<?php

/**
 * @category	SolDeveloper
 * @package		Crypto Sample
 * @author		Sol Developer <sol.developer@gmail.com>
 * @copyright	Copyright (c) 2013 Sol Developer (https://github.com/soldeveloper/crypto)
 * @license		http://www.gnu.org/copyleft/lesser.html
 */

ini_set('display_errors', 'on');
error_reporting(E_ALL);

set_include_path(
	dirname(__FILE__) . '/../src' . PATH_SEPARATOR . get_include_path()
);

require_once 'Crypto/Exception.php';
require_once 'Crypto/Properties.php';
require_once 'Crypto/Keys.php';
require_once 'Crypto/Encode.php';
require_once 'Crypto/Decode.php';
require_once 'Crypto/CertificateX509.php';
