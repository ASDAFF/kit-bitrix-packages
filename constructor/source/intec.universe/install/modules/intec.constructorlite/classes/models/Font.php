<?php
namespace intec\constructor\models;

use CFile;
use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\collections\Arrays;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\RegExp;
use intec\constructor\models\font\File;
use intec\constructor\models\font\Link;

Loc::loadMessages(__FILE__);

/**
 * Class Font
 * @property string $code
 * @property integer $active
 * @property string $name
 * @property integer $sort
 * @property integer $type
 * @property boolean $styleCode Код щрифта в стилях. Только для чтения.
 * @property boolean $isRegistered Шрифт подключен. Только для чтения.
 * @property boolean $isValid Шрифт корректкен. Только для чтения.
 * @package intec\constructor\models
 */
class Font extends ActiveRecord
{
    /**
     * Тип: локальный.
     */
    const TYPE_LOCAL = 0;
    /**
     * Тип: внешний.
     */
    const TYPE_EXTERNAL = 1;

    /**
     * Возвращает список типов шрифта.
     * @return array
     */
    public static function getTypes()
    {
        return [
            static::TYPE_LOCAL => Loc::getMessage('intec.constructor.models.font.type.local'),
            static::TYPE_EXTERNAL => Loc::getMessage('intec.constructor.models.font.type.external')
        ];
    }

    /**
     * Возвращает список значений типов шрифта.
     * @return array
     */
    public static function getTypesValues()
    {
        $values = static::getTypes();
        return ArrayHelper::getKeys($values);
    }

    /**
     * Толщина: Thin (Тонкий).
     */
    const WEIGHT_THIN = 100;
    /**
     * Толщина: Extra Light (Дополнительный светлый).
     */
    const WEIGHT_EXTRA_LIGHT = 200;
    /**
     * Толщина: Light (Светлый).
     */
    const WEIGHT_LIGHT = 300;
    /**
     * Толщина: Normal (Нормальный).
     */
    const WEIGHT_NORMAL = 400;
    /**
     * Толщина: Medium (Средний).
     */
    const WEIGHT_MEDIUM = 500;
    /**
     * Толщина: Semi Bold (Полужирный).
     */
    const WEIGHT_SEMI_BOLD = 600;
    /**
     * Толщина: Bold (Жирный).
     */
    const WEIGHT_BOLD = 700;
    /**
     * Толщина: Extra Bold (Дополнительный жирный).
     */
    const WEIGHT_EXTRA_BOLD = 800;
    /**
     * Толщина: Black (Черный).
     */
    const WEIGHT_BLACK = 900;

    /**
     * Возвращает список толщины.
     * @return array
     */
    public static function getWeights()
    {
        return [
            static::WEIGHT_THIN => Loc::getMessage('intec.constructor.models.font.weight.thin'),
            static::WEIGHT_EXTRA_LIGHT => Loc::getMessage('intec.constructor.models.font.weight.extraLight'),
            static::WEIGHT_LIGHT => Loc::getMessage('intec.constructor.models.font.weight.light'),
            static::WEIGHT_NORMAL => Loc::getMessage('intec.constructor.models.font.weight.normal'),
            static::WEIGHT_MEDIUM => Loc::getMessage('intec.constructor.models.font.weight.medium'),
            static::WEIGHT_SEMI_BOLD => Loc::getMessage('intec.constructor.models.font.weight.semiBold'),
            static::WEIGHT_BOLD => Loc::getMessage('intec.constructor.models.font.weight.bold'),
            static::WEIGHT_EXTRA_BOLD => Loc::getMessage('intec.constructor.models.font.weight.extraBold'),
            static::WEIGHT_BLACK => Loc::getMessage('intec.constructor.models.font.weight.black')
        ];
    }

    /**
     * Возвращает список значений толщины.
     * @return array
     */
    public static function getWeightsValues()
    {
        $values = static::getWeights();
        return ArrayHelper::getKeys($values);
    }

    /**
     * Стиль: Обычный.
     */
    const STYLE_NORMAL = 'normal';
    /**
     * Стиль: Наклонный.
     */
    const STYLE_ITALIC = 'italic';
    /**
     * Стиль: Косой.
     */
    const STYLE_OBLIQUE = 'oblique';

    /**
     * Возвращает список стилей шрифта.
     * @return array
     */
    public static function getStyles()
    {
        return [
            static::STYLE_NORMAL => Loc::getMessage('intec.constructor.models.font.style.normal'),
            static::STYLE_ITALIC => Loc::getMessage('intec.constructor.models.font.style.italic'),
            static::STYLE_OBLIQUE => Loc::getMessage('intec.constructor.models.font.style.oblique')
        ];
    }

    /**
     * Возвращает список значений стилей шрифта.
     * @return array
     */
    public static function getStylesValues()
    {
        $values = static::getStyles();
        return ArrayHelper::getKeys($values);
    }

