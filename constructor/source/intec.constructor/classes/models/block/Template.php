<?php
namespace intec\constructor\models\block;

use Bitrix\Main\Localization\Loc;
use intec\constructor\base\Exchangeable;
use intec\constructor\structure\block\Resources;
use intec\constructor\structure\BlockInterface;
use intec\Core;
use intec\core\base\InvalidParamException;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Json;
use intec\core\helpers\RegExp;
use intec\core\helpers\Type;
use intec\core\io\Path;
use ZipArchive;
use CFile;
use intec\constructor\structure\Block as Model;

Loc::loadMessages(__FILE__);

/**
 * Class Template
 * @property string $code
 * @property string $categoryCode
 * @property integer $sort
 * @property string $name
 * @property integer $image
 * @property array $data
 * @package intec\constructor\models\block
 */
class Template extends ActiveRecord implements Exchangeable, BlockInterface
{
    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_blocks_templates';
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->getAttribute('code') !== $this->getOldAttribute('code')) {
                $directory = $this->getResourcesDirectory(true);

                if (FileHelper::isDirectory($directory))
                    rename(
                        $directory,
                        $this->getResourcesDirectory()
                    );
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            FileHelper::removeDirectory($this->getResourcesDirectory(true));

            if (!empty($this->image))
                CFile::Delete($this->image);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'data' => [
                'class' => 'intec\core\behaviors\FieldArray',
                'attribute' => 'data'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'categoryCode', 'name'], 'string', 'max' => 255],
            [['image', 'sort'], 'integer'],
            [['sort'], 'default', 'value' => 500],
            [['code', 'categoryCode'], 'match', 'pattern' => '/^[A-Za-z0-9._-]*$/'],
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
            'code' => Loc::getMessage('intec.constructor.models.block.template.attributes.labels.code'),
            'categoryCode' => Loc::getMessage('intec.constructor.models.block.template.attributes.labels.categoryCode'),
            'sort' => Loc::getMessage('intec.constructor.models.block.template.attributes.labels.sort'),
            'name' => Loc::getMessage('intec.constructor.models.block.template.attributes.labels.name'),
            'image' => Loc::getMessage('intec.constructor.models.block.template.attributes.labels.image'),
            'data' => Loc::getMessage('intec.constructor.models.block.template.attributes.labels.data')
        ];
    }

    /**
     * Реляция. Возвращает категорию.
     * @param bool $result Результат.
     * @return Category|ActiveQuery|null
     */
    public function getCategory($result = false)
    {
        return $this->relation(
            'category',
            $this->hasOne(Category::className(), ['code' => 'categoryCode']),
            $result
        );
    }

    /**
     * Возвращает директорию ресурсов блока.
     * @param bool $old
     * @return Path
     */
    public function getResourcesDirectory($old = false)
    {
        $directory = Path::from('@intec/constructor/upload/blocks/templates');
        $directory = $directory->add(
            $old ?
                $this->getOldAttribute('code') :
                $this->getAttribute('code')
        );

        return $directory;
    }

    /**
     * @inheritdoc
     */
    public function getResources()
    {
        return new Resources($this->getResourcesDirectory(true));
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Возвращает модель блока.
     * @return Model
     */
    public function getModel()
    {
        if (!$this->getIsNewRecord())
            return Model::from(
                $this->data,
                $this->getResources()
            );

        return null;
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        $result = $this->toArray();

        unset($result['categoryCode']);
        unset($result['image']);

        $result['data'] = $this->data;

        return $result;
    }

    /**
     * Экспортирует шаблон в Json.
     * @return string
     */
    public function exportToJson()
    {
        $result = ArrayHelper::convertEncoding(
            $this->export(),
            Encoding::UTF8,
            Core::$app->charset
        );

        return Json::encode($result);
    }

    /**
     * Экспортирует шаблон в файл.
     * @param string $path
     * @return boolean
     */
    public function exportToFile($path)
    {
        if (!extension_loaded('zip'))
            return false;

        $path = FileHelper::normalizePath($path);

        if (FileHelper::isFile($path))
            return false;

        $directory = FileHelper::getEntryDirectory($path);

        if (!FileHelper::isDirectory($directory))
            FileHelper::createDirectory($directory);

        if (!FileHelper::isDirectory($directory))
            return false;

        $archive = new ZipArchive();

        if (!$archive->open($path, ZipArchive::CREATE))
            return false;

        $archive->addFromString('template.json', $this->exportToJson());

        if (!empty($this->image)) {
            $image = CFile::GetFileArray($this->image);

            if (!empty($image)) {
                $image = Path::from('@root/'.$image['SRC']);
                $archive->addFile($image->getValue(), 'image.'.$image->getExtension());
            }
        }

        $directory = $this->getResources()->getDirectory();
        $pack = function($prefix = null, $parent = null) use (&$pack, &$archive, $directory) {
            if ($parent !== null) {
                $directory = $directory->add($parent);
            } else if ($prefix === null) {
                $archive->addEmptyDir($prefix);
            }

            $entries = FileHelper::getDirectoryEntries($directory->value, false);

            foreach ($entries as $entry) {
                $path = $directory->add($entry);
                $pathRelative = Path::from($parent)->add($entry);
                $pathLocal = Path::from($prefix)->add($pathRelative);

                if (FileHelper::isFile($path->value)) {
                    $archive->addFile($path->value, $pathLocal->getValue('/'));
                } else if (FileHelper::isDirectory($path->value)) {
                    $archive->addEmptyDir($pathLocal->getValue('/'));

                    $pack(
                        $prefix,
                        $pathRelative->value
                    );
                }
            }
        };

        $pack('resources');
        $archive->close();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function import($data)
    {
        foreach ($this->attributes() as $attribute)
            if ($attribute != 'code' && $attribute != 'categoryCode' && $attribute != 'image')
                $this->setAttribute($attribute, null);

        unset($data['code']);
        unset($data['categoryCode']);
        unset($data['image']);

        $this->load($data, '');
        $this->data = ArrayHelper::getValue($data, 'data');

        return $this->save();
    }

    /**
     * Импортирует шаблон из Json.
     * @param string $data
     * @return boolean
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
     * Импортирует шаблон из директории.
     * @param Path|string $directory
     * @return boolean
     */
    public function importFromDirectory($directory)
    {
        $directory = Path::from($directory);

        if (!FileHelper::isDirectory($directory->value))
            return false;

        $dataPath = $directory->add('template.json');

        if (!FileHelper::isFile($dataPath->value))
            return false;

        $data = FileHelper::getFileData($dataPath->value);
        $result = $this->importFromJson($data);

        if (!$result)
            return false;

        $entries = FileHelper::getDirectoryEntries($directory);
        $expression = new RegExp('^image\\.(png|jpg|jpeg|bmp|gif)$');
        $expression->isInsensitive(true);

        foreach ($entries as $entry) {
            if ($expression->isMatch($entry)) {
                $image = $directory->add($entry);
                $image = CFile::MakeFileArray($image->value);

                if (!empty($image)) {
                    $image = CFile::SaveFile($image,
                        Path::from('@intec/constructor/upload')
                        ->getRelativeFrom('@upload')
                        ->add('blocks/previews')
                        ->getValue('/')
                    );

                    if (!empty($image)) {
                        $this->image = $image;
                        $this->save();
                    }
                }

                break;
            }
        }

        $resourcesPath = $directory->add('resources');
        $resourcesPathTo = $this->getResources()->getDirectory();

        if (FileHelper::isDirectory($resourcesPath->value)) {
            $parent = $resourcesPathTo->getParent();

            FileHelper::removeDirectory($resourcesPathTo->value);

            if (!FileHelper::isDirectory($parent->value))
                FileHelper::createDirectory($parent->value);

            FileHelper::copyDirectory(
                $resourcesPath->value,
                $resourcesPathTo->value
            );
        }

        return true;
    }

    /**
     * Импортирует шаблон из файла.
     * @param Path|string $path
     * @param Path|string|null $directory
     * @return boolean
     */
    public function importFromFile($path, $directory = null)
    {
        if (!extension_loaded('zip'))
            return false;

        if ($directory === null)
            $directory = $path;

        $path = Path::from($path);
        $directory = Path::from($directory);

        if (!FileHelper::isFile($path->value))
            return false;

        if (FileHelper::isDirectory($directory->value))
            return false;

        if (!FileHelper::createDirectory($directory->value))
            return false;

        $archive = new ZipArchive();

        if ($archive->open($path->value) !== true) {
            FileHelper::removeDirectory($directory->value);
            return false;
        }

        $archive->extractTo($directory->value);
        $archive->close();
        $result = $this->importFromDirectory($directory);

        FileHelper::removeDirectory($directory->value);

        return $result;
    }
}