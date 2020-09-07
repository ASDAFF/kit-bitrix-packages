<?php
namespace intec\core\db;

use intec\core\base\BaseObject;
use intec\core\base\InvalidConfigException;

/**
 * Представляет транзакцию базы данных.
 * Class Transaction
 * @property bool $isActive Транзакция ативна. Только в активной транзакции
 * можно использовать [[commit()]] или [[rollBack()]]. Только для чтения.
 * @property string $isolationLevel Уровень изоляции транзакции. Это свойство может принимать одно из значений
 * [[READ_UNCOMMITTED]], [[READ_COMMITTED]], [[REPEATABLE_READ]] и [[SERIALIZABLE]]. Только для записи.
 * @property int $level Текущий уровень вложенности транзакции. Только для чтения.
 * @package intec\core\db
 * @since 1.0.0
 */
class Transaction extends BaseObject
{
    /**
     * Константа уровня изоляции `READ UNCOMMITTED`.
     * @since 1.0.0
     */
    const READ_UNCOMMITTED = 'READ UNCOMMITTED';
    /**
     * Константа уровня изоляции `READ COMMITTED`.
     * @since 1.0.0
     */
    const READ_COMMITTED = 'READ COMMITTED';
    /**
     * Константа уровня изоляции `REPEATABLE READ`.
     * @since 1.0.0
     */
    const REPEATABLE_READ = 'REPEATABLE READ';
    /**
     * Константа уровня изоляции `SERIALIZABLE`.
     * @since 1.0.0
     */
    const SERIALIZABLE = 'SERIALIZABLE';

    /**
     * Подключение к базе данных, с которым ассоциируется данная транзакция.
     * @var Connection
     * @since 1.0.0
     */
    public $db;

    /**
     * Текущий уровень вложенности транзакции.
     * @var int
     * @since 1.0.0
     */
    private $_level = 0;


    /**
     * Возвращает значение, отражающее активность транзакции.
     * @return bool
     * @since 1.0.0
     */
    public function getIsActive()
    {
        return $this->_level > 0 && $this->db && $this->db->isActive;
    }

    /**
     * Начало транзакции.
     * @param string|null $isolationLevel Уровень изоляции.
     * @throws InvalidConfigException Если [[db]] содержит `null`.
     * @since 1.0.0
     */
    public function begin($isolationLevel = null)
    {
        if ($this->db === null) {
            throw new InvalidConfigException('Transaction::db must be set.');
        }
        $this->db->open();

        if ($this->_level === 0) {
            if ($isolationLevel !== null) {
                $this->db->getSchema()->setTransactionIsolationLevel($isolationLevel);
            }

            $this->db->trigger(Connection::EVENT_BEGIN_TRANSACTION);
            $this->db->pdo->beginTransaction();
            $this->_level = 1;

            return;
        }

        $schema = $this->db->getSchema();
        if ($schema->supportsSavepoint()) {
            $schema->createSavepoint('LEVEL' . $this->_level);
        }
        $this->_level++;
    }

    /**
     * Проводит транзакцию.
     * @throws Exception Если транзакция неактивна.
     * @since 1.0.0
     */
    public function commit()
    {
        if (!$this->getIsActive()) {
            throw new Exception('Failed to commit transaction: transaction was inactive.');
        }

        $this->_level--;
        if ($this->_level === 0) {
            $this->db->pdo->commit();
            $this->db->trigger(Connection::EVENT_COMMIT_TRANSACTION);
            return;
        }

        $schema = $this->db->getSchema();
        if ($schema->supportsSavepoint()) {
            $schema->releaseSavepoint('LEVEL' . $this->_level);
        }
    }

    /**
     * Откатывает транзакцию.
     * @throws Exception Если транзакция неактивна.
     * @since 1.0.0
     */
    public function rollBack()
    {
        if (!$this->getIsActive()) {
            // do nothing if transaction is not active: this could be the transaction is committed
            // but the event handler to "commitTransaction" throw an exception
            return;
        }

        $this->_level--;
        if ($this->_level === 0) {
            $this->db->pdo->rollBack();
            $this->db->trigger(Connection::EVENT_ROLLBACK_TRANSACTION);
            return;
        }

        $schema = $this->db->getSchema();
        if ($schema->supportsSavepoint()) {
            $schema->rollBackSavepoint('LEVEL' . $this->_level);
        } else {
            // throw an exception to fail the outer transaction
            throw new Exception('Roll back failed: nested transaction not supported.');
        }
    }

    /**
     * Устанавливает уровень изоляции транзакции.
     * @param string $level Уровень изоляции транзакции.
     * @throws Exception Если транзакция неактивна.
     * @since 1.0.0
     */
    public function setIsolationLevel($level)
    {
        if (!$this->getIsActive()) {
            throw new Exception('Failed to set isolation level: transaction was inactive.');
        }
        $this->db->getSchema()->setTransactionIsolationLevel($level);
    }

    /**
     * @return int Текущий уровень вложенности транзакции.
     * @since 1.0.0
     */
    public function getLevel()
    {
        return $this->_level;
    }
}
