<?php

if (@!include __DIR__ . '/../vendor/autoload.php') {
    echo 'Install Nette Tester using `composer install`';
    exit(1);
}

\Tester\Dumper::$maxLength = 1000;

function createSafeRequestFactory(): \JakubBoucek\SafeRequest\SafeRequestFactory
{
    return new \JakubBoucek\SafeRequest\SafeRequestFactory(new \Nette\Http\RequestFactory());
}

function toSafeRequest(\Nette\Http\Request $request) : \JakubBoucek\SafeRequest\SafeRequest
{
    return createSafeRequestFactory()->fromRequest($request);
}

function test(string $title, Closure $function): void
{
    $function();
}
