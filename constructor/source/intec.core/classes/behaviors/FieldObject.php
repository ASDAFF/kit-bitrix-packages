<?php
namespace intec\core\behaviors;

use intec\core\base\Behavior;
use intec\core\base\Event;
use intec\core\base\InvalidParamException;
use intec\core\db\ActiveRecord;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\Json;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

class FieldObject extends Behavior
{
    public $attribute;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'decompile',
            ActiveRecord::EVENT_AFTER_REFRESH => 'decompile',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'compile',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'decompile',
            ActiveRecord::EVENT_BEFORE_INSERT => 'compile',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'compile',
            ActiveRecord::EVENT_AFTER_INSERT => 'decompile',
            ActiveRecord::EVENT_AFTER_UPDATE => 'decompile'
        ];
    }

    /**
     * @param Event $event
     */
    public function compile($event)
    {
        /** @var ActiveRecord $record */
        $record = $this->owner;

        if (empty($this->attribute) || !$record->hasAttribute($this->attribute))
            return;

        $value = $record->getAttribute($this->attribute);

        if ($value !== null)
            $value = Json::encode($value, 320, true);

        $record->setAttribute($this->attribute, $value);
    }

    /**
     * @param Event $event
     */
    public function decompile($event)
    {
        /** @var ActiveRecord $record */
        $record = $this->owner;

        if (empty($this->attribute) || !$record->hasAttribute($this->attribute))
            return;

        $value = $record->getAttribute($this->attribute);

        if ($value !== null)
            try {
                $value = Json::decode($value, true, true);
            } catch (InvalidParamException $exception) {
                $value = null;
            }

        $record->setAttribute($this->attribute, $value);
    }
}