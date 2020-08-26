<?php

namespace Sotbit\Origami\Helper;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class Date
 *
 * @package Sotbit\Origami\Helper
 * @author  Sergey Danilkin <s.danilkin@sotbit.ru>
 */
class Date
{
    /**
     * @param $fromDate
     * @param $toDate
     *
     * @return string
     */
    public function dateFormatFromTo($fromDate, $toDate)
    {
        $monthsList = [
            "1"  => Loc::getMessage(\SotbitOrigami::moduleId.'_JANUARY'),
            "2"  => Loc::getMessage(\SotbitOrigami::moduleId.'_FEBRUARY'),
            "3"  => Loc::getMessage(\SotbitOrigami::moduleId.'_MARCH'),
            "4"  => Loc::getMessage(\SotbitOrigami::moduleId.'_APRIL'),
            "5"  => Loc::getMessage(\SotbitOrigami::moduleId.'_MAY'),
            "6"  => Loc::getMessage(\SotbitOrigami::moduleId.'_JUNE'),
            "7"  => Loc::getMessage(\SotbitOrigami::moduleId.'_JULY'),
            "8"  => Loc::getMessage(\SotbitOrigami::moduleId.'_AUGUST'),
            "9"  => Loc::getMessage(\SotbitOrigami::moduleId.'_SEPTEMBER'),
            "10" => Loc::getMessage(\SotbitOrigami::moduleId.'_OCTOBER'),
            "11" => Loc::getMessage(\SotbitOrigami::moduleId.'_NOVEMBER'),
            "12" => Loc::getMessage(\SotbitOrigami::moduleId.'_DECEMBER'),
        ];

        $fromTimestamp = strtotime($fromDate);
        $dayFromDate = date("j", $fromTimestamp);
        $monthFromDate = $monthsList[date("n", $fromTimestamp)];

        if ($toDate) {
            $toTimestamp = strtotime($toDate);
            $dayToDate = date("j", $toTimestamp);
            $monthToDate = $monthsList[date("n", $toTimestamp)];

            if ($monthFromDate == $monthToDate) {
                $monthFromDate = "";
            }

            $toString = " ".Loc::getMessage(\SotbitOrigami::moduleId.'_TO')." ".$dayToDate." ".$monthToDate;
        }

        $fromString = Loc::getMessage(\SotbitOrigami::moduleId.'_FROM')." ".$dayFromDate.($monthFromDate ? " ".$monthFromDate
                : "");
        $dateString = $fromString.$toString;

        return $dateString;
    }
}