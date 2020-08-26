<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if ($arResult['ITEMS']) {
    ?>
    <div class="sotbit-seometa-tags-column">
        <div class="sotbit-seometa-tags-column-container">
            <div class="sotbit-seometa-tags-column__title"><?= GetMessage('OFTEN_SEARCH'); ?></div>

            <div class="tags_wrapper">
                <div class="tags_section">
                    <?
                    foreach ($arResult['ITEMS'] as $Item) {
                        ?>
                        <div class="sotbit-seometa-tags-column-wrapper">
                            <?
                            if ($Item['TITLE'] && $Item['URL']) {
                                ?>
                                <div class="sotbit-seometa-tag-column">
                                    <a class="sotbit-seometa-tag-link" href="<?= $Item['URL'] ?>"
                                       title="<?= $Item['TITLE'] ?>"><?= $Item['TITLE'] ?></a>
                                </div>
                                <?
                            }
                            ?>
                        </div>
                        <?
                    } ?>
                </div>
            </div>
        </div>
        <div>
            <div class="sotbit-seometa-tags__hide">
                <div class="seometa-tags__hide"><?= GetMessage('POPULAR_HIDE'); ?>
                    <i class="angle-up" aria-hidden="true"></i>
                </div>
                <div class="seometa-tags__show"><?= GetMessage('POPULAR_SHOW'); ?>
                    <i class="angle-up" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
    <?
}

?>