    /**
     * Формат: TrueType (.ttf)
     */
    const FORMAT_TTF = 'truetype';
    /**
     * Формат: OpenType (.otf)
     */
    const FORMAT_OTF = 'opentype';
    /**
     * Формат: Embedded OpenType (.eot)
     */
    const FORMAT_EOT = 'embedded-opentype';
    /**
     * Формат: Web Open Font (.woff)
     */
    const FORMAT_WOFF = 'woff';
    /**
     * Формат: Web Open Font 2 (.woff2)
     */
    const FORMAT_WOFF2 = 'woff2';
    /**
     * Формат: SVG (.svg)
     */
    const FORMAT_SVG = 'svg';

    /**
     * Возвращает список форматов шрифта.
     * @return array
     */
    public static function getFormats()
    {
        return [
            static::FORMAT_TTF => Loc::getMessage('intec.constructor.models.font.format.ttf'),
            static::FORMAT_OTF => Loc::getMessage('intec.constructor.models.font.format.otf'),
            static::FORMAT_EOT => Loc::getMessage('intec.constructor.models.font.format.eot'),
            static::FORMAT_WOFF => Loc::getMessage('intec.constructor.models.font.format.woff'),
            static::FORMAT_WOFF2 => Loc::getMessage('intec.constructor.models.font.format.woff2'),
            static::FORMAT_SVG => Loc::getMessage('intec.constructor.models.font.format.svg')
        ];
    }

    /**
     * Возвращает список значений форматов шрифта.
     * @return array
     */
    public static function getFormatsValues()
    {
        $values = static::getFormats();
        return ArrayHelper::getKeys($values);
    }

