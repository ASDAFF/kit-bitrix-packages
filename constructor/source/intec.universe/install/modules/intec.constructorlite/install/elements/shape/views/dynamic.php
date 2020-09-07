<div class="ns-intec-constructor block-element block-element-shape" data-bind="{
    css: {
        'block-element-shape-rectangle': properties.type() === 'rectangle',
        'block-element-shape-circle': properties.type() === 'circle'
    },
    style: {
        'background-color': properties.color,
        'border-radius': properties.type() === 'rectangle' ? properties.border.radius() + 'px' : '50%',
        'background-image': properties.image.summary(),
        'background-size': properties.image.contain() ? 'contain' : 'cover'
    }
}">
    <div class="block-element-shape-border" data-bind="{
        'style': {
            'border': properties.border.summary(),
            'border-radius': properties.type() === 'rectangle' ? properties.border.radius() + 'px' : '50%'
        }
    }"></div>
</div>