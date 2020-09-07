/**
 * @plugin Lazy Load
 * @description loads images on scroll
 * @author apocalypsisdimon@gmail.com
 */
(function ($) {
    var engine;
    var loader;

    engine = {};
    engine.events = [
        'scroll',
        'resize',
        'orientationchange'
    ];

    engine.initialize = function (node, options) {
        this.node = node;
        this.options = {};
        this.images = null;
        this.observer = null;
        this.handler = $.proxy(engine.run, this);
        this.interval = null;
        this.busy = {
            'used': false,
            'delayed': false
        };

        engine.configure.call(this, $.extend({
            'autoRunUse': true,
            'autoRunInterval': 500,
            'busyUse': true,
            'busyTimeout': 500,
            'enableAttributeName': 'data-lazyload-use',
            'sourceAttributeName': 'data-lazyload-src',
            'sourceSetAttributeName': 'data-lazyload-srcset'
        }, options));

        if ($.isFunction(MutationObserver)) {
            node = this.node;

            if (!node)
                node = $('html').get(0);

            if (node) {
                this.observer = new MutationObserver($.proxy(engine.bind, this));
                this.observer.observe(node, {
                    'childList': true,
                    'subtree': true
                });
            }
        }
    };

    engine.bind = function () {
        if (this.node) {
            this.images = $('[' + this.options['enableAttributeName'] + '="true"]', this.node);
        } else {
            this.images = $('[' + this.options['enableAttributeName'] + '="true"]');
        }

        if (this.images.length > 0) {
            $(window).off(engine.events.join(' '), this.handler);
            $(window).on(engine.events.join(' '), this.handler);
        }
    };

    engine.reload = function () {
        engine.bind.call(this);
        engine.run.call(this);
    };

    engine.configure = function (options) {
        this.options = $.extend(this.options, options);

        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }

        if (this.options['autoRunUse'])
            this.interval = setInterval(this.handler, this.options['autoRunInterval']);

        engine.reload.call(this);
    };

    engine.run = function () {
        var context = this;

        if (context.images.length === 0)
            return;

        if (context.options['busyUse'] && context.busy.used) {
            context.busy.delayed = true;
            return;
        }

        context.busy.used = true;

        setTimeout(function () {
            context.images.each(function () {
                var image = this;
                var style = $.isFunction(getComputedStyle) ? getComputedStyle(image) : image.currentStyle;
                var visible = style && style.visibility === 'visible' && (
                    image.offsetWidth ||
                    image.offsetHeight ||
                    image.getClientRects().length
                );

                if (!visible)
                    return;

                var bounds = image.getBoundingClientRect();
                var available = bounds.top <= window.innerHeight && bounds.bottom >= 0;

                if (!available)
                    return;

                var source = image.getAttribute(context.options['sourceAttributeName']);
                var sourceSet = image.getAttribute(context.options['sourceSetAttributeName']);

                if (image.tagName === 'IMG') {
                    if (source)
                        image.src = source;

                    if (sourceSet)
                        image.srcset = sourceSet;
                } else {
                    if (source)
                        image.style.backgroundImage = 'url(\'' + source + '\')';
                }

                image.removeAttribute(context.options['enableAttributeName']);
                image.removeAttribute(context.options['sourceAttributeName']);
                image.removeAttribute(context.options['sourceSetAttributeName']);

                context.images = context.images.filter(function (index, item) {
                    return item !== image;
                });
            });

            if (context.images.length === 0)
                $(window).off(engine.events.join(' '), context.handler);

            context.busy.used = false;

            if (context.busy.delayed) {
                context.busy.delayed = false;
                engine.run.call(context);
            }
        }, context.options['busyTimeout']);
    };

    engine.destroy = function () {
        this.node = null;
        this.options = {};
        this.images = null;

        $(window).off(engine.events.join(' '), this.handler);

        if (this.interval)
            clearInterval(this.interval);

        if (this.observer) {
            this.observer.disconnect();
            this.observer = null;
        }
    };

    loader = function (node, options) {
        var self = this;
        var context = {};

        engine.initialize.call(context, node, options);

        self.run = $.proxy(engine.run, context);
        self.reload = $.proxy(engine.reload, context);
        self.destroy = $.proxy(engine.destroy, context);
    };

    $.lazyLoad = function (options) {
        return new loader(null, options);
    };

    $.fn.extend({
        'lazyLoad': function (options) {
            this.each(function () {
                if (!(this.lazyLoader instanceof loader)) {
                    if (options === undefined || $.isPlainObject(options)) {
                        this.lazyLoader = new loader(this, options);
                    }
                } else {
                    if ($.isPlainObject(options)) {
                        this.lazyLoader.configure(options);
                    } else if (options === 'run') {
                        this.lazyLoader.run();
                    } else if (options === 'reload') {
                        this.lazyLoader.reload();
                    } else if (options === 'destroy') {
                        this.lazyLoader.destroy();
                        this.lazyLoader = undefined;
                    }
                }
            });

            return this;
        }
    });
})(jQuery);