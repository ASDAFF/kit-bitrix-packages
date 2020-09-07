<?php
namespace intec\core\base;

use intec\Core;
use intec\core\Parameters;
use intec\core\bitrix\Web;
use intec\core\db\Connection;
use intec\core\i18n\Formatter;
use intec\core\i18n\I18N;
use intec\core\web\Browser;
use intec\core\web\Request;
use intec\core\web\Session;

/**
 * Приложение.
 * Class Application
 * @property array $container Поля вида ключ -> значение. Только для записи.
 * @property Browser $browser Компонент браузера. Только для чтения.
 * @property Connection $db Подключение к базе данных. Только для чтения.
 * @property Formatter $formatter Компонент для форматирования. Только для чтения.
 * @property I18N $i18n Компонент, управляющий языковыми пакетами. Только для чтения.
 * @property Parameters $parameters Компонент параметров. Только для чтения.
 * @property Request $request Компонент запроса. Только для чтения.
 * @property Session $session Компонент сессий. Только для чтения.
 * @property Security $security Копонент, отвечающий за безопасность. Только для чтения.
 * @property Morphology $morphology Копонент, отвечающий за морфологию. Только для чтения.
 * @property Web $web Копонент, отвечающий web контент. Только для чтения.
 * @property string $timeZone Временная зона, используемая приложением.
 * @property string $uniqueId Уникальный идентификатор приложения. Только для чтения.
 * @package intec\core\base
 * @since 1.0.0
 */
class Application extends Module
{
    /**
     * Событие которое вызывается перед обработкой запроса.
     * @event Event
     * @since 1.0.0
     */
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    /**
     * Событие которое вызывается после успешной обработки запроса.
     * @event Event.
     * @since 1.0.0
     */
    const EVENT_AFTER_REQUEST = 'afterRequest';

    /**
     * @var string Имя приложения.
     * @since 1.0.0
     */
    public $name = 'Intec core';

    /**
     * Кодировка, используемая приложением.
     * @var string
     * @since 1.0.0
     */
    public $charset = 'UTF-8';

    /**
     * Язык приложения.
     * @var string
     * @since 1.0.0
     */
    public $language = 'ru-RU';

    /**
     * Язык, на котором написаны ошибки и предупреждения.
     * @var string
     * @since 1.0.0
     */
    public $sourceLanguage = 'en-US';

    /**
     * Список компонентов и модулей которые должны быть загружены сразу.
     * Компоненты должны реализовывать интерфейс [[BootstrapInterface]].
     * @var array
     * @since 1.0.0
     */
    public $bootstrap = [];

    /**
     * Список загруженных модулей, проиндексированных по именам их классов.
     * @var array
     * @since 1.0.0
     */
    public $loadedModules = [];

    /**
     * Конструктор.
     * @param array $config Параметры объекта.
     * Обязательные поля [[id]] и [[basePath]].
     * @throws InvalidConfigException Если обязательные поля отсутствуют.
     * @since 1.0.0
     */
    public function __construct($config = [])
    {
        Core::$app = $this;
        static::setInstance($this);

        $this->preInit($config);

        Component::__construct($config);
    }

