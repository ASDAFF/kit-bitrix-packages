<?php
namespace intec\constructor\models;
IncludeModuleLangFile(__FILE__);

use intec\Core;
use intec\core\base\BaseObject;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\Type;
use intec\constructor\models\build\File;
use intec\constructor\models\build\Gallery;
use intec\constructor\models\build\Page;
use intec\constructor\models\build\Template;

/**
 * Class Build
 * @package intec\constructor\models
 */
class Build extends BaseObject
{
    /**
     * Текущая сборка.
     * @var Build
     */
    protected static $current;

    /**
     * Мета-информация сборки.
     * @var array|null
     */
    protected $_meta = null;
    /**
     * Текущая страница.
     * @var Page
     */
    protected $_page;
    /**
     * Текущий шаблон.
     * @var Template
     */
    protected $_template;

    /**
     * Возвращает текущую сборку в соответствии с шаблоном сайта.
     * @return Build|null
     */
    public static function getCurrent()
    {
        if (static::$current === null)
            static::$current = new static();

        return static::$current;
    }

    /**
     * Возвращает путь до сборки.
     * @param bool $old
     * @param bool $relative
     * @param string $separator
     * @return string|null
     */
    public function getDirectory($old = false, $relative = false, $separator = DIRECTORY_SEPARATOR)
    {
        $code = SITE_TEMPLATE_ID;

        if (!$relative)
            return FileHelper::normalizePath(Core::getAlias('@templates/'.$code, $separator));

        return FileHelper::normalizePath('/bitrix/templates/'.$code, $separator);
    }

    /**
     * Возвращает мета-данные шаблона.
     * @param bool $reset
     * @return array|mixed
     */
    public function getMeta($reset = false)
    {
        if ($this->_meta === null || $reset) {
            $directory = $this->getDirectory();
            $path = $directory . DIRECTORY_SEPARATOR . 'meta.php';
            $this->_meta = [];

            if (FileHelper::isFile($path))
                $this->_meta = include($path);
        }

        return $this->_meta;
    }

    /**
     * Возвращает значение мета-переменной шаблона.
     * @param string|array $key
     * @param bool $reset
     * @return mixed|null
     */
    public function getMetaValue($key, $reset = false)
    {
        $meta = $this->getMeta($reset);
        return ArrayHelper::getValue($meta, $key);
    }

    /**
     * Возвращает текущую страницу.
     * @param bool $reset
     * @return Page
     */
    public function getPage($reset = false)
    {
        if ($this->_page === null || $reset) {
            $directory = SITE_DIR;
            $path = Core::$app->request->getScriptUrl();

            if (!empty($_SERVER['REAL_FILE_PATH']))
                $path = $_SERVER['REAL_FILE_PATH'];

            $path = RegExp::replaceBy(
                '/^'.RegExp::escape($directory).'/',
                '',
                $path
            );

            $this->_page = new Page($this, $path, $directory);
        }

        return $this->_page;
    }

    /**
     * Возвращает файлы шаблона.
     * @return File[]
     */
    public function getFiles()
    {
        $meta = $this->getMeta();
        $files = ArrayHelper::getValue($meta, 'files');
        $result = [];

        if (Type::isArray($files)) {
            $types = File::getTypesValues();

            foreach ($files as $file) {
                $path = ArrayHelper::getValue($file, 'path');
                $path = Type::toString($path);
                $type = ArrayHelper::getValue($file, 'type');
                $type = Type::toString($type);

                if (empty($path))
                    continue;

                if (!ArrayHelper::isIn($type, $types))
                    continue;

                $file = new File($this, $type, $path);

                if ($file->isExists())
                    $result[] = $file;
            }
        }

        return $result;
    }

    /**
     * Возвращает шаблон, подходящий под условия.
     * @param string|null $directory Текущая директория сайта.
     * @param string|null $path Текущий путь.
     * @param string|null $url Текущий адрес Url.
     * @param array|null $parametersGet Параметры запроса.
     * @param array|null $parametersPage Параметры страницы.
     * @return Template|null
     */
    public function getTemplate($directory = null, $path = null, $url = null, $parametersGet = null, $parametersPage = null)
    {
        if ($this->_template === null)
            $this->_template = new Template();

        return $this->_template;
    }

    /**
     * Возвращает экземпляр галереи для сборки.
     * @return Gallery
     */
    public function getGallery()
    {
        return new Gallery($this);
    }
}