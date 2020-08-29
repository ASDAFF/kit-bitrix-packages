<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

class KitBlockIncludeComponent extends \CBitrixComponent
{
    /**
     * @param $arParams
     *
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        try {
            $moduleIncluded = Loader::includeModule('kit.origami');
            if (!$moduleIncluded) {
                return false;
            }
        } catch (\Bitrix\Main\LoaderException $e) {
            echo $e->getMessage();
        }

        $FrontBlock = new \Kit\Origami\Front\Block(
            SITE_ID
        );
        global $APPLICATION;
        $page = $APPLICATION->GetCurDir();
        $FrontBlock->setPage($page);

        $FrontBlock->setBlockCollection($this->arParams['PART']);

        $blockCollection = $FrontBlock->getBlockCollection();

        if ($FrontBlock->getFrontUser()->isCanChange()) {
            $this->arResult['AVAILABLE_BLOCKS'] = $this->getAvailableBlocks();
        }

        $this->arResult['BLOCK_COLLECTION'] = $blockCollection;
        $this->arResult['CAN_CHANGE'] = $FrontBlock->getFrontUser()
            ->isCanChange();
        $this->arResult['CAN_SAVE'] = $FrontBlock->getFrontUser()->isCanSave();
        $this->includeComponentTemplate();
    }

    /**
     * @return array
     */
    public function getAvailableBlocks()
    {
        $dir = $_SERVER['DOCUMENT_ROOT'].\KitOrigami::blockDir;
        if (is_dir($dir)) {
            $sections = $this->getSections($dir);
            $this->arResult['AVAILABLE_SECTIONS'] = $sections;
            $avBlocks = scandir($dir);
            $result = \KitOrigami::blockIncludeAvailable($avBlocks, $dir, $sections);
        }
        return $result;
    }

    public function getSections($dir)
    {
        $return = [];
        if (file_exists($dir.'/.sections.php')) {
            $return = include $dir.'/.sections.php';
        }

        return $return;
    }
}

?>