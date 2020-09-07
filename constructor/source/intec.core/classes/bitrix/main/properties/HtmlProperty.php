<?php
namespace intec\core\bitrix\main\properties;

use CFileMan;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

Loc::loadMessages(__FILE__);

class HtmlProperty
{
    /**
     * Пользовательский тип свойства.
     */
    const USER_TYPE_ID = 'html';

    /**
     * Возвращает объявление для системы.
     * @return array
     */
    public static function getDefinition()
    {
        return [
            'USER_TYPE_ID' => static::USER_TYPE_ID,
            'CLASS_NAME' => __CLASS__,
            'DESCRIPTION' => Loc::getMessage('intec.core.bitrix.main.properties.htmlProperty.name'),
            'BASE_TYPE' => 'string',
			'VIEW_CALLBACK' => [__CLASS__, 'getPublicView'],
			'EDIT_CALLBACK' => [__CLASS__, 'getPublicEdit']
        ];
    }

    /**
     * Возвращает тип поля базы данных.
     * @param array $arUserField
     * @return string
     */
    public function getDBColumnType($arUserField)
    {
        return 'text';
    }

    /**
     * Возвращает элемент управления для редактирования.
     * @param array $arProperty
     * @param array $arControl
     * @return string
     * @throws \Bitrix\Main\LoaderException
     */
    public function getEditFormHTML($arProperty, $arControl)
	{
		if (!Loader::includeModule('fileman'))
			return Loc::getMessage('IBLOCK_PROP_HTML_NOFILEMAN_ERROR');

		$sResult = null;

		if ($arProperty['MULTIPLE'] !== 'Y') {
            ob_start();

            CFileMan::AddHTMLEditorFrame(
                $arControl['NAME'],
                $arControl['VALUE'],
                null,
                'html',
                [
                    'height' => 450,
                    'width' => '100%'
                ],
                'N',
                0,
                '',
                '',
                '',
                true,
                false,
                [
                    'toolbarConfig' => 'admin',
                    'saveEditorState' => false,
                    'hideTypeSelector' => true
                ]
            );

            $sResult = ob_get_contents();
            ob_end_clean();
        } else {
		    $sResult = Html::tag('textarea', $arControl['VALUE'], [
                'name' => $arControl['NAME'],
		        'style' => [
                    'height' => '100px',
                    'min-width' => '300px'
                ]
            ]);
        }

		return $sResult;
	}

    /**
     * Возвращает вид в списках.
     * @param array $arProperty
     * @param array $arControl
     * @return mixed
     */
    public function getAdminListViewHTML($arProperty, $arControl)
    {
        return $arControl['VALUE'];
    }

    /**
     * Возвращает элемент управления для редактирования в списках.
     * @param array $arProperty
     * @param array $arControl
     * @return mixed
     */
    public function getAdminListEditHTML($arProperty, $arControl)
    {
        return $sResult = Html::tag('textarea', $arControl['VALUE'], [
            'name' => $arControl['NAME'],
            'style' => [
                'height' => '100px',
                'min-width' => '200px'
            ]
        ]);
    }

    /**
     * Возвращает вид в публичной части.
     * @param array $arProperty
     * @param array $arAdditionalValues
     * @return mixed
     */
    public function getPublicView($arProperty, $arAdditionalValues = [])
    {
        if (!isset($arProperty['VALUE']))
            return null;

        $arValue = $arProperty['VALUE'];

        if (!Type::isArray($arValue))
            $arValue = [$arValue];

        if (empty($arValue))
            $arValue = [null];

        return implode(', ', $arValue);
    }

    /**
     * Возвращает настройки.
     * @param array|boolean $arProperty
     * @param array $arControl
     * @param $bVarsFromForm
     * @return null
     */
    public function getSettingsHTML($arProperty = false, $arControl, $bVarsFromForm)
    {
        return null;
    }
}