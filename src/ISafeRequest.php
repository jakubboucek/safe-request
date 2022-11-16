<?php

declare(strict_types=1);

namespace JakubBoucek\SafeRequest;

use Nette\Http\IRequest;
use Nette\Http\UrlScript;

interface ISafeRequest extends IRequest
{
    /**
     * Returns the URL of the request.
     */
    public function getUrlWithUserCredential(): UrlScript;

    public function getUser(): ?string;

    public function getPassword(): ?string;
}
