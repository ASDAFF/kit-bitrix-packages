<?php
namespace intec\core\bitrix;

use intec\core\base\Component;
use intec\core\bitrix\web\JavaScript;
use intec\core\bitrix\web\Css;
use intec\core\bitrix\web\Less;
use intec\core\bitrix\web\Scss;

/**
 * Class Web
 * @property JavaScript $js Управление скриптами и расширениями JavaScript.
 * @property Css $css Управление стилями.
 * @property Less $less Генерация стилей на less.
 * @property Scss $scss Генерация стилей на scss.
 * @package intec\core\bitrix
 */
class Web extends Component
{
    /**
     * Объект JavaScript.
     * @var JavaScript
     */
    protected $_js;
    /**
     * Объект Css.
     * @var Css
     */
    protected $_css;
    /**
     * Объект Less.
     * @var Less
     */
    protected $_less;
    /**
     * Объект Scss.
     * @var Scss
     */
    protected $_scss;

    public function init()
    {
        parent::init();

        $this->_js = new JavaScript();
        $this->_css = new Css();
        $this->_less = new Less();
        $this->_scss = new Scss();
    }

    /**
     * Возвращает объект JavaScript.
     * @return JavaScript
     */
    public function getJs()
    {
        return $this->_js;
    }

    /**
     * Возвращает объект Css.
     * @return Css
     */
    public function getCss()
    {
        return $this->_css;
    }

    /**
     * Возвращает объект Less.
     * @return Less
     */
    public function getLess()
    {
        return $this->_less;
    }

    /**
     * Возвращает объект Scss.
     * @return Scss
     */
    public function getScss()
    {
        return $this->_scss;
    }
}