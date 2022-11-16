<?php

declare(strict_types=1);

namespace JakubBoucek\SafeRequest;

use Nette\Http\IRequest;
use Nette\Http\RequestFactory;

class SafeRequestFactory extends RequestFactory
{
    private RequestFactory $factory;

    public function __construct(RequestFactory $factory)
    {
        $this->factory = $factory;
        $this->urlFilters = &$factory->urlFilters;
    }

    public function fromGlobals(): SafeRequest
    {
        // Nette bug: https://github.com/nette/http/issues/218
        $remoteHost = !empty($_SERVER['REMOTE_HOST'])
            ? $_SERVER['REMOTE_HOST']
            : null;

        return $this->fromRequest($this->factory->fromGlobals(), $remoteHost);
    }

    /**
     * @param string|string[] $proxy
     * @return static
     */
    public function setProxy($proxy): self
    {
        $this->factory->setProxy($proxy);
        return $this;
    }

    public function setBinary(bool $binary = true): self
    {
        $this->factory->setBinary($binary);
        return $this;
    }

    public function fromRequest(IRequest $request, ?string $remoteHost = null): SafeRequest
    {
        // Nette bug: https://github.com/nette/http/issues/215
        $url = $request instanceof SafeRequest
            ? $request->getUrlWithUserCredential()
            : $request->getUrl();

        // Nette bug: https://github.com/nette/http/issues/218
        $remoteHost = ($request instanceof SafeRequest && $remoteHost !== null)
            ? $request->getRemoteHost()
            : $remoteHost;

        // Keep getter lazy and allow to GC previous SafeRequest instances
        $rawBodyCallback = $request instanceof SafeRequest
            ? $request->getRawBodyCallback()
            : static function () use ($request) {
                return $request->getRawBody();
            };

        // Parameters for compatibility with future Nette HTTP package versions
        $futureParams = ($request instanceof SafeRequest)
            ? $request->getFutureParams()
            : [];

        return new SafeRequest(
            $url,
            $request->getPost(),
            $request->getFiles(),
            $request->getCookies(),
            $request->getHeaders(),
            $request->getMethod(),
            $request->getRemoteAddress(),
            $remoteHost,
            $rawBodyCallback,
            ...$futureParams
        );
    }
}
