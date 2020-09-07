<?php
use intec\constructor\models\Build;
use intec\constructor\models\build\Template as BuildTemplate;
use intec\constructor\structure\widget\Template as WidgetTemplate;

/**
 * @var array $properties
 * @var Build $build
 * @var BuildTemplate $template
 * @var array $data
 * @var WidgetTemplate $this
 */
?>
<div class="intec-content">
    <div class="intec-content-wrapper">
        <h1 class="intec-header"><?= $this->getLanguage()->getMessage('view.message') ?></h1>
    </div>
</div>