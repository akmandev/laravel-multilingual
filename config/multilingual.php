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

    /**
     *
     */
    'middleware' => OzanAkman\Multilingual\Middleware\Localize::class,
];