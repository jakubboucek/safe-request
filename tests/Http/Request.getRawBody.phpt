<?php

/**
 * Test: Nette\Http\Request getRawBody.
 */

declare(strict_types=1);

use Nette\Http;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
    $request = new Http\Request(new Http\UrlScript, null, null, null, null, null, null, null, function () {
        return 'raw body';
    });
    $request = toSafeRequest($request);

    Assert::same('raw body', $request->getRawBody());
});


test('', function () {
    $request = new Http\Request(new Http\UrlScript);
    $request = toSafeRequest($request);

    Assert::null($request->getRawBody());
});
