<div class="ns-intec-constructor block-element block-element-button" data-bind="{
    style: {
        'border-radius': properties.border.radius() + 'px'
    }
}">
    <div class="block-element-button-background" data-bind="{
        style: {
            'background-color': properties.background.color.default,
            'opacity': properties.background.opacity.summary
        }
    }"></div>
    <div class="block-element-button-border" data-bind="{
        style: {
            'border': properties.border.summary,
            'border-radius': properties.border.radius() + 'px'
        }
    }"></div>
    <div class="block-element-button-wrapper">
        <span class="block-element-button-aligner"></span>
        <span class="block-element-button-text" data-bind="{
            text: properties.text,
            style: {
                'color': properties.text.color.default,
                'font-family': properties.text.font,
                'font-size': properties.text.size.summary,
                'letter-spacing': properties.text.letterSpacing.summary,
                'line-height': properties.text.lineHeight.summary,
                'font-weight': properties.text.style() === 'bold' ? 'bold' : null,
                'font-style': properties.text.style() === 'italic' ? 'italic' : null
            }
        }"></span>
    </div>
</div>