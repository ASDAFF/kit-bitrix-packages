<?php
namespace intec\constructor\models\block;

use Bitrix\Main\Localization\Loc;
use intec\constructor\base\Exchangeable;
use intec\Core;
use intec\core\base\InvalidParamException;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Json;
use intec\core\helpers\Type;
use intec\core\io\Path;
use ZipArchive;

Loc::loadMessages(__FILE__);

/**
 * Class Category
 * @property string $code
 * @property integer $sort
 * @property string $name
 * @package intec\constructor\models\block
 */
class Category extends ActiveRecord implements Exchangeable
{
    /**
     * Действие импорта: Никакого
     */
    const IMPORT_ACTION_NONE = 'none';
    /**
     * Действие импорта: Заменить
     */
    const IMPORT_ACTION_REPLACE = 'replace';
    /**
     * Действие импорта: Добавить префикс
     */
    const IMPORT_ACTION_PREFIX = 'prefix';

    /**
     * Возвращает список возможных действий.
     * @return array
     */
    public static function getImportActions()
    {
        return [
            self::IMPORT_ACTION_NONE => Loc::getMessage('intec.constructor.models.block.category.importActions.none'),
            self::IMPORT_ACTION_REPLACE => Loc::getMessage('intec.constructor.models.block.category.importActions.replace'),
            self::IMPORT_ACTION_PREFIX => Loc::getMessage('intec.constructor.models.block.category.importActions.prefix')
        ];
    }

    /**
     * Возвращает список возможных значений действий.
     * @return array
     */
    public static function getImportActionsValues()
    {
        $values = self::getImportActions();
        $values = ArrayHelper::getKeys($values);

        return $values;
    }

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_blocks_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'string', 'max' => 255],
            [['sort'], 'integer'],
            [['sort'], 'default', 'value' => 500],
            [['code'], 'match', 'pattern' => '/^[A-Za-z0-9._-]*$/'],
            [['code'], 'unique'],
            [['code', 'sort', 'name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Loc::getMessage('intec.constructor.models.block.category.attributes.labels.code'),
            'sort' => Loc::getMessage('intec.constructor.models.block.category.attributes.labels.sort'),
            'name' => Loc::getMessage('intec.constructor.models.block.category.attributes.labels.name')
        ];
    }

    /**
     * Реляция. Возвращает блоки категории.
     * @param bool $result Результат.
     * @return Template|ActiveQuery|null
     */
    public function getTemplates($result = false)
    {
        return $this->relation(
            'templates',
            $this->hasMany(Template::className(), ['categoryCode' => 'code'])
                ->orderBy('sort'),
            $result
        );
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        $data = $this->toArray();

        unset($data['code']);
        return $data;
    }

    /**
     * Экспортирует категорию в Json строку.
     * @return string
     */
    public function exportToJson()
    {
        $data = $this->export();
        $result = ArrayHelper::convertEncoding(
            $data,
            Encoding::UTF8,
            Core::$app->charset
        );

        return Json::encode($result);
    }

    /**
     * Экспортирует категорию в файл.
     * @param Path|string $path
     * @return bool
     */
    public function exportToFile($path)
    {
        $path = Path::from($path);

        if (FileHelper::isFile($path->value))
            return false;

        FileHelper::setFileData(
            $path->value,
            $this->exportToJson()
        );

        return true;
    }

    /**
     * Экспортирует категории в массив.
     * @return array
     */
    public static function exportAll()
    {
        $categories = static::find()->all();
        $data = [];

        /** @var Category $category */
        foreach ($categories as $category)
            $data[$category->code] = $category->export();

        return $data;
    }

    /**
     * Экспортирует категории в Json.
     * @return string
     */
    public static function exportAllToJson()
    {
        $data = static::exportAll();
        $result = ArrayHelper::convertEncoding(
            $data,
            Encoding::UTF8,
            Core::$app->charset
        );

        return Json::encode($result);
    }

    /**
     * Экспортирует категории в файл.
     * @return string
     */
    public static function exportAllToFile($path)
    {
        $path = Path::from($path);

        if (FileHelper::isFile($path->value))
            return false;

        FileHelper::setFileData(
            $path->value,
            static::exportAllToJson()
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function import($data)
    {
        unset($data['code']);

        foreach ($this->attributes() as $attribute) {
            if ($attribute == 'code')
                continue;

            $this->setAttribute($attribute, null);
        }

        $this->load($data, '');

        return $this->save();
    }

    /**
     * Импортирует категорию из Json.
     * @param $data
     * @return bool
     */
    public function importFromJson($data)
    {
        try {
            $data = Json::decode($data);
        } catch (InvalidParamException $exception) {
            return false;
        }

        if (!Type::isArray($data))
            return false;

        $data = ArrayHelper::convertEncoding(
            $data,
            Core::$app->charset,
            Encoding::UTF8
        );

        return $this->import($data);
    }

    /**
     * Импортирует категорию из файла.
     * @param Path|string $path
     * @return bool
     */
    public function importFromFile($path)
    {
        $path = Path::from($path);

        if (!FileHelper::isFile($path->value))
            return false;

        return $this->importFromJson(FileHelper::getFileData($path));
    }

    /**
     * Импортирует категории в массив.
     * @param array $data Данные.
     * @param string $action Действие с существующими категориями.
     * @param string|null $prefix Префикс.
     * @return boolean
     */
    public static function importAll($data, $action = self::IMPORT_ACTION_NONE, $prefix = null)
    {
        if (!Type::isArrayable($data))
            return false;

        $categories = static::find()
            ->indexBy('code')
            ->all();

        foreach ($data as $code => $categoryData) {
            if (!Type::isArray($categoryData))
                continue;

            $category = new Category();
            $category->code = $code;

            if ($categories->exists($category->code)) {
                if ($action === self::IMPORT_ACTION_REPLACE) {
                    $category = $categories->get($category->code);
                } else if ($action === self::IMPORT_ACTION_PREFIX) {
                    if (empty($prefix))
                        continue;

                    while ($categories->exists($category->code))
                        $category->code = $prefix.$category->code;
                } else {
                    continue;
                }
            }

            $category->import($categoryData);
        }

        return true;
    }

    /**
     * Импортирует категории из Json.
     * @param string $data Данные.
     * @param string $action Действие с существующими категориями.
     * @param string|null $prefix Префикс.
     * @return boolean
     */
    public static function importAllFromJson($data, $action = self::IMPORT_ACTION_NONE, $prefix = null)
    {
        try {
            $data = Json::decode($data);
        } catch (InvalidParamException $exception) {
            return false;
        }

        if (!Type::isArray($data))
            return false;

        $data = ArrayHelper::convertEncoding(
            $data,
            Core::$app->charset,
            Encoding::UTF8
        );

        return static::importAll(
            $data,
            $action,
            $prefix
        );
    }

    /**
     * Экспортирует категории в файл.
     * @param Path|string $path Данные.
     * @param string $action Действие с существующими категориями.
     * @param string|null $prefix Префикс.
     * @return boolean
     */
    public static function importAllFromFile($path, $action = self::IMPORT_ACTION_NONE, $prefix = null)
    {
        $path = Path::from($path);

        if (!FileHelper::isFile($path->value))
            return false;

        return static::importAllFromJson(
            FileHelper::getFileData($path),
            $action,
            $prefix
        );
    }
}