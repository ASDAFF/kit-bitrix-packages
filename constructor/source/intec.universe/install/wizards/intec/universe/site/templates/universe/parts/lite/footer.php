<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;
use intec\template\Properties;

/**
 * @var string $directory
 */

(function ($directory) use (&$properties) { ?>
<?php
    global $APPLICATION;

    $properties = Properties::getCollection();
    $blocks = $properties->get('footer-blocks');
    $blocks = Arrays::from($blocks);

    $page['path'] = $APPLICATION->GetCurPage(false);
    $page['main'] = $page['path'] === SITE_DIR;
    $page['background'] = $properties->get('template-background-show');
    $page['blocks'] = [
        'menu' => $properties->get('template-menu-show')
    ];

    if (FileHelper::isFile($directory.'/parts/custom/footer.php'))
        include($directory.'/parts/custom/footer.php');
?>
            <?php if ($page['blocks']['menu']) { ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
            <?php } ?>
        <?= Html::endTag('div') ?>
        </div>
    </div>
    <div class="intec-template-footer">
        <?php $blocks->each(function ($code, $block) use (&$APPLICATION) { ?>
        <?php if (!$block['active']) return ?>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:main.include',
                '.default',
                [
                    'AREA_FILE_SHOW' => 'file',
                    'PATH' => SITE_DIR.'include/footer/blocks/'.$code.'/'.$block['template'].'.php'
                ],
                false,
                ['HIDE_ICONS' => 'Y']
            ) ?>
        <?php }) ?>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '.default',
            [
                'AREA_FILE_SHOW' => 'file',
                'PATH' => SITE_DIR.'include/footer/base.php'
            ],
            false,
            ['HIDE_ICONS' => 'Y']
        ) ?>
    </div>
</div>
<?php })($directory);