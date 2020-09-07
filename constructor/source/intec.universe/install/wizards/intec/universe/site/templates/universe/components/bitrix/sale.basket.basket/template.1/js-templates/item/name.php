<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

?>
<h2 class="basket-item-name">
    {{#DETAIL_PAGE_URL}}
        <?= Html::tag('a', '{{NAME}}', [
            'class' => 'intec-cl-text-hover',
            'href' => '{{DETAIL_PAGE_URL}}',
            'data-entity' => 'basket-item-name'
        ]) ?>
    {{/DETAIL_PAGE_URL}}
    {{^DETAIL_PAGE_URL}}
        <?= Html::tag('span', '{{NAME}}', [
            'data-entity' => 'basket-item-name'
        ]) ?>
    {{/DETAIL_PAGE_URL}}
</h2>