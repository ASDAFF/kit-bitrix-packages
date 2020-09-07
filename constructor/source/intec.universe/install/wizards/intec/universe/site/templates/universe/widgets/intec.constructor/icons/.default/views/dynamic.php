<!-- ko if: items().length > 0 -->
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="widget widget-icons widget-default">
                <div class="widget-icons-header" data-bind="{
                    visible: header.show,
                    html: header.value
                }"></div>
                <div class="widget-icons-items">
                    <div class="widget-icons-items-wrapper" data-bind="{ foreach: items }">
                        <div class="widget-icons-item" data-bind="{
                            style: {
                                width: $parent.count() > 0 ? (100 / $parent.count()) + '%' : null
                            }
                        }">
                            <div class="widget-icons-item-wrapper">
                                <div class="widget-icons-background">
                                    <div class="widget-icons-background-wrapper">
                                        <div class="widget-icons-background-brush" data-bind="{
                                            visible: $parent.background.show,
                                            style: {
                                                background: $parent.background.color,
                                                borderRadius: $parent.background.rounding.calculated,
                                                opacity: $parent.background.opacity.calculated
                                            }
                                        }"></div>
                                        <div class="widget-icons-background-icon" data-bind="{
                                            style: {
                                                backgroundImage: image.calculated
                                            }
                                        }"></div>
                                    </div>
                                </div>
                                <div class="widget-icons-caption" data-bind="{
                                    text: name,
                                    style: {
                                        fontWeight: $parent.caption.style.bold() ? 'bold' : null,
                                        fontStyle: $parent.caption.style.italic() ? 'italic' : null,
                                        textDecoration: $parent.caption.style.underline() ? 'underline' : null,
                                        color: $parent.caption.text.color,
                                        fontSize: $parent.caption.text.size.summary,
                                        textAlign: $parent.caption.text.align.value,
                                        opacity: $parent.caption.opacity.calculated
                                    }
                                }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /ko -->
<!-- ko if: items().length === 0 -->
    <div class="constructor-element-stub">
        <div class="constructor-element-stub-wrapper">
            <?= $this->getLanguage()->getMessage('view.message') ?>
        </div>
    </div>
<!-- /ko -->