    /**
     * @var array
     */
    protected static $cache = [];
    /**
     * @var array
     */
    protected static $registered = [];
    /**
     * @var string
     */
    protected static $expression = '@font-face.*?\\{.*?font-family:[[:space:]]*(\\\'|\\")?([A-Za-z0-9-_[:space:]]+)(\\\'|\\")?.*?;';
    /**
     * @var array
     */
    protected static $available;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_fonts';
    }

    /**
     * Возвращает доступные шрифты.
     * @param boolean $collection Вернуть в виде коллекции.
     * @param boolean $reset Сбросить кеш.
     * @return ActiveRecords|Font[]
     */
    public static function findAvailable($collection = true, $reset = false)
    {
        if (static::$available === null || $reset) {
            $result = static::find()
                ->where(['active' => 1])
                ->orderBy(['sort' => SORT_ASC])
                ->indexBy('code')
                ->all(null, false);

            static::$available = $result;
        }

        if ($collection)
            return new ActiveRecords(static::$available);

        return static::$available;
    }

    /**
     * @var string
     */
    protected $_externalCode;
    /**
     * @var string
     */
    protected $_style = false;

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        if ($this->type === static::TYPE_EXTERNAL) {
            $link = $this->getLink(true);

            if (!empty($link))
                $link->delete();
        } else if ($this->type === static::TYPE_LOCAL) {
            $files = $this->getFiles(true);

            foreach ($files as $file) {
                if (!empty($file->fileId))
                    CFile::Delete($file->fileId);

                $file->delete();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $types = static::getTypesValues();

        return [
            [['code', 'name'], 'string', 'max' => 255],
            [['active'], 'boolean', 'strict' => false],
            [['sort', 'type'], 'integer'],
            [['code'], 'match', 'pattern' => '/^[A-Za-z0-9_\s-]*$/'],
            [['active'], 'default', 'value' => 1],
            [['sort'], 'default', 'value' => 500],
            [['type'], 'in', 'range' => $types],
            [['code'], 'unique'],
            [['code', 'active', 'name', 'sort', 'type'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => GetMessage('intec.constructor.models.font.attributes.labels.code'),
            'active' => GetMessage('intec.constructor.models.font.attributes.labels.active'),
            'name' => GetMessage('intec.constructor.models.font.attributes.labels.name'),
            'type' => GetMessage('intec.constructor.models.font.attributes.labels.type'),
            'sort' => GetMessage('intec.constructor.models.font.attributes.labels.sort')
        ];
    }

    /**
     * Возвращает код шрифта для стилей.
     * @param boolean $reset Сбросить кеш.
     * @return string
     */
    public function getStyleCode($reset = false)
    {
        if ($this->_externalCode === null || $reset) {
            if ($this->type === static::TYPE_LOCAL) {
                $this->_externalCode = $this->code;
            } else {
                $link = $this->getLink(true);

                if (!empty($link)) {
                    $expression = new RegExp(static::$expression);
                    $expression->isGlobal(false);
                    $expression->isDotAll(true);
                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 10
                        ]
                    ]);

                    $data = @file_get_contents($link->value, false, $context);

                    if (!empty($data)) {
                        $code = $expression->match($data, 2);
                        
                        if (!empty($code))
                            $this->_externalCode = $code;
                    }
                }
            }
        }

        return $this->_externalCode;
    }

    /**
     * Реляция. Возвращает ссылку внешнего шрифта.
     * @param bool $result
     * @return Link|ActiveQuery|null
     */
    public function getLink($result = false)
    {
        return $this->relation(
            'link',
            $this->hasOne(Link::className(), ['fontCode' => 'code']),
            $result
        );
    }

    /**
     * Реляция. Возвращает файлы локального шрифта.
     * @param boolean $result
     * @param boolean $collection
     * @return File[]|ActiveRecords|ActiveQuery|null
     */
    public function getFiles($result = false, $collection = true)
    {
        return $this->relation(
            'files',
            $this->hasMany(File::className(), ['fontCode' => 'code']),
            $result,
            $collection
        );
    }

    /**
     * Возвращает css стиль для подключения шрифта.
     * @param boolean $reset Сбросить кеш.
     * @return string|null
     */
    public function getStyle($reset = true)
    {
        if ($this->_style === false || $reset) {
            $result = '';

            if ($this->type === static::TYPE_LOCAL) {
                $includes = $this->getFiles(true);
                $weights = static::getWeightsValues();
                $styles = static::getStylesValues();
                $formats = static::getFormatsValues();

                $files = [];

                foreach ($includes as $file) {
                    if (!empty($file->fileId))
                        $files[] = $file->fileId;
                }

                if (!empty($files)) {
                    $files = Arrays::fromDBResult(CFile::GetList([], [
                        '@ID' => implode(',', $files)
                    ]))->indexBy('ID');
                } else {
                    $files = new Arrays();
                }

                $files->each(function ($id, &$file) {
                    $file['SRC'] = CFile::GetFileSRC($file);
                });

                foreach ($weights as $weight) {
                    /** @var File[] $weightIncludes */
                    $weightIncludes = [];

                    foreach ($includes as $file)
                        if ($file->weight == $weight)
                            $weightIncludes[] = $file;

                    foreach ($styles as $style) {
                        /** @var File[] $styleIncludes */
                        $styleIncludes = [];

                        foreach ($weightIncludes as $file)
                            if ($file->style == $style)
                                $styleIncludes[$file->format] = $file;

                        $parts = [
                            static::FORMAT_EOT => null,
                            static::FORMAT_WOFF2 => null,
                            static::FORMAT_WOFF => null,
                            static::FORMAT_TTF => null,
                            static::FORMAT_OTF => null,
                            static::FORMAT_SVG => null
                        ];

                        foreach ($formats as $format) {
                            if (empty($styleIncludes[$format]))
                                continue;

                            $file = $styleIncludes[$format];

                            if (!empty($file)) {
                                $file = $files->get($file->fileId);

                                if (!empty($file))
                                    $parts[$format] = $file['SRC'];
                            }

                            unset($file);
                            unset($format);
                        }

                        $partsTemporary = [];

                        foreach ($parts as $format => $file) {
                            if (empty($file))
                                continue;

                            $partsTemporary[$format] = $file;
                        }

                        $parts = $partsTemporary;

                        unset($partsTemporary);

                        if (empty($parts))
                            continue;

                        $result .= '@font-face {';
                        $result .= 'font-family: \'' . $this->code . '\';';
                        $result .= 'font-weight: ' . $weight . ';';
                        $result .= 'font-style: ' . $style . ';';

                        if (!empty($parts[static::FORMAT_EOT]))
                            $result .= 'src: url(\'' . $parts[static::FORMAT_EOT] . '#iefix\');';

                        $first = true;

                        foreach ($parts as $format => $file) {
                            $result .= ($first ? 'src: ' : ', ') . 'url(\'' . $file . ($first ? '#iefix' : null) . '\') format(\'' . $format . '\')';
                            $first = false;
                        }

                        $result .= '; }';

                        unset($styleIncludes);
                        unset($parts);
                    }

                    unset($weightIncludes);
                }
            } else {
                $link = $this->getLink(true);

                if (!empty($link))
                    $result = '@import url(\'' . $link->value . '\');';
            }

            if (empty($result))
                $result = null;

            $this->_style = $result;
        }

        return $this->_style;
    }

    /**
     * Возвращает значение, означающее, подключен ли шрифт.
     * @return boolean
     */
    public function getIsRegistered()
    {
        return !empty(static::$registered[$this->code]) && static::$registered[$this->code];
    }

    /**
     * Возвращает значение, означающее, корректный ли шрифт.
     * @return boolean
     */
    public function getIsValid()
    {
        if (empty($this->code))
            return false;

        if ($this->type === static::TYPE_LOCAL) {
            $files = $this->getFiles(true);

            return !empty($files);
        } else {
            $code = $this->getStyleCode();

            if (empty($code))
                return false;

            return $code === $this->code;
        }
    }

    /**
     * Подключает шрифт на страницу.
     */
    public function register()
    {
        if (!$this->active)
            return;

        if ($this->getIsRegistered())
            return;

        static::$registered[$this->code] = true;

        if ($this->type === static::TYPE_LOCAL) {
            Core::$app->web->css->addString($this->getStyle());
        } else {
            $link = $this->getLink(true);

            if (!empty($link))
                Core::$app->web->css->addFile($link->value);
        }
    }
}