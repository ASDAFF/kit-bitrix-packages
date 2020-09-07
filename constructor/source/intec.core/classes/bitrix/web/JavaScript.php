<?php
namespace intec\core\bitrix\web;

use CJSCore;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\core\io\Path;

/**
 * Класс для управления скриптами в системе.
 * Class JavaScript
 * @package intec\core\bitrix\web
 * @since 1.0.0
 */
class JavaScript
{
    /**
     * Добавляет расширение JavaScript.
     * @param JavaScriptExtension $extension Расширение, которое необходимо добавить.
     * @param bool $replace Заменить если такое уже есть.
     * @return bool Расширение успешно добавлено.
     * @since 1.0.0
     */
    public function addExtension($extension, $replace = false)
    {
        if (!$extension instanceof JavaScriptExtension)
            return false;

        if (!$extension->verify())
            return false;

        if ($this->isExtensionExists($extension) && !$replace)
            return false;
        
        CJSCore::RegisterExt(
            $extension->id,
            $extension->toCJSExtension()
        );

        return true;
    }

    /**
     * Добавляет расширения JavaScript.
     * @param JavaScriptExtension[] $extensions Расширения, которые необходимо добавить.
     * @param bool $replace Заменить если такое уже есть.
     * @since 1.0.0
     */
    public function addExtensions($extensions, $replace = false)
    {
        if (!Type::isArray($extensions))
            return;

        foreach ($extensions as $extension) {
            $this->addExtension($extension, $replace);
        }
    }

    /**
     * Возвращает добавленное расширение или null.
     * @param string $id Идентификатор расширения.
     * @return JavaScriptExtension|null Расширение или null.
     * @since 1.0.0
     */
    public function getExtension($id)
    {
        if (!$this->isExtensionExists($id))
            return null;

        $array = CJSCore::getExtInfo($id);

        if (!Type::isArray($array))
            return null;

        $extension = JavaScriptExtension::fromCJSExtension($id, $array);

        if (!$extension->verify())
            return null;

        return $extension;
    }

    /**
     * Проверяет расширение на существование.
     * @param JavaScriptExtension|integer $extension Расширение или идентификатор.
     * @return bool Расширение найдено.
     * @since 1.0.0
     */
    public function isExtensionExists($extension)
    {
        if ($extension instanceof JavaScriptExtension)
            $extension = $extension->id;

        return CJSCore::IsExtRegistered($extension);
    }

    /**
     * Загружает расширения.
     * @param string|array|JavaScriptExtension $extensions Расширения.
     */
    public function loadExtensions($extensions)
    {
        if (!Type::isArray($extensions))
            $extensions = [$extensions];

        foreach ($extensions as $key => $extension)
            if ($extension instanceof JavaScriptExtension)
                if ($extension->verify()) {
                    $extensions[$key] = $extension->id;
                } else {
                    unset($extensions[$key]);
                }

        if (!empty($extensions))
            CJSCore::Init($extensions);
    }

    /**
     * Подключает файл скрипта.
     * @param string $path Путь до скрипта. Может быть относительным или абсолютным.
     * @param bool $additional
     * @since 1.0.0
     */
    public function addFile($path, $additional = false)
    {
        if (StringHelper::position('://', $path) === false)
            $path = Path::from($path)
                ->toRelative()
                ->asAbsolute()
                ->getValue('/');

        $asset = Asset::getInstance();
        $asset->addJs($path, $additional);
    }

    /**
     * Подключает строку со скриптом.
     * @param string $string Скрипт.
     * @since 1.0.0
     */
    public function addString($string)
    {
        $string = '<script type="text/javascript">'.$string.'</script>';
        $asset = Asset::getInstance();
        $asset->addString($string, false, AssetLocation::AFTER_JS);
    }
}