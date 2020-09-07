/**
 * Navigation
 * @since 2.0.0
 */
;(function ($, window, document, undefined) {
    var plugin;

    plugin = function (core) {
        this._core = core;

        /**
         * Статус инициализации плагина.
         * @protected
         * @type {Boolean}
         */
        this._initialized = false;

        /**
         * Главный элемент.
         * @type {jQuery}
         */
        this.$element = this._core.$element;

        /**
         * Элемент со слайдами.
         * @type {jQuery}
         */
        this.$stage = null;

        /**
         * Обертка элемента со слайдами.
         * @type {jQuery}
         */
        this.$overlay = null;

        /**
         * Обработчики событий элемента со слайдами.
         * @type {jQuery}
         */
        this._overlayHandlers = {
            'mousemove': $.proxy(function (e) {
                if (this._initialized) {
                    var settings = this._core.settings;
                    var node = this.$overlay;

                    if (settings['overlayNav']) {
                        var pages = 0;
                        var side = 0;
                        var position = 0;

                        if (settings['overlayNavByPages'] && this._core._plugins.navigation) {
                            pages = Math.ceil(this._core.items().length / settings.items);
                        } else {
                            pages = this._core.items().length - settings.items + 1;
                        }

                        if (pages < 0)
                            pages = 1;

                        if (settings['overlayNavOrientation'] === 'horizontal') {
                            side = node.outerWidth(false);
                            position = e.pageX - node.offset().left;
                        } else {
                            side = node.outerHeight(false);
                            position = e.pageY - node.offset().top;
                        }

                        var slide = Math.ceil(position / (side / pages));

                        if (slide < 1)
                            slide = 1;

                        if (slide > pages)
                            slide = pages;

                        if (this._core._plugins.navigation) {
                            this._core.to(slide - 1, null, !settings['overlayNavByPages']);
                        } else {
                            this._core.to(slide - 1);
                        }
                    }
                }
            }, this)
        };

        /**
         * Обработчики событий плагина.
         * @protected
         * @type {Object}
         */
        this._handlers = {
            'initialized.owl.carousel': $.proxy(function (e) {
                if (!this._initialized) {
                    this.initialize();
                    this._initialized = true;
                }
            }, this)
        };

        // Настройки по умолчанию
        this._core.options = $.extend({}, plugin.Defaults, this._core.options);

        this.$element.on(this._handlers);
    };

    plugin.Defaults = {
        'overlayNav': false, // Включить навигацию при наведении
        'overlayNavByPages': false, // Навигация по страницам (Если подключен плагин Navigation)
        'overlayNavOrientation': 'horizontal' // Ориентация навигации при наведении (horizontal|vertical)
    };

    plugin.prototype.initialize = function () {
        this.$stage = this._core.$stage;
        this.$overlay = this.$stage.parent();
        this.$overlay.on(this._overlayHandlers);
    };

    plugin.prototype.destroy = function() {
        $.each(this._handlers, function (event, handler) {
            this.$element.off(event, handler);
        });

        $.each(this._overlayHandlers, function (event, handler) {
            this.$overlay.off(event, handler);
        });
    };

    $.fn.owlCarousel.Constructor.Plugins['OverlayNavigation'] = plugin;
})(window.Zepto || window.jQuery, window, document);