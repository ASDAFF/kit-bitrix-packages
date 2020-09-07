<?
namespace intec\core\bitrix\components;

use CIBlock;
use CIBlockSection;
use CFile;
use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * Class IBlockSections
 * @package intec\core\bitrix\components
 * @author apocalypsisdimon@gmail.com
 */
class IBlockSections extends IBlock
{
    /**
     * Режим глобальной выборки. Если инфоблок или раздел(ы) не указаны, разрешает вывод всех элементов.
     * @var boolean
     */
    protected $_isGlobal = false;

    /**
     * Идентификатор(ы) раздела(ов).
     * @var integer|integer[]|null
     */
    protected $_sectionsId;
    /**
     * Код(ы) раздела(ов).
     * @var string|string[]|null
     */
    protected $_sectionsCode;

    /**
     * Шаблон URL корня.
     * @var string
     */
    protected $_urlRoot;
    /**
     * Шаблон URL раздела.
     * @var string
     */
    protected $_urlSection;

    /**
     * Возвращает режим глобальной выборки.
     * @return boolean
     */
    protected function getIsGlobal()
    {
        return $this->_isGlobal;
    }

    /**
     * Устанавливает режим глобальной выборки.
     * @return boolean
     */
    protected function setIsGlobal($value)
    {
        $this->_isGlobal = Type::toBoolean($value);

        return $value;
    }

    /**
     * Получает идентификаторы разделов.
     * @return integer|integer[]|false|null
     */
    protected function getSectionsId()
    {
        return $this->_sectionsId;
    }

    /**
     * Устанавливает идентификаторы разделов.
     * @param integer|integer[]|false|null $value
     */
    protected function setSectionsId($value)
    {
        if ($value !== null)
            if (Type::isNumeric($value)) {
                $value = Type::toInteger($value);
                $value = $value > 0 ? $value : null;
            } else if (Type::isArray($value)) {
                $value = array_filter($value);

                if (empty($value))
                    $value = null;
            } else if ($value !== false) {
                $value = null;
            }

        $this->_sectionsId = $value;

        return $this;
    }

    /**
     * Получает коды разделов.
     * @return string|string[]|false|null
     */
    protected function getSectionsCode()
    {
        return $this->_sectionsCode;
    }

    /**
     * Устанавливает коды разделов.
     * @param string|string[]|false|null $value
     */
    protected function setSectionsCode($value)
    {
        if ($value !== null)
            if (Type::isArray($value)) {
                $value = array_filter($value);

                if (empty($value))
                    $value = null;
            } else if ($value !== false) {
                $value = Type::toString($value);
            }

        $this->_sectionsCode = $value;

        return $this;
    }

    /**
     * Возвращает шаблон URL корня.
     * @return string
     */
    protected function getUrlRoot()
    {
        return $this->_urlRoot;
    }

    /**
     * Возвращает шаблон URL раздела.
     * @return string
     */
    protected function getUrlSection()
    {
        return $this->_urlSection;
    }

    /**
     * Устанавливает шаблоны URL.
     * @param string|null $root
     * @param string|null $section
     */
    protected function setUrlTemplates($root = null, $section = null)
    {
        if ($root !== null)
            $root = Type::toString($root);

        if ($section !== null)
            $section = Type::toString($section);

        $this->_urlRoot = $root;
        $this->_urlSection = $section;

        return $this;
    }

