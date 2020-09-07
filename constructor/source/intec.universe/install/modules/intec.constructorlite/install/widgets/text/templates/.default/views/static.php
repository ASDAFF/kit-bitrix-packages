<?php

use intec\constructor\models\Font;
use intec\constructor\structure\widget\Template;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $properties
 * @var array $data
 * @var Template $this
 */

$textFont = ArrayHelper::getValue($properties, 'textFont');

if (!empty($textFont)) {
    $textFont = Font::findOne($textFont);

    if (!empty($textFont))
        $textFont->register();
}

?>
<?= Html::beginTag('div', [
    'style' => [
        'font-family' => !empty($textFont) ? '\''.$textFont->code.'\', sans-serif' : null
    ]
]) ?>
    <?= $properties['text'] ?>
<?= Html::endTag('div') ?>