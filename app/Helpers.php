<?php

use Carbon\Translator;
use Illuminate\Console\Application;
use Illuminate\Http\RedirectResponse;



if (!function_exists('translate')) {
    function translate($key): array|string|Translator|Application|null
    {
        return __($key);
    }
}


if (!function_exists('error')) {
    function error($message = null): RedirectResponse
    {
        flash(translate($message ?? 'messages.Wrong'))->error();
        return back();
    }
}




