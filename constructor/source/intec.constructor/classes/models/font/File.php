<?php
namespace intec\constructor\models\font;

use CFile;
use Bitrix\Main\Localization\Loc;
use intec\constructor\models\Font;
use intec\core\db\ActiveRecord;
use intec\core\helpers\ArrayHelper;

Loc::loadMessages(__FILE__);

/**
 * Class File
 * @property string $fontCode
 * @property integer $weight
 * @property string $style
 * @property string $format
 * @property integer $fileId
 * @package intec\constructor\models\font
 */
class File extends ActiveRecord
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
        return 'constructor_fonts_files';
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        if (!empty($this->fileId))
            CFile::Delete($this->fileId);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $weights = Font::getWeightsValues();
        $styles = Font::getStylesValues();
        $formats = Font::getFormatsValues();

        return [
            [['fontCode'], 'string', 'max' => 255],
            [['weight'], 'integer'],
            [['style', 'format'], 'string'],
            [['fontCode'], 'match', 'pattern' => '/^[A-Za-z0-9_\s-]*$/'],
            [['weight'], 'in', 'range' => $weights],
            [['weight'], 'default', 'value' => Font::WEIGHT_NORMAL],
            [['style'], 'in', 'range' => $styles],
            [['style'], 'default', 'value' => Font::STYLE_NORMAL],
            [['format'], 'in', 'range' => $formats],
            [['fontCode', 'weight', 'style', 'format'], 'unique', 'targetAttribute' => ['fontCode', 'weight', 'style', 'format']],
            [['fontCode', 'weight', 'style', 'format', 'fileId'], 'required']
        ];
    }
}