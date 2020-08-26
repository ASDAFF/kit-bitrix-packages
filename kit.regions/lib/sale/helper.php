<?php
namespace Kit\Regions\Sale;

use Bitrix\Main\Loader;
use Bitrix\Sale\Internals;

/**
 * Class Helper
 *
 * @package Kit\Regions\Sale
 * 
 */
class Helper
{
    public static function getPersonTypeUser($siteId, $userId = null) {
        $personTypeId = 1;

        if (!Loader::includeModule('sale') || !Loader::includeModule('catalog')) {
            return $personTypeId;
        }

        // list all type user
        $personType = Internals\PersonTypeTable::getList(
            [
                'filter' => [
                    "ACTIVE" => "Y",
                    "LID"    => $siteId,
                ],
            ])->fetchAll();

        if (!empty($personType)) {
            if ($userId) {
                $arProfiles = \CSaleOrderUserProps::GetList(
                    array("DATE_UPDATE" => "DESC"),
                    array("USER_ID" => $userId)
                );

                if($arProfile = $arProfiles->Fetch()) {
                    $personTypeId = $arProfile['PERSON_TYPE_ID'];
                }

            } else {
                $personTypeId = $personType[0]['ID'];
            }
        }

        return $personTypeId;
    }

}