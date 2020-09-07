<?php
namespace intec\constructor\models\build\template;
IncludeModuleLangFile(__FILE__);

use intec\core\db\ActiveQuery;
use intec\core\helpers\ArrayHelper;
use intec\constructor\models\build\property\Value as PropertyValue;
use intec\constructor\models\build\Template;

/**
 * Class TemplateValue
 * @property int $templateId
 * @package intec\constructor\models\build\property
 */
class Value extends PropertyValue
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_templates_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['templateId'] = ['templateId', 'integer'];
        $rules['unique'][0][] = 'templateId';
        $rules['unique']['targetAttribute'][] = 'templateId';
        $rules['required'][0][] = 'templateId';

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['templateId'] =  GetMessage('intec.constructor.models.build.template.value.attributes.labels.templateId');

        return $labels;
    }

    /**
     * Реляция. Возвращает привязанный шаблон к значению.
     * @param bool $result
     * @return Template|ActiveQuery|null
     */
    public function getTemplate($result = false)
    {
        return $this->relation(
            'template',
            $this->hasOne(Template::className(), [
                'id' => 'templateId',
                'buildId' => 'buildId'
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
        unset($result['templateId']);
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
        unset($data['templateId']);
        unset($data['code']);

        foreach ($this->attributes() as $attribute) {
            if ($attribute === 'buildId' || $attribute === 'templateId')
                continue;

            $this->setAttribute($attribute, null);
        }

        $this->load($data, '');

        return $this->save();
    }
}