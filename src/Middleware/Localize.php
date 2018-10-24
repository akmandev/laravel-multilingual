<?php

namespace OzanAkman\Multilingual\Middleware;

use Closure;
use OzanAkman\Multilingual\Models\Locale;
use OzanAkman\Multilingual\Exceptions\PatternException;

class Localize
{
    /**
     * Scheme http or https.
     * @var
     */
    private $scheme;

    /**
     * Domain of the current request.
     * @var
     */
    private $httpHost;

    /**
     * Available locales.
     * @var
     */
    private $locales;

    /**
     * Localization pattern.
     * @var
     */
    private $pattern;

    /**
     * The method name to validate pattern.
     * @var
     */
    private $method;

    /**
     * The locale of user's choice.
     * @var
     */
    private $selectedLocale;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @throws \Exception
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $this->locales();
        $this->pattern();
        $this->method();
        $this->selectedLocale();
        $this->schemeAndHttpHost($request);

        if (method_exists($this, $this->method)) {
            return $this->handleRedirect($request, $next);
        }

        throw PatternException::invalidPattern($this->pattern);
    }

    /**
     * Get all available locales.
     * @throws \Exception
     */
    private function locales()
    {
        $this->locales = locales()->toArray();
    }

    /**
     * Get the url pattern from the config.
     */
    private function pattern()
    {
        $this->pattern = config('multilingual.pattern');
    }

    /**
     * Get method name for the given pattern.
     */
    private function method()
    {
        $this->method = $this->methodName($this->pattern);
    }

    /**
     * Get pre-selected locale from the user's choice.
     * @param string|null $selectedLocale
     * @throws \Exception
     */
    private function selectedLocale($selectedLocale = null)
    {
        $this->selectedLocale = $selectedLocale
            ? $selectedLocale
            : (cookie('locale')->getValue() ?? default_locale()->code);
    }

    /**
     * Get scheme and http host.
     * @param  \Illuminate\Http\Request $request
     * @throws \Exception
     */
    private function schemeAndHttpHost($request)
    {
        $this->scheme = $request->getScheme();
        $this->httpHost = $request->getHttpHost();
    }

    /**
     * Get method name for the given pattern as studly case.
     * @param $pattern
     * @return string
     */
    private function methodName($pattern)
    {
        return 'build' . studly_case($pattern) . 'Url';
    }

    /**
     * Redirect user to the valid url.
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    private function handleRedirect($request, Closure $next)
    {
        $requestLocale = $this->getLocaleFromRequest();

        if ($requestLocale) {
            $this->selectedLocale($requestLocale->code);
        } else {
            $redirectTo = $this->getRedirectUrl();
            if (!$requestLocale || (!array_key_exists($requestLocale->code, $this->locales))) {
                return redirect($redirectTo);
            }
        }
        return $next($request);
    }

    private function getRedirectUrl()
    {
        return $this->pattern === 'domain'
            ? $this->buildDomainUrl()
            : $this->buildPathUrl();
    }

    /**
     * Build up a url for domain as localized.
     * @return string
     */
    private function buildDomainUrl()
    {
        return $this->scheme . '://' . $this->selectedLocale . '.' . $this->extractDomain($this->httpHost);
    }

    /**
     * Build up a url for path as localized.
     * @return string
     */
    private function buildPathUrl()
    {
        return $this->scheme . '://' . $this->extractDomain($this->httpHost) . '/' . $this->selectedLocale;
    }

    /**
     * Get locale by the current request
     * @return mixed|null|\OzanAkman\Multilingual\Models\Locale
     */
    private function getLocaleFromRequest()
    {
        return $this->pattern === 'domain'
            ? $this->getLocaleFromDomain()
            : $this->getLocaleFromPath();
    }

    /**
     * Try to parse locale from the domain.
     * @return \OzanAkman\Multilingual\Models\Locale|null
     */
    private function getLocaleFromDomain()
    {
        $code = $this->extractSubdomain();

        if ($code) {
            return $this->getLocale($code);
        }

        return null;
    }

    /**
     * Try to parse locale from the path.
     * @return mixed
     */
    private function getLocaleFromPath()
    {
        $code = request()->segment(1);
        return $this->getLocale($code);
    }

    /**
     * Get locale by the given code.
     * @param $code
     * @return mixed
     */
    private function getLocale($code)
    {
        return Locale::where('code', $code)->where('enabled', 1)->first();
    }

    /**
     * Extract main domain from http host
     * @param string $host
     * @return mixed
     */
    private function extractDomain($host = null)
    {
        $host = $host ?? $this->httpHost;
        $regexPattern = '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i';
        return preg_match($regexPattern, $host, $matches)
            ? $matches['domain']
            : $host;
    }

    /**
     * Extract first subdomain from http host.
     * @return string
     */
    private function extractSubdomain()
    {
        $domain = $this->extractDomain();
        return rtrim(strstr($this->httpHost, $domain, true), '.');
    }
}
