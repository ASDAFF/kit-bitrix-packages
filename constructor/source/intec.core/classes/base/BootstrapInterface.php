<?php
namespace intec\core\base;

/**
 * Интерфейс для автозагрузки модулей.
 * Interface BootstrapInterface
 * @package intec\core\base
 * @since 1.0.0
 */
interface BootstrapInterface
{
    /**
     * Данный метод будет вызван после того, как основное приложение будет загружено.
     * @param Application $app Приложение которое запущено.
     * @since 1.0.0
     */
    public function bootstrap($app);
}
