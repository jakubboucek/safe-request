<?php

/**
 * Test: Nette\Http\RequestFactory and user and password.
 */

declare(strict_types=1);

use Nette\Http\RequestFactory;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$_SERVER = [
    'PHP_AUTH_USER' => 'user',
    'PHP_AUTH_PW' => 'password',
];
$factory = createSafeRequestFactory();
Assert::same('user', $factory->fromGlobals()->getUrlWithUserCredential()->getUser());
Assert::same('password', $factory->fromGlobals()->getUrlWithUserCredential()->getPassword());
Assert::same('user', $factory->fromGlobals()->getUser());
Assert::same('password', $factory->fromGlobals()->getPassword());
Assert::same('', $factory->fromGlobals()->getUrl()->getUser());
Assert::same('', $factory->fromGlobals()->getUrl()->getPassword());


$_SERVER = [];
$factory = createSafeRequestFactory();
Assert::same('', $factory->fromGlobals()->getUrl()->getUser());
Assert::same('', $factory->fromGlobals()->getUrl()->getPassword());
Assert::same('', $factory->fromGlobals()->getUser());
Assert::same('', $factory->fromGlobals()->getPassword());
