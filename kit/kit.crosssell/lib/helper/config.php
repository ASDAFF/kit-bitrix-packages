<?php

namespace Kit\Crosssell\Helper;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Sale\Delivery\Services\Table;
use Bitrix\Sale\Internals\PersonTypeTable;

Loc::loadMessages(__FILE__);

class Config
{
    public static function getSites()
    {
        $sites = [];
        try {
            $rs = \Bitrix\Main\SiteTable::getList([
                'select' => [
                    'SITE_NAME',
                    'LID',
                ],
                'filter' => ['ACTIVE' => 'Y'],
            ]);
            while ($site = $rs->fetch()) {
                $sites[$site['LID']] = $site['SITE_NAME'];
            }
        } catch (ObjectPropertyException $e) {
            $e->getMessage();
        } catch (ArgumentException $e) {
            $e->getMessage();
        } catch (SystemException $e) {
            $e->getMessage();
        }
        try {
            if (!is_array($sites) || count($sites) == 0) {
                throw new SystemException("Cannt get sites");
            }
        } catch (SystemException $exception) {
            echo $exception->getMessage();
        }

        return $sites;
    }
}
