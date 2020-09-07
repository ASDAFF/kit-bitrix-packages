<?php

use intec\constructor\models\Build;
use intec\constructor\models\build\Template as BuildTemplate;
use intec\constructor\structure\widget\Template as WidgetTemplate;

/**
 * @var array $properties
 * @var Build $build
 * @var BuildTemplate $template
 * @var array
 * @var WidgetTemplate $this
 */
?>
<!-- ko if: text() -->
<div data-bind="{
    html: text,
    style: {
        'font-family': text.font
    }
}"></div>
<!-- /ko -->
<!-- ko if: !text() -->
<div class="constructor-element-stub">
    <div class="constructor-element-stub-wrapper">
        <?= $this->getLanguage()->getMessage('view.message') ?>
    </div>
</div>
<!-- /ko -->