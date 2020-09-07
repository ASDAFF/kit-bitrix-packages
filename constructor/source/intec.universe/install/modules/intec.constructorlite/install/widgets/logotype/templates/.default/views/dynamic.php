<div class="widget widget-logotype widget-default">
    <!-- ko if: type.value() === type.values()[0] -->
        <!-- ko if: image.calculated -->
            <div class="widget-logotype-image" data-bind="{
                style: {
                    backgroundImage: image.calculated,
                    backgroundSize: image.proportions() ? 'contain' : '100% 100%',
                    width: image.width.summary() ? image.width.summary() : '100%',
                    height: image.height.summary() ? image.height.summary() : '100%'
                }
            }"></div>
        <!-- /ko -->
        <!-- ko if: !image.calculated() -->
            <div class="constructor-element-stub">
                <div class="constructor-element-stub-wrapper">
                    <?= $this->getLanguage()->getMessage('view.message') ?>
                </div>
            </div>
        <!-- /ko -->
    <!-- /ko -->
    <!-- ko if: type.value() === type.values()[1] -->
        <!-- ko if: text -->
            <div class="widget-logotype-text" data-bind="{
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
    <!-- /ko -->
    <div class="widget-logotype-aligner"></div>
</div>