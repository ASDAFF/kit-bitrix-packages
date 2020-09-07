<?php
namespace intec\core\base;

use phpMorphy;
use phpMorphy_Exception;
use intec\core\libraries\phpmorphy\Library;

/**
 * Компонент, отвечающий за морфологию.
 * Class Morphology
 * @property boolean $isLoaded
 * @package intec\core\base
 * @author apocalypsisdimon@gmail.com
 */
class Morphology extends Component
{
    /**
     * Экземпляр класса морфологии.
     * @var phpMorphy|null
     */
    protected $_instance;

    /**
     * Состояние компонента.
     * @var boolean
     */
    protected $_loaded = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $library = $this->getLibrary();

        if ($library->load()) {
            try {
                $instance = new phpMorphy($library->getDictionariesDirectory(), 'ru_RU', [
                    'storage' => PHPMORPHY_STORAGE_FILE
                ]);

                $this->_instance = $instance;
            } catch (phpMorphy_Exception $exception) {
                $this->_loaded = false;
            }
        }
    }

    /**
     * Возвращает экземпляр класса морфологии.
     * @return phpMorphy
     */
    public function getInstance()
    {
        return $this->_instance;
    }

    /**
     * Возвращает библиотеку компонента.
     * @return Library|null
     */
    public function getLibrary()
    {
        return Library::getInstance();
    }

    /**
     * Возвращает состояние компонента.
     * @return boolean
     */
    public function getIsLoaded()
    {
        return $this->getLibrary()->getIsLoaded() && $this->_loaded;
    }
}