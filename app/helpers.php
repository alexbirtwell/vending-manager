<?php
function currency_format($value)
{
     return config('business.currency.symbol') . number_format($value, config('business.currency.precision'), config('business.currency.decimal_separator'), config('business.currency.thousand_separator'));
}

function getSiteIdFromPath(): ?int
{
    $paths = collect(explode('/', request()->path()));
    $i=0;
    foreach($paths as $path) {
        if ($path == "sites") {
            return isset($paths[$i+1]) && is_numeric($paths[$i+1]) ? (int) $paths[$i+1] : null;
        }
        $i++;
    }
    return null;
}
?>
