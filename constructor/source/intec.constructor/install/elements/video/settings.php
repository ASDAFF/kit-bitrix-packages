<?php

use elements\intec\constructor\text\Element;

/**
 * @var Element $this
 */

$language = $this->getLanguage();

?>
<div class="constructor-menu-section constructor-m-b-20">
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.source') ?>
            </div>
            <div class="constructor-menu-field-content">
                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                    value: properties.source
                }" />
            </div>
        </div>
        <div class="constructor-menu-section-field">
            <div class="constructor-grid constructor-grid-i-4 constructor-grid-a-v-center constructor-p-t-10">
                <div class="constructor-grid-item-auto">
                    <input type="checkbox" data-bind="{
                        bind: ko.models.switch(),
                        checked: properties.allowFullScreen
                    }" />
                </div>
                <div class="constructor-grid-item constructor-menu-text">
                    <?= $language->getMessage('settings.allowFullScreen') ?>
                </div>
            </div>
        </div>
    </div>
</div>