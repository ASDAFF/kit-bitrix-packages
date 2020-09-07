<?php
namespace widgets\intec\constructor\icons;

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
        $directory = $build->getDirectory(false, true, '/');
        $data['header'] = [
            'value' => ArrayHelper::getValue($properties, ['header', 'value']),
            'show' => ArrayHelper::getValue($properties, ['header', 'show']) == true
        ];

        $data['header']['show'] =
            $data['header']['show'] &&
            !empty($data['header']['value']);

        foreach (['caption', 'description'] as $key) {
            $array = [];
            $array['style'] = [
                'bold' => ArrayHelper::getValue($properties, [$key, 'style', 'bold']) == true,
                'italic' => ArrayHelper::getValue($properties, [$key, 'style', 'italic']) == true,
                'underline' => ArrayHelper::getValue($properties, [$key, 'style', 'underline']) == true
            ];
            $array['text'] = [];
            $array['text']['align'] = ArrayHelper::fromRange(['left', 'center', 'right'], ArrayHelper::getValue($properties, [$key, 'text', 'align', 'value']));
            $array['text']['size'] = null;
            $array['text']['color'] = null;
            $array['opacity'] = 0;

            $size = [
                'value' => ArrayHelper::getValue($properties, [$key, 'text', 'size', 'value']),
                'measure' => ArrayHelper::getValue($properties, [$key, 'text', 'size', 'measure'])
            ];
            $color = ArrayHelper::getValue($properties, [$key, 'text', 'color']);
            $opacity = ArrayHelper::getValue($properties, [$key, 'opacity']);

            if (Type::isNumeric($size['value']))
                $array['text']['size'] = $size['value'].ArrayHelper::fromRange(['px', '%', 'em', 'pt'], $size['measure']);

            if (!empty($color))
                $array['text']['color'] = $color;

            if (Type::isNumeric($opacity))
                $array['opacity'] = (100 - $opacity) / 100;

            $data[$key] = $array;
        }

        $data['background'] = [];
        $data['background']['show'] = ArrayHelper::getValue($properties, ['background', 'show']) == true;
        $data['background']['color'] = null;
        $data['background']['rounding'] = null;
        $data['background']['opacity'] = null;

        $color = ArrayHelper::getValue($properties, ['background', 'color']);
        $rounding = ArrayHelper::getValue($properties, ['background', 'rounding']);
        $opacity = ArrayHelper::getValue($properties, ['background', 'opacity']);

        if (!empty($color))
            $data['background']['color'] = $color;

        if (!empty($rounding)) {
            $data['background']['rounding'] = [];
            $default = ArrayHelper::getValue($rounding, 'value');

            if (Type::isNumeric($default)) {
                $default = $default.ArrayHelper::fromRange(
                        ['px', '%'],
                        ArrayHelper::getValue(
                            $rounding,
                            'measure'
                        )
                    );
            } else {
                $default = 0;
            }

            foreach (['top', 'right', 'bottom', 'left'] as $side) {
                $value = ArrayHelper::getValue($rounding, [$side, 'value']);

                if (Type::isNumeric($value)) {
                    $value = $value.ArrayHelper::fromRange(
                            ['px', '%'],
                            ArrayHelper::getValue(
                                $rounding,
                                [$side, 'measure']
                            )
                        );
                } else {
                    $value = $default;
                }

                $data['background']['rounding'][$side] = $value;
            }
        }

        if (Type::isNumeric($opacity))
            $data['background']['opacity'] = (100 - $opacity) / 100;

        $data['count'] = null;
        $data['items'] = [];
        $count = ArrayHelper::getValue($properties, 'count');
        $items = ArrayHelper::getValue($properties, 'items');

        if (Type::isNumeric($count))
            if ($count > 0)
                $data['count'] = $count;

        if (Type::isArray($items))
            foreach ($items as $item) {
                $array = [];
                $array['name'] = ArrayHelper::getValue($item, 'name');
                $array['description'] = ArrayHelper::getValue($item, 'description');
                $array['link'] = null;
                $array['image'] = ArrayHelper::getValue($item, 'image');

                $link = ArrayHelper::getValue($item, 'link');

                if (!empty($link))
                    $array['link'] = $link;

                if (!empty($array['image']))
                    $array['image'] = StringHelper::replaceMacros($array['image'], [
                        'TEMPLATE' => $directory
                    ]);

                $data['items'][] = $array;
            }

        return $data;
    }
}