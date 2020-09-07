<?php
namespace widgets\intec\constructor\logotype;

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

class Widget extends \intec\constructor\structure\Widget
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
    protected function data($properties, $build, $template)
    {
        $data = [];
        $data['type'] = ArrayHelper::getValue($properties, 'type');
        $data['type'] = ArrayHelper::fromRange(['image', 'text'], $data['type']);

        $image = null;

        if ($data['type'] == 'image') {
            $image = ArrayHelper::getValue($properties, 'image');

            if (!Type::isArray($image))
                $image = [];

            foreach (['width', 'height'] as $dimension) {
                if (empty($data['image'][$dimension]['value'])) {
                    $data['image'][$dimension] = '100%';
                } else {
                    $data['image'][$dimension] = $data['image'][$dimension]['value'].$data['image'][$dimension]['measure'];
                }
            }

            if (!empty($data['image']['url'])) {
                $data['image']['url'] = StringHelper::replaceMacros($data['image']['url'], [
                    'TEMPLATE' => $build->getDirectory(false, true, '/')
                ]);
            }

            $image['proportions'] = Type::toBoolean($image['proportions']);
        }

        $data['image'] = ArrayHelper::merge([
            'url' => null,
            'width' => [
                'value' => null,
                'measure' => 'px'
            ],
            'height' => [
                'value' => null,
                'measure' => 'px'
            ],
            'proportions' => false
        ], $image);

        $data['text'] = [
            'font' => ArrayHelper::getValue($properties, 'textFont'),
            'value' => ArrayHelper::getValue($properties, 'text')
        ];

        return $data;
    }
}