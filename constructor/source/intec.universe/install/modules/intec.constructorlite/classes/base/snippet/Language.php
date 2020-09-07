<?php
namespace intec\constructor\base\snippet;

use intec\core\base\InvalidParamException;
use intec\core\base\BaseObject;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\constructor\base\Snippet;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * Class Language
 * @property Snippet $object
 * @property array $messages
 * @package intec\constructor\base\snippet
 */
class Language extends BaseObject
{
    /**
     * @var Snippet
     */
    protected $_object;
    /**
     * @var array
     */
    protected $_messages = [];

    /**
     * Language constructor.
     * @param Snippet $object
     */
    public function __construct($object)
    {
        if (!($object instanceof Snippet))
            throw new InvalidParamException('Object is not a "'.Snippet::className().'" instance.');

        $this->_object = $object;

        parent::__construct([]);
    }

    /**
     * Возвращает объект, к которому привязан.
     * @return Snippet
     */
    public function getObject()
    {
        return $this->_object;
    }

    /**
     * Возвращает языкозависимые строки.
     * @param string $language Язык.
     * @return array
     */
    public function getMessages($language = LANGUAGE_ID)
    {
        $messages = [];

        if (empty($language))
            return $messages;

        $messages = ArrayHelper::getValue($this->_messages, $language);

        if ($messages === null) {
            $file = $this->getObject()->getDirectory()->add('lang/'.$language.'.php');

            if (FileHelper::isFile($file))
                $messages = include($file);

            if (!Type::isArray($messages))
                $messages = [];
        }

        return $messages;
    }

    /**
     * Возвращает языкозависимую строку по коду.
     * @param string $code
     * @param array $macros
     * @param string $language
     */
    public function getMessage($code, $macros = [], $language = LANGUAGE_ID)
    {
        $messages = $this->getMessages($language);
        $message = ArrayHelper::getValue($messages, $code);

        return StringHelper::replaceMacros(
            $message,
            $macros
        );
    }
}