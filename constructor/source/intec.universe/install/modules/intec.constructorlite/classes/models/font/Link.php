<?php
namespace intec\constructor\models\font;

use Bitrix\Main\Localization\Loc;
use intec\core\db\ActiveRecord;

Loc::loadMessages(__FILE__);

/**
 * Class Link
 * @property string $fontCode
 * @property string $value
 * @package intec\constructor\models\font
 */
class Link extends ActiveRecord
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
        return 'constructor_fonts_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fontCode'], 'string', 'max' => 255],
            [['value'], 'string'],
            [['fontCode'], 'match', 'pattern' => '/^[A-Za-z0-9_\s-]*$/'],
            [['fontCode'], 'unique'],
            [['fontCode', 'value'], 'required']
        ];
    }
}