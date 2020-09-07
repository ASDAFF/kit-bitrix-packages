<?php

use Bitrix\Main\Localization\Loc;

?>
<div class="constructor-dialog constructor-dialog-gallery" data-bind="{
    bind: dialogs.list.gallery,
    with: dialogs.list
}">
    <div class="constructor-dialog-header" data-bind="{
        with: gallery.data
    }">
        <div class="constructor-dialog-header-wrapper">
            <div class="constructor-dialog-title">
                <?= Loc::getMessage('container.modals.gallery.title') ?>
            </div>
            <div class="constructor-dialog-content">
                <div class="constructor-dialog-search">
                    <div class="constructor-icon-loop"></div>
                    <input
                        type="text"
                        class="constructor-dialog-search-input"
                        placeholder="<?= Loc::getMessage('container.modals.gallery.search') ?>"
                        data-bind="{
                            value: filter,
                            valueUpdate: 'keyup'
                        }"
                    />
                </div>
            </div>
            <div class="constructor-dialog-buttons">
                <button class="constructor-dialog-button constructor-icon-window" data-bind="{
                    click: $parent.gallery.expanded.switch
                }"></button>
                <button class="constructor-dialog-button constructor-icon-cancel" data-bind="{
                    click: $parent.gallery.close
                }"></button>
            </div>
        </div>
    </div>
    <div class="constructor-dialog-body" data-bind="{
        with: gallery.data
    }">
        <div class="constructor-dialog-gallery-images">
            <div class="constructor-dialog-loader" data-bind="{
                visible: updating
            }">
                <div class="constructor-loader constructor-loader-1"></div>
            </div>
            <div class="constructor-dialog-gallery-images-wrapper nano" data-bind="{
                bind: $parent.gallery.scroll,
                visible: !updating()
            }">
                <div class="constructor-dialog-gallery-images-wrapper-2 nano-content">
                    <div class="constructor-dialog-gallery-images-wrapper-3" data-bind="{
                        foreach: images
                    }">
                        <!-- ko if: $root.gallery.isImage($data) -->
                            <div class="constructor-dialog-gallery-image" data-bind="{
                                click: function () {
                                    $parents[1].gallery.select($data);
                                }
                            }">
                                <div class="constructor-dialog-gallery-image-wrapper">
                                    <div class="constructor-dialog-gallery-image-wrapper-2">
                                        <div class="constructor-dialog-gallery-image-aligner"></div>
                                        <img class="constructor-dialog-gallery-image-view" data-bind="{
                                            attr: {
                                                'alt': name,
                                                'src': path
                                            }
                                        }" />
                                        <div class="constructor-dialog-gallery-content constructor-vertical-middle">
                                            <div class="constructor-aligner"></div>
                                            <div class="constructor-dialog-gallery-name" data-bind="{
                                                text: name
                                            }"></div>
                                        </div>
                                        <div class="constructor-dialog-gallery-delete" data-bind="{
                                            click: $data.delete,
                                            clickBubble: false
                                        }">
                                            <i class="constructor-icon-cancel"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- /ko -->
                        <!-- ko if: !$root.gallery.isImage($data) -->
                            <div class="constructor-dialog-gallery-image">
                                <div class="constructor-dialog-gallery-image-wrapper">
                                    <div class="constructor-dialog-gallery-image-wrapper-2">
                                        <div class="constructor-loader constructor-loader-1"></div>
                                    </div>
                                </div>
                            </div>
                        <!-- /ko -->
                    </div>
                </div>
            </div>
        </div>
        <div class="constructor-dialog-gallery-upload no-select" data-bind="{
            bind: $parent.gallery.uploader.zone,
            css: {
                'constructor-dialog-gallery-upload-active': $parent.gallery.uploader.active
            }
        }">
            <div class="constructor-dialog-gallery-icon constructor-icon constructor-icon-picture"></div>
            <div class="constructor-dialog-gallery-title"><?= Loc::getMessage('container.modals.gallery.load') ?></div>
            <div class="constructor-dialog-gallery-text"><?= Loc::getMessage('container.modals.gallery.drop.image') ?></div>
        </div>
        <input type="file" accept="image/*" style="display: none" data-bind="{
            bind: $parent.gallery.uploader.node
        }" />
    </div>
</div>