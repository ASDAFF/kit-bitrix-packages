<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arData
 */

?>
<div class="intec-grid-item-auto intec-grid-item-768-1">
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-article',
        'data' => [
            'role' => 'article',
            'show' => !empty($arData['ARTICLE']['VALUE']) ? 'true' : 'false'
        ]
    ]) ?>
        <?= Html::tag('span', $arData['ARTICLE']['NAME'], [
            'class' => 'catalog-element-article-name'
        ]) ?>
        <?= Html::tag('span', $arData['ARTICLE']['VALUE'], [
            'class' => 'catalog-element-article-value',
            'data-role' => 'article.value'
        ]) ?>
    <?= Html::endTag('div') ?>
</div>