    /**
     * Предзагрузка приложения.
     * Вызывается после конструктора.
     * Инициализирует необходимые свойства приложения.
     * @param array $config the application configuration
     * @throws InvalidConfigException Если обязательные поля отсутствуют.
     * @since 1.0.0
     */
    public function preInit(&$config)
    {
        if (!isset($config['id'])) {
            throw new InvalidConfigException('The "id" configuration for the Application is required.');
        }
        if (isset($config['basePath'])) {
            $this->setBasePath($config['basePath']);
            unset($config['basePath']);
        } else {
            throw new InvalidConfigException('The "basePath" configuration for the Application is required.');
        }

        if (isset($config['timeZone'])) {
            $this->setTimeZone($config['timeZone']);
            unset($config['timeZone']);
        } elseif (!ini_get('date.timezone')) {
            $this->setTimeZone('UTC');
        }

        if (isset($config['container'])) {
            $this->setContainer($config['container']);

            unset($config['container']);
        }

        // Объединение компонентов ядра с пользовательскими
        foreach ($this->coreComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->bootstrap();
    }

    /**
     * Производит загрузку модулей и компонентов.
     * Этот метод вызывается после метода [[init()]].
     * @since 1.0.0
     */
    protected function bootstrap()
    {
        foreach ($this->bootstrap as $class) {
            $component = null;
            if (is_string($class)) {
                if ($this->has($class)) {
                    $component = $this->get($class);
                } elseif ($this->hasModule($class)) {
                    $component = $this->getModule($class);
                } elseif (strpos($class, '\\') === false) {
                    throw new InvalidConfigException("Unknown bootstrapping component ID: $class");
                }
            }
            if (!isset($component)) {
                $component = Core::createObject($class);
            }

            if ($component instanceof BootstrapInterface) {
                $component->bootstrap($this);
            }
        }
    }

    /**
     * Возвращает уникальный идентификатор приложения.
     * Для приложения он всегда пустой т.к. используется только для модулей.
     * @return string Уникальный идентификатор.
     * @since 1.0.0
     */
    public function getUniqueId()
    {
        return '';
    }

    /**
     * Устанавливает базовую директорию приложения.
     * Этот метод может быть вызван только вначале конструктора.
     * @param string $path Корневая директория приложения.
     * @throws InvalidParamException Если директории не существует.
     * @since 1.0.0
     */
    public function setBasePath($path)
    {
        parent::setBasePath($path);
        Core::setAlias('@root', $path);
    }

    /**
     * Возвращает временную зону, используемую приложением.
     * @return string Временная зона.
     * @since 1.0.0
     */
    public function getTimeZone()
    {
        return date_default_timezone_get();
    }

    /**
     * Устанавливает временную зону, используемую приложением.
     * @param string $value Временная зона.
     * @since 1.0.0
     */
    public function setTimeZone($value)
    {
        date_default_timezone_set($value);
    }

    /**
     * Возвращает компонент браузера.
     * @return Browser Компонент браузера.
     * @since 1.0.0
     */
    public function getBrowser()
    {
        return $this->get('browser');
    }

    /**
     * Возвращает компонент подключения к базе данных.
     * @return Connection Компонент подключения к базе данных.
     * @since 1.0.0
     */
    public function getDb()
    {
        return $this->get('db');
    }

    /**
     * Возвращает компонент для форматирования.
     * @return Formatter
     */
    public function getFormatter()
    {
        return $this->get('formatter');
    }

    /**
     * Возвращает компонент для управления языковыми пакетами.
     * @return I18N
     */
    public function getI18n()
    {
        return $this->get('i18n');
    }

    /**
     * Возвращает компонент, отвечающий за безопасность.
     * @return Security Компонент, отвечающий за безопасность.
     * @since 1.0.0
     */
    public function getSecurity()
    {
        return $this->get('security');
    }

    /**
     * Возвращает компонент, отвечающий за морфологию.
     * @return Morphology Компонент, отвечающий за морфологию.
     * @since 1.0.0
     */
    public function getMorphology()
    {
        return $this->get('morphology');
    }

    /**
     * Возвращает компонент, отвечающий за параметры.
     * @return Parameters Компонент, отвечающий за параметры.
     * @since 1.0.0
     */
    public function getParameters()
    {
        return $this->get('parameters');
    }

    /**
     * Возвращает компонент, отвечающий за запрос.
     * @return Request Компонент, отвечающий за запрос.
     * @since 1.0.0
     */
    public function getRequest()
    {
        return $this->get('request');
    }

    /**
     * Возвращает компонент, отвечающий за сессию.
     * @return Session Компонент, отвечающий за сессию.
     * @since 1.0.0
     */
    public function getSession()
    {
        return $this->get('session');
    }

    /**
     * Возвращает компонент, отвечающий за подключение web контента.
     * @return Web Компонент, отвечающий за подключение web контента.
     * @since 1.0.0
     */
    public function getWeb()
    {
        return $this->get('web');
    }

    /**
     * Возвращает список базовых компонентов данного приложения.
     * @see set()
     * @since 1.0.0
     */
    public function coreComponents()
    {
        return [
            'browser' => ['class' => 'intec\core\web\Browser'],
            'formatter' => ['class' => 'intec\core\i18n\Formatter'],
            'i18n' => ['class' => 'intec\core\i18n\I18N'],
            'db' => ['class' => 'intec\core\db\Connection'],
            'security' => ['class' => 'intec\core\base\Security'],
            'morphology' => ['class' => 'intec\core\base\Morphology'],
            'parameters' => ['class' => 'intec\core\Parameters'],
            'request' => ['class' => 'intec\core\web\Request'],
            'session' => ['class' => 'intec\core\web\Session'],
            'web' => ['class' => 'intec\core\bitrix\Web']
        ];
    }

    /**
     * Настраивает [[Core::$container]] с помощью массива параметров.
     *
     * @param array $config Массив пар ключ-значение.
     * @since 1.0.0
     */
    public function setContainer($config)
    {
        Core::configure(Core::$container, $config);
    }
}
