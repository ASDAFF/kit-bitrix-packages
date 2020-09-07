(function ($, api) {
    $(document).on('ready', function () {
        var adapt;
        var area;
        var container;
        var template;
        var height;

        area = $(window);
        container = $('body');
        template = container.find('.intec-template');
        template.content = template.find('.intec-template-content');
        template.footer = template.find('.intec-template-footer');

        adapt = function () {
            container.css({'height': 'auto'});
            template.footer.css({'position': ''});
            template.content.css({'padding-bottom': ''});

            if (container.height() < area.height()) {
                container.css({'height': '100%'});

                if (template.footer.size() === 1) {
                    template.footer.css({'position': 'absolute'});
                    template.content.css({'padding-bottom': template.footer.height + 'px'});
                }
            }
        };

        $(window).on('resize', adapt);
        setInterval(adapt, 250);
        adapt();
    });

    $(document).on('ready', function () {
        if (api.isFunction($.fn.stellar)) {
            $(window).stellar({
                'horizontalScrolling': false,
                'verticalScrolling': true,
                'horizontalOffset': 0,
                'verticalOffset': 0,
                'responsive': true,
                'scrollProperty': 'scroll',
                'positionProperty': 'position',
                'parallaxBackgrounds': true,
                'parallaxElements': false,
                'hideDistantElements': false
            });
        }
    });

    $(document).on('ready', function () {
        $('body').lazyLoad({
            'autoRunUse': true,
            'autoRunInterval': 500,
            'busyUse': true,
            'busyTimeout': 500,
            'enableAttributeName': 'data-lazyload-use',
            'sourceAttributeName': 'data-original',
            'sourceSetAttributeName': 'data-original-set'
        });
    });
})(jQuery, intec);