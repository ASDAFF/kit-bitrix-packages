universe.catalog = (function ($, api) {
    var catalog = {};

    catalog.offers = function (settings) {
        var self = this;
        var properties = [];
        var list = [];
        var current = null;

        settings = api.extend({}, settings);

        api.each(settings.properties, function (index, property) {
            properties.push(property);
        });

        api.each(settings.list, function (index, offer) {
            list.push(offer);
        });

        self.getProperties = function () {
            return properties;
        };

        self.getList = function () {
            return list;
        };

        self.getCurrent = function () {
            return current;
        };

        self.setCurrentById = function (id) {
            var offer = self.getById(id);
            var changed = false;

            if (offer !== null) {
                changed = current !== offer;
                current = offer;
            }

            if (changed) {
                self.trigger('change', current, self.getValues());
                return current;
            }

            return null;
        };

        api.extend(self, api.ext.events(self));
    };

    catalog.offers.prototype.getById = function (id) {
        var result = null;

        api.each(this.getList(), function (index, offer) {
            if (offer.id === id) {
                result = offer;
                return false;
            }
        });

        return result;
    };

    catalog.offers.prototype.getByValues = function (values) {
        var self = this;
        var result = null;

        api.each(self.getList(), function (index, offer) {
            var equal = true;

            api.each(self.getProperties(), function(index, property) {
                var value = offer.values[property.code];

                if (!api.isEmpty(value) && !api.isEmpty(values[property.code])) {
                    if (value != values[property.code])
                        equal = false;
                }
            });

            if (equal) {
                result = offer;
                return false;
            }
        });

        return result;
    };

    catalog.offers.prototype.setCurrentByValue = function (code, value) {
        var offer = this.getCurrent();
        var result = null;
        var values;

        if (offer === null)
            return result;

        values = api.extend({}, offer.values);
        values[code] = value;

        result = this.getByValues(values);

        if (result !== null)
            return this.setCurrentById(result.id);

        values = {};

        api.each(offer.values, function (offerValueCode, offerValue) {
            values[offerValueCode] = offerValue;

            if (offerValueCode == code) {
                values[offerValueCode] = value;
                return false;
            }
        });

        result = this.getByValues(values);

        if (result !== null)
            return this.setCurrentById(result.id);

        return result;
    };

    catalog.offers.prototype.setCurrentByValues = function (values) {
        var result = this.getByValues(values);

        if (result !== null)
            return this.setCurrentById(result.id);

        return result;
    };

    catalog.offers.prototype.getValues = function () {
        var self = this;
        var current = self.getCurrent();
        var properties = [];
        var result = {
            'displayed': {},
            'disabled': {},
            'enabled': {},
            'selected': {}
        };

        api.each(self.getList(), function (index, offer) {
            api.each(offer.values, function (code, value) {
                if (!api.isDeclared(result.displayed[code]))
                    result.displayed[code] = [];

                if (!api.inArray(value, result.displayed[code]))
                    result.displayed[code].push(value);
            });
        });

        api.each(self.getProperties(), function (index, property) {
            var code = property.code;

            api.each(self.getList(), function (index, offer) {
                var compared = true;
                var value = offer.values[property.code];

                if (current !== null) {
                    intec.each(properties, function (index, property) {
                        if (offer.values[property.code] != current.values[property.code]) {
                            compared = false;
                            return false;
                        }
                    });
                } else if (properties.length > 0) {
                    compared = false;
                }

                if (compared) {
                    if (!api.isDeclared(result.enabled[code]))
                        result.enabled[code] = [];

                    if (!api.inArray(value, result.enabled[code]))
                        result.enabled[code].push(value);
                }
            });

            properties.push(property);
        });

        api.each(result.displayed, function (code, values) {
            if (!api.isDeclared(result.disabled[code]))
                result.disabled[code] = [];

            api.each(values, function (index, value) {
                if (!api.inArray(value, result.enabled[code]))
                    result.disabled[code].push(value);
            });
        });

        if (current !== null)
            intec.each(current.values, function (code, value) {
                if (!api.isDeclared(result.selected[code]))
                    result.selected[code] = [];

                result.selected[code].push(value);
            });

        return result;
    };

    catalog.offers.prototype.isEmpty = function () {
        return this.getList().length === 0;
    };

    return catalog;
})(jQuery, intec);