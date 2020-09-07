<?php
namespace intec\constructor\structure;

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\constructor\base\ScannableSnippet;
use intec\constructor\models\Build;
use intec\constructor\structure\widget\Template;
use intec\constructor\structure\widget\Templates;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;

Loc::loadMessages(__FILE__);

/**
 * Class Widget
 * @package intec\constructor\models
 */
abstract class Widget extends ScannableSnippet
{
    use SnippetIconTrait;
    use SnippetMetaTrait;
    use SnippetHandleTrait;

    /**
     * Кеш шаблонов.
     * @var array
     */
    protected $_templates = [];

    /**
     * Возвращает директорию до шаблонов виджета.
     * @param Build $build Сборка.
     * @return Path
     */
    public function getTemplatesDirectory($build = null)
    {
        if ($build instanceof Build) {
            $directory = Path::from($build->getDirectory());
            $directory = $directory->add('widgets/'.$this->getNamespace().'/'.$this->getId());
        } else {
            $directory = $this->getDirectory()->add('templates');
        }

        return $directory;
    }

    /**
     * Возвращает шаблоны виджета.
     * @param Build $build Сборка.
     * @param boolean $collection Возвращать как коллекцию.
     * @param boolean $refresh Обновить список шаблонов в кеше.
     * @return Templates|Template[]
     */
    public function getTemplates($build = null, $collection = true, $refresh = false)
    {
        if (!($build instanceof Build))
            $build = null;

        $result = new Templates();

        /**
         * Функция загрузки шаблонов.
         * @param Path $directory
         * @param Build|null $build
         * @return array
         */
        $load = function ($directory, $build = null) {
            $result = [];
            $entries = FileHelper::getDirectoryEntries($directory->getValue(), false);

            foreach ($entries as $entry) {
                $path = $directory.'/'.$entry;
                $template = new Template($this, $path, $build);
                $result[$template->getCode()] = $template;
            }

            return $result;
        };

        if (!ArrayHelper::keyExists('*', $this->_templates) || $refresh)
            $this->_templates['*'] = $load($this->getTemplatesDirectory());

        $result->setRange($this->_templates['*']);

        if (!empty($build)) {
            if (!ArrayHelper::keyExists($build->code, $this->_templates) || $refresh)
                $this->_templates[$build->code] = $load($this->getTemplatesDirectory($build));

            $result->setRange($this->_templates[$build->code]);
        }

        if (!$collection)
            return $result->asArray();

        return $result;
    }

    /**
     * Возвращает шаблон по коду.
     * @param string $code
     * @param Build|null $build
     * @return Template|null
     */
    public function getTemplate($code = null, $build = null)
    {
        if ($code === null)
            $code = '.default';

        $templates = $this->getTemplates($build);
        $template = $templates->get($code);

        return $template;
    }
}