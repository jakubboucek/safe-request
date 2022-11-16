# Safe HTTP Request

Safe HTTP Request: extension of [Nette Request object](https://doc.nette.org/cs/http/request), but sanitized from known
safety issues.

## Sanitized issues

1. Using [Nette `RequestFactory`](https://github.com/nette/http/blob/master/src/Http/RequestFactory.php) **can cause to
   leak user's Basic auth credentials**, because it by-default creating the 
   [`Url` object](https://github.com/nette/http/blob/master/src/Http/Url.php) with that and used to create back-link URLs
   (issue [nette/http#215](https://github.com/nette/http/issues/215)). It's fixed at
   [PR#211](https://github.com/nette/http/pull/211), but not yet released (and probably fix it will be never available
   for PHP < 8.0).
2. Call of [`Request->getReferer()`](https://github.com/nette/http/blob/v3.1.6/src/Http/Request.php#L233-L241)
   method can cause to crash App, because evil client can call request with invalid `Referer` header
   (issue [nette/http#215](https://github.com/nette/http/issues/215)). It's fixed at
   eb3f6d1980c0b2552a13f5eb944d37515072c998, but only with mark method as deprecated and not yet released (and probably
   fix it will be never available for PHP < 8.0).
3. Call of [`Request->getRemoteHost()`](https://github.com/nette/http/blob/v3.1.6/src/Http/Request.php#L296-L306)
   method can cause to slow or stuck your App, because `Request` object is trying contact DNS server on every read of
   property (issue [nette/http#218](https://github.com/nette/http/issues/218)).

Package requires the [`nette/http`](https://packagist.org/packages/nette/http) package, it's not replacing it, just
extending it.

## Features

- Removes sensitive data from `Request`->`Url` object.
- Fixes app crash on request with invalid Referer.
- Fixes performance issue with Remote Host.

## Install

```shell
composer require jakubboucek/safe-request
```

## Usage

Instead:
```php
$request = (new \Nette\Http\RequestFactory)->fromGlobals();
```

Use:
```php
$request = (new \JakubBoucek\SafeRequest\SafeRequestFactory)->fromGlobals();
```

You can get Basic Auth User Credential with:
```php
$user = $request->getUser();
$pass = $request->getPassword();
```

You can get `Url` with Basic Auth User Credential with:
```php
$url = $request->getUrlWithUserCredential();
echo $url; // http://user:password@example.com/
```

## Contributing
Please don't hesitate send Issue or Pull Request.

## Security
If you discover any security related issues, please email pan@jakubboucek.cz instead of using the issue tracker.

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.

### Origin code licences
- [New BSD License](https://github.com/nette/latte/blob/master/license.md#new-bsd-license)
- [GNU General Public License](https://github.com/nette/latte/blob/master/license.md#gnu-general-public-license)

Copyright (c) 2004, 2014 David Grudl (https://davidgrudl.com) All rights reserved.
Please see [License File](https://github.com/nette/latte/blob/master/license.md) for more information.
