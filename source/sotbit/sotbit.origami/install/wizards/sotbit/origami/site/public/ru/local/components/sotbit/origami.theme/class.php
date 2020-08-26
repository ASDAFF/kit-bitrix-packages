<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

class SotbitOrigamiThemeComponent extends \CBitrixComponent
{
    /**
     * @var bool
     */
    private $canChange = false;

    /**
     * @var bool
     */
    private $canSave = false;

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        $moduleIncluded = false;
        try {
            $moduleIncluded = Loader::includeModule('sotbit.origami');
        } catch (\Bitrix\Main\LoaderException $e) {
        }
        if (!$moduleIncluded) {
            return false;
        }

        $FrontUser = \SotbitOrigami::FrontUser();
        if (empty($FrontUser)) {
            return false;
        }

        $this->arResult['CAN_CHANGE'] = $FrontUser->isCanChange();
        $this->arResult['CAN_SAVE'] = $FrontUser->isCanSave();
        $this->includeComponentTemplate();
    }
}
?>