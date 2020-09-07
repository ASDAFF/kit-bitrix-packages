<?
namespace elements\intec\constructor\video;

use intec\core\helpers\ArrayHelper;
use intec\constructor\structure\block\Element as Model;
use intec\core\helpers\Type;

/**
 * Class Element
 * @property string $source
 * @property boolean $allowFullScreen
 * @package elements\intec\constructor\video
 */
class Element extends Model
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getLanguage()->getMessage('name');
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes = ArrayHelper::merge($attributes, [
            'source',
            'allowFullScreen'
        ]);

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function attributeCorrect($attribute, $value, $resolution, $operation)
    {
        if ($attribute == 'source') {
            if ($value !== null)
                $value = Type::toString($value);
        } else if ($attribute == 'allowFullScreen') {
            $value = Type::toBoolean($value);
        }

        return parent::attributeCorrect(
            $attribute,
            $value,
            $resolution,
            $operation
        );
    }
}