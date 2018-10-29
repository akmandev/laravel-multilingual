<?php

return [
    /*
     * How we should treat urls to get the selected language?
     * http://{domain}.site.com
     * http://site.com/{path}
     *
     * Supported: "domain", "path"
     */
    'pattern' => 'path',

    /*
     * Localization middleware to handle user redirects.
     */
    'middleware' => OzanAkman\Multilingual\Middleware\Localize::class,
];
