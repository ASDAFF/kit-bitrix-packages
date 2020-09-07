<!-- ko if: node.draggable -->
    <div class="ns-intec-constructor block-element block-element-text" data-bind="{
        html: properties.text,
        style: {
            'text-align': attribute('textAlign'),
            'color': attribute('textColor'),
            'font-size': attribute('textSize').summary,
            'font-family': properties.text.font,
            'line-height': attribute('textLineHeight').summary,
            'letter-spacing': attribute('textLetterSpacing').summary
        }
    }"></div>
<!-- /ko -->
<!-- ko if: !node.draggable() -->
    <div class="ns-intec-constructor block-element block-element-text" contenteditable="true" data-bind="{
        bind: ko.models.ckeditor({
            'allowedContent': true,
            'removeButtons': 'Font',
            'inline': true,
            'startupFocus': true
        }, properties.text),
        html: properties.text,
        style: {
            'text-align': attribute('textAlign'),
            'color': attribute('textColor'),
            'font-size': attribute('textSize').summary,
            'font-family': properties.text.font,
            'line-height': attribute('textLineHeight').summary,
            'letter-spacing': attribute('textLetterSpacing').summary
        }
    }"></div>
<!-- /ko -->