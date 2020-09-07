<?php

use Bitrix\Main\Loader;
use intec\Core;
use intec\core\base\InvalidParamException;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Json;
use intec\core\helpers\Type;
use intec\core\io\Path;
use intec\constructor\Module as Constructor;
use intec\constructor\models\Build;

class IntecSystemSettingsComponent extends CBitrixComponent
{
    /**
     * Режим: Конфигурирование
     */
    const MODE_CONFIGURE = 'configure';
    /**
     * Режим: Отрисовка
     */
    const MODE_RENDER = 'render';

    /**
     * Возвращает путь до файла настроек.
     * @param string $sFile
     * @return Path
     */
    protected function getFilePath($sFile = '.settings.json')
    {
        return Path::from('@root/'.SITE_DIR.$sFile);
    }

    /**
     * Загружает данные из файла.
     * @param string $sFile
     * @return array
     */
    protected function loadFromFile($sFile = '.settings.json')
    {
        $oPath = $this->getFilePath($sFile);
        $arValues = FileHelper::getFileData($oPath->value);

        try {
            $arValues = Json::decode($arValues, true, true);
        } catch (InvalidParamException $exception) {}

        if (!Type::isArray($arValues))
            $arValues = [];

        return $arValues;
    }

    /**
     * Сохраняет данные в файл.
     * @param array $arValues
     * @param string $sFile
     */
    protected function saveToFile($arValues, $sFile = '.settings.json')
    {
        if (!Type::isArray($arValues))
            $arValues = [];

        FileHelper::setFileData(
            $this->getFilePath()->value,
            Json::encode($arValues, 320, true)
        );
    }

    /**
     * Загружает данные из сессии.
     * @param string $sVariable
     * @return array
     */
    protected function loadFromSession($sVariable = 'system.settings')
    {
        $arValues = Core::$app->session->get($sVariable);

        if (!Type::isArray($arValues))
            $arValues = [];

        return $arValues;
    }

    /**
     * Сохраняет данные в сессию.
     * @param array $arValues
     * @param string $sVariable
     */
    protected function saveToSession($arValues, $sVariable = 'system.settings')
    {
        if (!Type::isArray($arValues))
            $arValues = [];

        Core::$app->session->set($sVariable, $arValues);
    }

    /**
     * Приводит значение свойства к типу.
     * @param array $arProperty
     * @param mixed $mValue
     * @return mixed
     */
    protected function bringPropertyValue($arProperty, $mValue)
    {
        $sType = ArrayHelper::getValue($arProperty, 'type');
        $mDefault = ArrayHelper::getValue($arProperty, 'default');

        if ($mValue === null)
            $mValue = $mDefault;

        if ($sType === 'string') {
            $mValue = Type::toString($mValue);
        } else if ($sType === 'integer') {
            $mValue = Type::toInteger($mValue);
        } else if ($sType === 'float') {
            $mValue = Type::toFloat($mValue);
        } else if ($sType === 'boolean') {
            $mValue = Type::toBoolean($mValue);
        } else if ($sType === 'list') {
            $bMultiple = ArrayHelper::getValue($arProperty, 'multiple');
            $arValues = ArrayHelper::getValue($arProperty, 'values');

            if (Type::isArrayable($arValues)) {
                if (!$bMultiple) {
                    $bSet = false;

                    foreach ($arValues as $arValue) {
                        $mListValue = ArrayHelper::getValue($arValue, 'value');

                        if ($mValue == $mListValue) {
                            $bSet = true;
                            $mValue = $mListValue;
                            break;
                        }
                    }

                    unset($mListValue);
                    unset($arValue);

                    if (!$bSet)
                        $mValue = $mDefault;

                    unset($bSet);
                } else {
                    $bUnset = false;

                    if ($mValue === null)
                        $bUnset = true;

                    if (!Type::isArray($mValue))
                        $mValue = [];

                    $arValueNew = [];

                    foreach ($mValue as $mValuePart) {
                        if (empty($mValuePart))
                            continue;

                        foreach ($arValues as $arValue) {
                            $mListValue = ArrayHelper::getValue($arValue, 'value');

                            if ($mValuePart == $mListValue) {
                                $arValueNew[] = $mListValue;
                            }
                        }
                    }

                    unset($mListValue);
                    unset($arValue);
                    unset($mValuePart);

                    if ($bUnset && empty($arValueNew))
                        if (Type::isArray($mDefault))
                            $arValueNew = $mDefault;

                    $mValue = $arValueNew;

                    unset($arValue);
                    unset($bUnset);
                }
            } else {
                $mValue = $mDefault;
            }

            unset($bStrict);
            unset($arValues);
        }

        return $mValue;
    }

