<?php

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path(string $path = ''): string
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

function getLocalesAllowed(): array
{
    $locales = [];

    $localesAllowed = explode(',', env('LOCALES_ALLOWED'));

    foreach($localesAllowed as $localeAllowed) {
        $country = explode('-', $localeAllowed);

        if(!empty($country[1])) {
            if(empty($locales[$country[0]])) {
                $locales[$country[0]] = [$country[1]];
            }
            else {
                array_push($locales[$country[0]], $country[1]);
            }
        }
    }

    return $locales;
}
