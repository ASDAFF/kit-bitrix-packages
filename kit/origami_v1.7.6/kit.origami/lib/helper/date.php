<?php

namespace Kit\Origami\Helper;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class Date
 *
 * @package Kit\Origami\Helper
 *
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
            "1"  => Loc::getMessage(\KitOrigami::moduleId.'_JANUARY'),
            "2"  => Loc::getMessage(\KitOrigami::moduleId.'_FEBRUARY'),
            "3"  => Loc::getMessage(\KitOrigami::moduleId.'_MARCH'),
            "4"  => Loc::getMessage(\KitOrigami::moduleId.'_APRIL'),
            "5"  => Loc::getMessage(\KitOrigami::moduleId.'_MAY'),
            "6"  => Loc::getMessage(\KitOrigami::moduleId.'_JUNE'),
            "7"  => Loc::getMessage(\KitOrigami::moduleId.'_JULY'),
            "8"  => Loc::getMessage(\KitOrigami::moduleId.'_AUGUST'),
            "9"  => Loc::getMessage(\KitOrigami::moduleId.'_SEPTEMBER'),
            "10" => Loc::getMessage(\KitOrigami::moduleId.'_OCTOBER'),
            "11" => Loc::getMessage(\KitOrigami::moduleId.'_NOVEMBER'),
            "12" => Loc::getMessage(\KitOrigami::moduleId.'_DECEMBER'),
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

            $toString = " ".Loc::getMessage(\KitOrigami::moduleId.'_TO')." ".$dayToDate." ".$monthToDate;
        }

        $fromString = Loc::getMessage(\KitOrigami::moduleId.'_FROM')." ".$dayFromDate.($monthFromDate ? " ".$monthFromDate
                : "");
        $dateString = $fromString.$toString;

        return $dateString;
    }
}