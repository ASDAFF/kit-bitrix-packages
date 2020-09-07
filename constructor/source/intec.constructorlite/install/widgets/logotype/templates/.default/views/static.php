<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Font;
use intec\constructor\models\build\Template as SiteTemplate;
use intec\constructor\structure\widget\Template as WidgetTemplate;

/**
 * @var array $properties
 * @var array $data
 * @var SiteTemplate $siteTemplate
 * @var WidgetTemplate $this
 */

$type = $data['type'];
$image = $data['image'];
$text = $data['text'];
$textFont = $data['textFont'];

if ($type != 'image' && !empty($textFont)) {
    $textFont = Font::findOne($textFont);

    if (!empty($textFont))
        $textFont->register();
}

?>
<div class="widget widget-logotype widget-default">
    <?php if ($type == 'image' && !empty($image['url'])) {
        $style = [];
        $style['background-image'] = 'url(\''.$image['url'].'\')';
        $style['background-size'] = $image['proportions'] ? 'contain' : '100% 100%';

        if (!empty($image['width']))
            $style['width'] = $image['width'];

        if (!empty($image['height']))
            $style['height'] = $image['height'];

        echo Html::tag('div', null, [
            'class' => 'widget-logotype-image',
            'style' => Html::cssStyleFromArray($style)
        ]);
    } else {
        echo Html::tag('div', $text, [
            'class' => 'widget-logotype-text',
            'style' => [
                'font-family' => !empty($textFont) ? '\''.$textFont->code.'\', sans-serif' : null
            ]
        ]);
    } ?>
    <div class="widget-logotype-aligner"></div>
</div>