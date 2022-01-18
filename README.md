# header-utils
![Packagist Version](https://img.shields.io/packagist/v/hyqo/http-headers?style=flat-square)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/hyqo/http-headers?style=flat-square)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/hyqo/http-headers/run-tests?style=flat-square)

## Install

```sh
composer require hyqo/http-headers
```

## Usage

### Forwarded ([MDN](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Forwarded))

```php
use Hyqo\HTTP\Headers;

$headers = new Headers(['Forwarded'=>'for=192.0.2.60; For="[2001:db8:cafe::17]:4711"; proto=https; by=203.0.113.43'])
$headers->getForwarded()
```
```text
array(3) {
  ["X-Forwarded-For"]=>
  array(2) {
    [0]=>
    string(10) "192.0.2.60"
    [1]=>
    string(24) "[2001:db8:cafe::17]:4711"
  }
  ["X-Forwarded-Proto"]=>
  string(5) "https"
  ["X-Forwarded-Host"]=>
  string(12) "203.0.113.43"
}
```
### X-Forwarded-For ([MDN](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Forwarded-For))

```php
use Hyqo\HTTP\Headers;

$headers = new Headers(['X-Forwarded-For'=>'192.0.2.60, "[2001:db8:cafe::17]:4711"'])
$headers->getXForwardedFor()
```
```text
array(2) {
[0]=>
string(10) "192.0.2.60"
[1]=>
string(24) "[2001:db8:cafe::17]:4711"
}
```

### X-Forwarded-Proto ([MDN](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Forwarded-Proto))

```php
use Hyqo\HTTP\Headers;

$headers = new Headers(['X-Forwarded-Proto'=>'https'])
$headers->getXForwardedProto(); //https
```
