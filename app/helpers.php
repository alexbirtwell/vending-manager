<?php
function currency_format($value)
{
     return config('business.currency.symbol') . number_format($value, config('business.currency.precision'), config('business.currency.decimal_separator'), config('business.currency.thousand_separator'));
}
?>