    /**
     * @inheritdoc
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!Loader::includeModule('intec.core'))
            return $arParams;

        if (!Type::isArray($arParams))
            $arParams = [];

        $arParams = ArrayHelper::merge([
            'MODE' => static::MODE_CONFIGURE,
            'VARIABLES_ACTION' => null
        ], $arParams);

        if (empty($arParams['VARIABLES_ACTION']))
            $arParams['VARIABLES_ACTION'] = 'system-settings-action';

        $arParams['MODE'] = ArrayHelper::fromRange([
            static::MODE_CONFIGURE,
            static::MODE_RENDER
        ], $arParams['MODE']);

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * @inheritdoc
     */
    public function executeComponent()
    {
        /** @var CUser $USER */
        global $USER;

        if (
            !Loader::includeModule('intec.core') || (
                !Loader::includeModule('intec.constructor') &&
                !Loader::includeModule('intec.constructorlite')
            )
        ) return [];

        $arParams = $this->arParams;
        $arResult = [];

        $arResult['ACTION'] = null;
        $arResult['BUILD'] = Build::getCurrent();
        $arResult['MODE'] = $arParams['MODE'];
        $arResult['VARIABLES'] = [
            'ACTION' => $arParams['VARIABLES_ACTION']
        ];
        $arResult['PROPERTIES'] = [];
        $arResult['TEMPLATE'] = null;
        $arResult['TEMPLATES'] = [];
        $arResult['VALUES'] = [];

        if (empty($arResult['BUILD']))
            return $arResult['VALUES'];

        if (!Constructor::isLite()) {
            $arResult['TEMPLATES'] = $arResult['BUILD']->getTemplates(true, false);

            if ($arResult['MODE'] !== static::MODE_CONFIGURE) {
                $arResult['TEMPLATE'] = $arResult['BUILD']->getTemplate();
            }
        }

        $arResult['PROPERTIES'] = $arResult['BUILD']->getMetaValue('properties');

        if (!$USER->IsAdmin())
            $arResult['VALUES'] = $this->loadFromSession();

        if (empty($arResult['VALUES']))
            $arResult['VALUES'] = $this->loadFromFile();

        $oRequest = Core::$app->request;

        if ($oRequest->getIsPost()) {
            $arResult['ACTION'] = $oRequest->post($arResult['VARIABLES']['ACTION']);

            if ($arResult['ACTION'] === 'apply') {
                $arProperties = $oRequest->post('properties');

                if (Type::isArray($arProperties))
                    foreach ($arProperties as $sKey => $sValue)
                        $arResult['VALUES'][$sKey] = $sValue;

                unset($mValue);
                unset($sKey);
                unset($arProperties);
            }
        }

        unset($oRequest);

        if ($arResult['ACTION'] === 'reset') {
            if ($USER->IsAdmin()) {
                $arResult['VALUES'] = $this->loadFromFile('.settings.default.json');
            } else {
                $arResult['VALUES'] = $this->loadFromFile();
            }
        }

        foreach ($arResult['PROPERTIES'] as $sKey => &$arProperty) {
            $mValue = ArrayHelper::getValue($arResult['VALUES'], $sKey);
            $arProperty['value'] = $this->bringPropertyValue(
                $arProperty,
                $mValue
            );
        }

        unset($arProperty);
        unset($sKey);

        $arResult['VALUES'] = [];

        if ($this->initComponentTemplate('', $this->getSiteTemplateId())) {
            $oTemplate = $this->getTemplate();
            $oPath = Path::from('@root/'.$oTemplate->GetFolder().'/configuration.php');

            if (FileHelper::isFile($oPath->value))
                include($oPath->value);
        }

        foreach ($arResult['PROPERTIES'] as $sKey => &$arProperty)
            $arResult['VALUES'][$sKey] = $arProperty['value'];

        unset($arProperty);
        unset($sKey);

        if (!Type::isArray($arResult['VALUES']))
            $arResult['VALUES'] = [];

        if ($arResult['MODE'] === static::MODE_CONFIGURE) {
            $this->setFrameMode(true);

            if ($arResult['ACTION'] === 'apply' || $arResult['ACTION'] === 'reset') {
                if ($USER->IsAdmin()) {
                    $this->saveToFile($arResult['VALUES']);
                } else {
                    $this->saveToSession($arResult['VALUES']);
                }
            }
        } else if ($arResult['MODE'] === static::MODE_RENDER) {
            $this->arResult = $arResult;
            $this->IncludeComponentTemplate();
        }

        return $arResult['VALUES'];
    }
}