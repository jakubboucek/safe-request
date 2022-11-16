<?php

/**
 * Test: Nette\Http\RequestFactory and method.
 */

declare(strict_types=1);

use Nette\Http\RequestFactory;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$_SERVER = [
    'REQUEST_METHOD' => 'GET',
    'HTTP_X_HTTP_METHOD_OVERRIDE' => 'PATCH',
];
$factory = createSafeRequestFactory();
Assert::same('GET', $factory->fromGlobals()->getMethod());


$_SERVER = [
    'REQUEST_METHOD' => 'POST',
    'HTTP_X_HTTP_METHOD_OVERRIDE' => 'PATCH',
];
$factory = createSafeRequestFactory();
Assert::same('PATCH', $factory->fromGlobals()->getMethod());


$_SERVER = [
    'REQUEST_METHOD' => 'POST',
    'HTTP_X_HTTP_METHOD_OVERRIDE' => ' *',
];
$factory = createSafeRequestFactory();
Assert::same('POST', $factory->fromGlobals()->getMethod());
