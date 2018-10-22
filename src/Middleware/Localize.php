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
     * @throws \Exception
     */
    private function selectedLocale()
    {
        $this->selectedLocale = cookie('locale')->getValue() ?? default_locale()->code;
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
     */
    private function handleRedirect($request, Closure $next)
    {
        $requestLocale = $this->getLocaleFromRequest();
        $redirectTo = $this->pattern === 'domain'
            ? $this->buildDomainUrl()
            : $this->buildPathUrl();

        if (!$requestLocale) {
            return redirect($redirectTo);
        }

        if (!array_key_exists($requestLocale->code, $this->locales)) {

            return redirect($redirectTo);
        }

        return $next($request);
    }

    /**
     * Build up a url for domain as localized.
     * @return string
     */
    private function buildDomainUrl()
    {
        return $this->scheme . '://' . $this->selectedLocale . '.' . $this->httpHost;
    }

    /**
     * Build up a url for path as localized.
     * @return string
     */
    private function buildPathUrl()
    {
        return $this->scheme . '://' . $this->httpHost . '/' . $this->selectedLocale;
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
        $host = $this->httpHost;
        preg_match('/^[A-Za-z]{2}/', $host, $code, 'PREG_OFFSET_CAPTURE', 0);

        if ($code) {
            $this->getLocale($code);
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
}
