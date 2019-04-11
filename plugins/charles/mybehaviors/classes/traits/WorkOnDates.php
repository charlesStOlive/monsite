<?php namespace Charles\Mybehaviors\Classes\Traits;

use Carbon\Carbon;

trait WorkOnDates
{

	/**
     * [getPeriodeScope description]
     * @param  [string] $type            [string : day, week, month, year]
     * @param  [integer] $periodeToRemove [enlève un nombre de jours ou week...]
     * @return [date]                  [donne une date]
     */
    protected function getPeriodeDay($type, $periodeToRemove) {


        switch ($type) {
            case "day":
                return Carbon::now()->subDay($periodeToRemove);
                break;
            case "week":
                return Carbon::now()->subWeek($periodeToRemove);
                break;
            case "month":
                return Carbon::now()->subMonth($periodeToRemove);
                break;
            case "year":
                return Carbon::now()->subYear($periodeToRemove);
                break;
        }

    }

   /**
    * [getPeriodeScope description]
    * @param  [string] $type       [string : day, week, month, year]
    * @param  [$query] $query     [le query en cours]
    * @param  [string] $field     [le champs a analyser]
    * @param  [date] $CarbonDay [Une date à analyser]
    * @return [query]            [retourne le scope]
    */
    protected function getPeriodeScope($type, $query, $field, $CarbonDay) {
        switch ($type) {
            case "day":
                $periode = $CarbonDay->day;
                return $query::whereDay($field, $periode) ;
                break;
            case "week":
                $start = $CarbonDay->startOfWeek();
                $end = $CarbonDay->endOfWeek();
                return $query::whereBetween($field, [$start, $end]) ;
                break;
            case "month":
                $periode = $CarbonDay->month;
                return $query::whereMonth($field, $periode) ;
                break;
            case "year":
                $periode = $CarbonDay->year;
                return $query::whereYear($field, $periode) ;
                break;
        }

    }


}