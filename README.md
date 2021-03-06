crypto
======

Crypto module provides data encryption and digital signature, it may also be used to generate certificates.

Installation
------------

#### Installing version 0.3 via Composer

* Get [Composer](http://getcomposer.org/)
* Create file composer.json if absent:

```json
{
	"repositories": [
		{
			"type": "package",
			"package": {
				"name": "soldeveloper/crypto",
				"version": "0.3",
				"source": {
					"type": "git",
					"url": "https://github.com/soldeveloper/crypto.git",
					"reference": "0.3"
				},
				"autoload": {
					"psr-0": {"Crypto\\": "src/"}
				}
			}
		}
	],
  	"require":{
		"php": ">=5.4",
		"soldeveloper/crypto": "0.3"
	}
}
```

* Run `composer update`.

#### Install latest version from source code repository

`git clone https://github.com/soldeveloper/crypto.git`

### Requirements

- **PHP** >= 5.4
- **OpenSSL** >= 0.9.6

### Usage

* See [/example/sample.php](https://github.com/soldeveloper/crypto/blob/master/example/example.php).
