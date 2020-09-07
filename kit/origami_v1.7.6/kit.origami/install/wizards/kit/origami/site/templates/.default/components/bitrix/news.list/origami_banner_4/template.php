<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->createFrame()->begin();

use Kit\Origami\Helper\Config;

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>
<? if ($arResult['MAIN']): ?>
    <div class="puzzle_block main-banner-two">
        <div class="puzzle_block about__puzzle_block main-container">
            <div class="about_block">
                <div class="main_page-small_banner">
                    <? foreach ($arResult['MAIN'] as $item):
                        $img = $item['PREVIEW_PICTURE']['SRC'] ? $item['PREVIEW_PICTURE'] : $item['DETAIL_PICTURE'];
                        $name = $item['~PREVIEW_TEXT'] ? $item['~PREVIEW_TEXT'] : $item['NAME'];
                        if ($lazyLoad) {
                            $strLazyLoad = 'src="' . SITE_TEMPLATE_PATH . '/assets/img/loader_lazy.svg" data-src="' . $img['SRC'] . '" class="lazy"';
                        } else {
                            $strLazyLoad = 'src="' . $img['SRC'] . '"';
                        }
                        ?>
                        <a href="<?= $item['PROPERTIES']['URL']['VALUE'] ?>" title="<?= $name ?>" class="<?=$hoverClass?>">
                            <img <?= $strLazyLoad ?>
                                width="<?= $img['WIDTH'] ?>"
                                height="<?= $img['HEIGHT'] ?>"
                                title="<?= $img['TITLE'] ?>"
                                alt="<?= $img['ALT'] ?>"
                            >
                            <? if ($lazyLoad):?>
                                <span class="loader-lazy loader-lazy--small"></span>
                            <? endif; ?>
                        </a>
                    <? endforeach ?>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>
