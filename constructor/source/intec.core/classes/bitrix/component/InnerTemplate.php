<?php
namespace intec\core\bitrix\component;

use CBitrixComponent;
use CBitrixComponentTemplate;
use Bitrix\Main\Localization\Loc;
use intec\core\base\BaseObject;
use intec\core\db\Exception;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Type;
use intec\core\io\Path;

/**
 * Класс, представляющий собой внутренний шаблон шаблона компонента.
 * Class InnerTemplate
 * @property string $name
 * @property string $code
 * @property CBitrixComponent $component
 * @property CBitrixComponentTemplate $template
 * @property Path $directory
 * @package intec\core\bitrix\component
 * @author apocalypsisdimon@gmail.com
 */
class InnerTemplate extends BaseObject
{
    /**
     * @var string
     */
    protected $_name;
    /**
     * @var string
     */
    protected $_code;
    /**
     * @var Path
     */
    protected $_directory;
    /**
     * @var CBitrixComponentTemplate
     */
    protected $_template;

    /**
     * InnerTemplate constructor.
     * @param string|Path $directory
     * @param CBitrixComponentTemplate $template
     * @param string $code
     */
    public function __construct($directory, $template, $code)
    {
        if (!($template instanceof CBitrixComponentTemplate))
            throw new Exception('Argument "template" is not a CBitrixComponentTemplate.');

        $code = Type::toString($code);

        if (empty($code))
            throw new Exception('Argument "code" cannot be empty.');

        $this->_directory = Path::from($directory);
        $this->_template = $template;
        $this->_code = $code;

        $description = $this->getDescription();
        $this->_name = ArrayHelper::getValue($description, 'name');

        if (empty($this->_name) && !Type::isNumeric($this->_name))
            $this->_name = $code;

        parent::__construct([]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @return CBitrixComponent
     */
    public function getComponent()
    {
       return $this->_template->__component;
    }

    /**
     * @return CBitrixComponentTemplate
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * @return Path
     */
    public function getDirectory()
    {
        return $this->_directory;
    }

    /**
     * @return Path
     */
    protected function getDescriptionPath()
    {
        return $this->_directory->add('description.php');
    }

    /**
     * @return array
     */
    protected function getDescription()
    {
        $path = $this->getDescriptionPath();
        $result = null;

        if (FileHelper::isFile($path->value))
            $result = include($path->value);

        if (!Type::isArray($result))
            $result = [];

        return $result;
    }

    /**
     * @return Path
     */
    public function getParametersPath()
    {
        return $this->_directory->add('parameters.php');
    }

    /**
     * @return array
     */
    public function getParameters($componentName, $templateName, $siteTemplate, $arCurrentValues)
    {
        $path = $this->getParametersPath();
        $parameters = [];

        if (FileHelper::isFile($path->value)) {
            Loc::loadMessages($path->value);
            $parameters = include($path->value);
        }

        if (!Type::isArray($parameters))
            $parameters = [];

        return $parameters;
    }

    /**
     * @return Path
     */
    public function getModifierPath()
    {
        return $this->_directory->add('modifier.php');
    }

    /**
     * @param $arResult
     * @param $arParams
     * @param $arData
     */
    public function modify(&$arParams, &$arResult, &$arData = null)
    {
        global $APPLICATION;
        global $DB;
        global $USER;

        $path = $this->getModifierPath();

        if (FileHelper::isFile($path->value)) {
            Loc::loadMessages($path->value);
            include($path->value);
        }
    }

    /**
     * @return Path
     */
    public function getStylePath()
    {
        return $this->_directory->add('style.css');
    }

    /**
     * @return Path
     */
    public function getScriptPath()
    {
        return $this->_directory->add('script.js');
    }

    /**
     * @return Path
     */
    public function getViewPath()
    {
        return $this->_directory->add('view.php');
    }

    /**
     * @param $arResult
     * @param $arParams
     * @param $arData
     */
    public function render(&$arParams, &$arResult, &$arData = null)
    {
        global $APPLICATION;
        global $DB;
        global $USER;

        $stylePath = $this->getStylePath();
        $scriptPath = $this->getScriptPath();

        if (FileHelper::isFile($stylePath->value))
            $this->getTemplate()->addExternalCss(
                $stylePath
                    ->toRelative()
                    ->asAbsolute()
                    ->getValue('/')
            );

        if (FileHelper::isFile($scriptPath->value))
            $this->getTemplate()->addExternalJS(
                $scriptPath
                    ->toRelative()
                    ->asAbsolute()
                    ->getValue('/')
            );

        $path = $this->getViewPath();

        if (FileHelper::isFile($path->value)) {
            Loc::loadMessages($path->value);
            include($path->value);
        }
    }
}