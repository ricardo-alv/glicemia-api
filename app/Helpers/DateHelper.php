<?php

use Illuminate\Support\Carbon;


if (! function_exists('formatDateBrTime')) {
    /**
     * Formata a data para o formato brasileiro (dd/mm/yyyy H:i:s).
     * 
     * @param string|null $date
     * @return string
     */
    function formatDateBrTime($date): string
    {
        return $date ? Carbon::parse($date)->format('d/m/Y H:i:s') : '';
    }
}

if (! function_exists('formatDateBr')) {
    /**
     * Formata a data para o formato brasileiro (dd/mm/yyyy).
     * 
     * @param string|null $date
     * @return string
     */
    function formatDateBr($date): string
    {
        return $date ? Carbon::parse($date)->format('d/m/Y') : '';
    }
}

if (! function_exists('period_start')) {
    /**
     * data com time inicial (yyyy-mm-dd 00:00:00).
     * 
     * @param string|null $date
     * @return string
     */
    function period_start($date): string
    {
        return $date ? Carbon::parse($date)->startOfDay() : '';
    }
}

if (! function_exists('period_final')) {
    /**
     * data com time final (yyyy-mm-dd  23:59:59).
     * 
     * @param string|null $date
     * @return string
     */
    function period_final($date): string
    {
        return $date ? Carbon::parse($date)->endOfDay() : '';
    }
}
