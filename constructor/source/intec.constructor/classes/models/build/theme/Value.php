<?php
namespace intec\constructor\models\build\theme;
IncludeModuleLangFile(__FILE__);

use intec\core\db\ActiveQuery;
use intec\core\helpers\ArrayHelper;
use intec\constructor\models\build\property\Value as PropertyValue;
use intec\constructor\models\build\Theme;

/**
 * Class ThemeValue
 * @property string $themeCode
 * @package intec\constructor\models\build
 */
class Value extends PropertyValue
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_themes_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['themeCode'] = ['themeCode', 'string', 'max' => 128];
        $rules['unique'][0][] = 'themeCode';
        $rules['unique']['targetAttribute'][] = 'themeCode';
        $rules['required'][0][] = 'themeCode';

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['themeCode'] =  GetMessage('intec.constructor.models.build.theme-value.attributes.labels.themeCode');

        return $labels;
    }

    /**
     * Реляция. Возвращает привязанную тему к значению.
     * @param bool $result
     * @return Theme|ActiveQuery|null
     */
    public function getTheme($result = false)
    {
        return $this->relation(
            'theme',
            $this->hasOne(Theme::className(), [
                'buildId' => 'buildId',
                'code' => 'themeCode'
            ]),
            $result
        );
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        $result = $this->toArray();
        $result['code'] = $result['propertyCode'];

        unset($result['buildId']);
        unset($result['themeCode']);
        unset($result['propertyCode']);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function import($data)
    {
        $data['propertyCode'] = ArrayHelper::getValue($data, 'code');

        unset($data['buildId']);
        unset($data['themeCode']);
        unset($data['code']);

        foreach ($this->attributes() as $attribute) {
            if ($attribute === 'buildId' || $attribute === 'themeCode')
                continue;

            $this->setAttribute($attribute, null);
        }

        $this->load($data, '');

        return $this->save();
    }
}