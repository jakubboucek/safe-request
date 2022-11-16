<?php

declare(strict_types=1);

namespace JakubBoucek\SafeRequest;

use Nette\Http\Request;
use Nette\Http\UrlImmutable;
use Nette\Http\UrlScript;

class SafeRequest extends Request implements ISafeRequest
{
    private ?UrlScript $safeUrl = null;
    private ?string $remoteHost;
    /** @var array|null Parameters for compatibility with future Nette HTTP package versions */
    private ?array $futureParams;
    /** @var callable|null Keep getter lazy and allow to GC previous SafeRequest instances */
    private $rawBodyCallback;

    public function __construct(
        UrlScript $url,
        ?array $post = null,
        ?array $files = null,
        ?array $cookies = null,
        ?array $headers = null,
        ?string $method = null,
        ?string $remoteAddress = null,
        ?string $remoteHost = null,
        ?callable $rawBodyCallback = null,
        ...$futureParams
    ) {
        $this->remoteHost = $remoteHost;
        $this->futureParams = $futureParams;
        $this->rawBodyCallback = $rawBodyCallback;

        parent::__construct(
            $url,
            $post,
            $files,
            $cookies,
            $headers,
            $method,
            $remoteAddress,
            $remoteHost,
            $rawBodyCallback,
            ...$futureParams
        );
    }

    public function withUrl(UrlScript $url)
    {
        $dolly = parent::withUrl($url);
        $dolly->safeUrl = null;
        return $dolly;
    }

    /**
     * Returns the URL of the request saninited from User info.
     *
     * @see https://github.com/nette/http/issues/215
     */
    public function getUrl(): UrlScript
    {
        if ($this->safeUrl === null) {
            $this->safeUrl = parent::getUrl()->withoutUserInfo();
        }

        return $this->safeUrl;
    }

    /**
     * Returns the URL of the request with User info.
     *
     * @see https://github.com/nette/http/issues/215
     */
    public function getUrlWithUserCredential(): UrlScript
    {
        return parent::getUrl();
    }

    public function getUser(): ?string
    {
        return parent::getUrl()->getUser();
    }

    public function getPassword(): ?string
    {
        return parent::getUrl()->getPassword();
    }

    /**
     * @see https://github.com/nette/http/issues/218
     */
    public function getRemoteHost(): ?string
    {
        return $this->remoteHost;
    }

    public function getReferer(): ?UrlImmutable
    {
        // Nette bug: https://github.com/nette/http/pull/210
        $referer = $this->getHeader('referer');
        if ($referer !== null && @parse_url($referer) === false) {
            return null;
        }

        return parent::getReferer();
    }


    /**
     * @internal Method for compatibility with future Nette HTTP package versions
     */
    public function getFutureParams(): ?array
    {
        return $this->futureParams;
    }

    /**
     * @internal Bridge for keep the getter lazy and allow to GC previous SafeRequest instances
     */
    public function getRawBodyCallback(): ?callable
    {
        return $this->rawBodyCallback;
    }
}
