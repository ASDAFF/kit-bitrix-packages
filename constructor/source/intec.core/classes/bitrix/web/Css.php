<?php
namespace intec\core\bitrix\web;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use intec\Core;
use intec\core\helpers\StringHelper;
use intec\core\io\Path;

/**
 * Класс для управления скриптами в системе.
 * Class JavaScript
 * @package intec\core\bitrix\web
 * @since 1.0.0
 */
class Css
{
    /**
     * Возвращает Less-менеджер.
     * @return Less
     */
    protected function getLess()
    {
        return Core::$app->web->less;
    }

    /**
     * Подключает файл стилей.
     * @param string $path Путь до файла. Может быть относительным или абсолютным.
     * @since 1.0.0
     */
    public function addFile($path)
    {
        global $APPLICATION;

        if (StringHelper::position('://', $path) === false)
            $path = Path::from($path)
                ->toRelative()
                ->asAbsolute()
                ->getValue('/');

        $APPLICATION->SetAdditionalCSS($path);
    }

    /**
     * Подключает строку со стилем.
     * @param string $string Скрипт.
     * @since 1.0.0
     */
    public function addString($string)
    {
        $string = '<style>'.$string.'</style>';
        $asset = Asset::getInstance();
        $asset->addString($string, false, AssetLocation::AFTER_CSS);
    }

    /**
     * Компилирует и подключает less строку.
     * @param string $string Строка less.
     * @param array $parameters Праметры less.
     * @param bool $throwException Вызывать исключение.
     */
    public function addLessString($string, $parameters = [], $throwException = false)
    {
        $less = $this->getLess();
        $string = $less->compile($string, $parameters, $throwException);
        $this->addString($string);
    }

    /**
     * Компилирует и подключает файл less.
     * @param string $pathFrom Путь до less файла.
     * @param string|null $pathTo Путь до css файла, в который будет идти запись.
     * Если `null`, то файл будет добавлен строкой.
     * @param array $parameters Параметры less.
     * @param bool $throwException Вызывать исключение.
     */
    public function addLessFile($pathFrom, $pathTo = null, $parameters = [], $throwException = false)
    {
        $less = $this->getLess();
        $result = $less->compileFile($pathFrom, $pathTo, $parameters, $throwException);

        if ($pathTo) {
            $this->addFile($pathTo);
        } else if (!empty($result)) {
            $this->addString($result);
        }
    }
}