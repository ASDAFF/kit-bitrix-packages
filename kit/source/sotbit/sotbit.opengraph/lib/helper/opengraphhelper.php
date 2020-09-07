<?
namespace Sotbit\Opengraph\Helper;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

/**
 * class for page meta settings
 *
 * @author Evgenij Sidorenko < e.sidorenko@sotbit.ru >
 *
 */
class OpengraphHelper {
    public static function saveImage($arImg, $case = 'OG_IMAGE') {
        if(empty($arImg))
            return false;
        
        if(is_array($arImg)) {
            if(Loader::includeModule('iblock'))
                $fid = \CFile::SaveFile(\CIBlock::makeFileArray($arImg, false, ""), "sotbit_opengraph");
        }
        else if(is_numeric($arImg)) {
            $fid = $arImg;
        }
        else {
            $fid = \CFile::SaveFile(\CFile::makeFileArray($arImg), "sotbit_opengraph");
        }
        
        if(isset($_REQUEST[$case.'_del']) && $_REQUEST[$case.'_del'] == 'Y')
            $fid = false;
        
        return $fid;
    }
}

?>