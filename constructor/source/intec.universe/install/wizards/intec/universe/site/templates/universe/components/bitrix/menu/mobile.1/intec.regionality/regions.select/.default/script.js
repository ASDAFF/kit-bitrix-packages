(function () {
    var prototype;

    if (window.JCIntecRegionalityRegionsSelect)
        return;

    window.JCIntecRegionalityRegionsSelect = function (parameters) {
        this.action = parameters.action;
        this.site = parameters.site;
    };

    prototype = window.JCIntecRegionalityRegionsSelect.prototype;
    prototype.select = function (region) {
        if (!region)
            return;

        BX.ajax({
            'url': this.action,
            'method': 'POST',
            'data': {
                'site': this.site,
                'region': region,
                'url': window.location.href
            },
            'dataType': 'json',
            'async': false,
            'start': true,
            'cache': false,
            'onsuccess': function (data) {
                if (!data)
                    return;

                if (data.status === 'success') {
                    if (data.action === 'redirect')
                        window.location = data.url;
                } else if (data.status === 'error') {
                    if (data.message)
                        console.error(data.message);
                }
            },
            'onfailure': function (data) {
                console.error('Error occurred due request (intec.regionality:region.select).');
                console.error(data);
            }
        })
    };
})();