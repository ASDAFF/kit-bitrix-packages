<?php

use intec\core\helpers\Html;
use elements\intec\constructor\video\Element;
use intec\constructor\structure\Block;

/**
 * @var Block $block
 * @var Element $this
 */

?>
<div class="ns-intec-constructor block-element block-element-video">
    <?= Html::tag('iframe', null, [
        'class' => 'block-element-video-frame',
        'src' => $this->source,
        'allowfullscreen' => $this->allowFullScreen ? 'allowfullscreen' : null
    ]) ?>
</div>