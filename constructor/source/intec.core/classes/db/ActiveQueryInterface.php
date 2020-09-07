<?php
namespace intec\core\db;

/**
 * Определяет базовые методы для ActiveQuery.
 * Interface ActiveQueryInterface
 * @package intec\core\db
 * @since 1.0.0
 */
interface ActiveQueryInterface extends QueryInterface
{
    /**
     * Устанавливает свойство [[asArray]].
     * @param bool $value Возвращать результаты в виде массива.
     * @return $this Текущий запрос.
     * @since 1.0.0
     */
    public function asArray($value = true);

    /**
     * Выполняет запрос и возвращает одну запись.
     * @param Connection $db Подключение к базе данных.
     * @return ActiveRecordInterface|array|null Одна запись или `null`, если результат пуст.
     * @since 1.0.0
     */
    public function one($db = null);

    /**
     * Устанавливает свойство [[indexBy]]. Индексировать результаты.
     * @param string|callable $column Наименование столбца.
     * @return $this Текущий запрос.
     * @since 1.0.0
     */
    public function indexBy($column);

    /**
     * Устанавливает реляции, которые необходимо получить с данным запросом.
     * @return $this Текущий запрос.
     * @since 1.0.0
     */
    public function with();

    /**
     * Использует реляцию в качестве таблицы для связи.
     * @param string $relationName Наименование реляции.
     * @param callable $callable Функция обратного вызова для кастомизации.
     * @return $this Текущий запрос.
     * @since 1.0.0
     */
    public function via($relationName, callable $callable = null);

    /**
     * Ищет реляции для определенной модели.
     * @param string $name Наименование реляции.
     * @param ActiveRecordInterface $model Модель.
     * @return mixed Записи реляций.
     * @since 1.0.0
     */
    public function findFor($name, $model);
}
