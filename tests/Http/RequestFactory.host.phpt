<?php

/**
 * Test: Nette\Http\RequestFactory and host.
 */

declare(strict_types=1);

use Nette\Http\RequestFactory;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$_SERVER = [
    'HTTP_HOST' => 'localhost',
];
$factory = createSafeRequestFactory();
Assert::same('http://localhost/', (string)$factory->fromGlobals()->getUrl());


$_SERVER = [
    'HTTP_HOST' => 'www-x.nette.org',
];
$factory = createSafeRequestFactory();
Assert::same('http://www-x.nette.org/', (string)$factory->fromGlobals()->getUrl());


$_SERVER = [
    'HTTP_HOST' => '192.168.0.1:8080',
];
$factory = createSafeRequestFactory();
Assert::same('http://192.168.0.1:8080/', (string)$factory->fromGlobals()->getUrl());


$_SERVER = [
    'HTTP_HOST' => '[::1aF]:8080',
];
$factory = createSafeRequestFactory();
Assert::same('http://[::1af]:8080/', (string)$factory->fromGlobals()->getUrl());


$_SERVER = [
    'HTTP_HOST' => "a.cz\n",
];
$factory = createSafeRequestFactory();
Assert::same('http:/', (string)$factory->fromGlobals()->getUrl());


$_SERVER = [
    'HTTP_HOST' => 'a.cz.',
];
$factory = createSafeRequestFactory();
Assert::same('http://a.cz/', (string)$factory->fromGlobals()->getUrl());


$_SERVER = [
    'HTTP_HOST' => 'AB',
];
$factory = createSafeRequestFactory();
Assert::same('http://ab/', (string)$factory->fromGlobals()->getUrl());