    /**
     * Возвращает результат выборки.
     * @param array|null $arSort
     * @param array|null $arFilter
     * @param integer|null $iCount
     * @param integer|null $iPage
     * @param boolean $bQuantity
     * @param array|false $arSelect
     * @return array
     */
    protected function getSections($arSort = null, $arFilter = null, $iCount = null, $iPage = null, $bQuantity = false, $arSelect = [])
    {
        global $APPLICATION;

        $arResult = [];

        if (!Loader::includeModule('iblock'))
            return $arResult;

        $bExecute = $this->getIsGlobal();

        $iId = $this->getIBlockId();
        $sType = $this->getIBlockType();

        $arFilter = Type::isArray($arFilter) ? $arFilter : [];
        $arSort = Type::isArray($arSort) ? $arSort : [];
        $iCount = Type::toInteger($iCount);
        $iCount = !empty($iCount) ? $iCount : null;
        $iPage = Type::toInteger($iPage);
        $iPage = !empty($iPage) && $iCount !== null ? $iPage : null;
        $bQuantity = Type::toBoolean($bQuantity);
        $arSelect = Type::isArray($arSelect) ? $arSelect : [];

        $arSectionsId = $this->getSectionsId();
        $arSectionsCode = $this->getSectionsCode();
        $arNavigation = [];

        if ($iCount !== null)
            $arNavigation['nPageSize'] = $iCount;

        if ($iPage !== null)
            $arNavigation['iNumPage'] = $iPage;

        if ($sType !== null)
            $arFilter['IBLOCK_TYPE'] = $sType;

        if ($iId !== null) {
            $arFilter['IBLOCK_ID'] = $iId;
            $bExecute = true;
        }

        if ($arSectionsId !== null) {
            $arFilter['ID'] = $arSectionsId;
            $bExecute = true;
        }

        if ($arSectionsCode !== null) {
            $arFilter['CODE'] = $arSectionsCode;
            $bExecute = true;
        }

        if (!$bExecute)
            return $arResult;

        if ($iId !== null && $APPLICATION->GetShowIncludeAreas()) {
            $arButtons = CIBlock::GetPanelButtons($iId, 0,
                Type::isNumeric($arSectionsId) ? $arSectionsId : 0,
                array(
                    'SECTION_BUTTONS' => true
                )
            );

            $this->AddIncludeAreaIcons(
                CIBlock::GetComponentMenu(
                    $APPLICATION->GetPublicShowMode(),
                    $arButtons
                )
            );
        }

        $arImages = [];
        $rsResult = CIBlockSection::GetList($arSort, $arFilter, $bQuantity, $arSelect, $arNavigation);
        $rsResult->SetUrlTemplates(null, $this->getUrlSection(), $this->getUrlRoot());

        while ($rsSection = $rsResult->GetNextElement()) {
            $arSection = $rsSection->GetFields();
            $arSection['PROPERTIES'] = $rsSection->GetProperties();

            if (!empty($arSection['PICTURE']))
                $arImages[] = $arSection['PICTURE'];

            if (!empty($arSection['DETAIL_PICTURE']))
                $arImages[] = $arSection['DETAIL_PICTURE'];

            $buttons = CIBlock::GetPanelButtons(
                $arSection['IBLOCK_ID'],
                0,
                $arSection['ID'],
                array(
                    'SECTION_BUTTONS' => true,
                    'SESSID' => false,
                    'CATALOG' => true
                )
            );

            $arSection['EDIT_LINK'] = $buttons['edit']['edit_section']['ACTION_URL'];
            $arSection['DELETE_LINK'] = $buttons['edit']['delete_section']['ACTION_URL'];
            $arResult[$arSection['ID']] = $arSection;
        }

        if (!empty($arImages)) {
            $rsImages = CFile::GetList(array(), array(
                '@ID' => implode(',', $arImages)
            ));
            $arImages = array();

            while ($arImage = $rsImages->GetNext()) {
                $arImage['SRC'] = CFile::GetFileSRC($arImage);
                $arImages[$arImage['ID']] = $arImage;
            }

            foreach ($arResult as &$arSection) {
                if (!empty($arSection['PICTURE']))
                    $arSection['PICTURE'] = ArrayHelper::getValue($arImages, $arSection['PICTURE']);

                if (!empty($arSection['DETAIL_PICTURE']))
                    $arSection['DETAIL_PICTURE'] = ArrayHelper::getValue($arImages, $arSection['DETAIL_PICTURE']);
            }
        }

        return $arResult;
    }
}