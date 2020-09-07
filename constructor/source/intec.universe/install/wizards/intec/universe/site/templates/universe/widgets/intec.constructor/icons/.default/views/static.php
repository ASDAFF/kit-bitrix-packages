<?php
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template as SiteTemplate;
use intec\constructor\structure\widget\Template as WidgetTemplate;

/**
 * @var array $properties
 * @var array $data
 * @var Build $build
 * @var SiteTemplate $template
 * @var WidgetTemplate $this
 */

$header = $data['header'];
$caption = $data['caption'];
$background = $data['background'];
$items = $data['items'];
$width = null;

if ($data['count'] !== null)
    $width = 100 / $data['count'];
?>
<div class="intec-content">
    <div class="intec-content-wrapper">
        <div class="widget widget-icons widget-default">
            <?php if ($header['show']) { ?>
                <div class="widget-icons-header">
                    <?= Html::encode($header['value']) ?>
                </div>
            <?php } ?>
            <div class="widget-icons-items">
                <div class="widget-icons-items-wrapper">
                    <?php foreach ($items as $item) { ?>
                        <?= Html::beginTag($item['link'] !== null ? 'a' : 'div', [
                            'class' => 'widget-icons-item',
                            'style' => [
                                'width' => $width !== null ? $width.'%' : null
                            ],
                            'href' => $item['link'] !== null ? $item['link'] : null
                        ]) ?>
                            <div class="widget-icons-item-wrapper">
                                <div class="widget-icons-background">
                                    <div class="widget-icons-background-wrapper">
                                        <?php if ($background['show']) { ?>
                                            <?= Html::beginTag('div', [
                                                'class' => 'widget-icons-background-brush',
                                                'style' => [
                                                    'background' => $background['color'],
                                                    'border-radius' => $background['rounding'] !== null ? implode(' ', $background['rounding']) : null,
                                                    'opacity' => $background['opacity']
                                                ]
                                            ]) ?>
                                            <?= Html::endTag('div') ?>
                                        <?php } ?>
                                        <?= Html::beginTag('div', [
                                            'class' => 'widget-icons-background-icon',
                                            'style' => [
                                                'background-image' => $item['image'] !== null ? 'url(\''.$item['image'].'\')' : null
                                            ]
                                        ]) ?>
                                        <?= Html::endTag('div') ?>
                                    </div>
                                </div>
                                <?= Html::beginTag('div', [
                                    'class' => 'widget-icons-caption',
                                    'style' => [
                                        'font-weight' => $caption['style']['bold'] ? 'bold' : null,
                                        'font-style' => $caption['style']['italic'] ? 'italic' : null,
                                        'text-decoration' => $caption['style']['underline'] ? 'underline' : null,
                                        'color' => $caption['text']['color'],
                                        'font-size' => $caption['text']['size'],
                                        'text-align' => $caption['text']['align'],
                                        'opacity' => $caption['opacity']
                                    ]
                                ]) ?>
                                    <?= Html::encode($item['name']) ?>
                                <?= Html::endTag('div') ?>
                            </div>
                        <?= Html::endTag($item['link'] !== null ? 'a' : 'div') ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>