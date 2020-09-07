window.JCCatalogStoreSKU = function(params)
{
    var i;

    if(!params)
        return;

    this.config = {
        'id' : params.ID,
        'showEmptyStore'	: params.SHOW_EMPTY_STORE,
        'useMinAmount'		: params.USE_MIN_AMOUNT,
        'minAmount'			: params.MIN_AMOUNT,
        'measure'			: params.MEASURE
    };

    this.messages = params.MESSAGES;
    this.sku = params.SKU;
    this.stores = params.STORES;
    this.obStores = {};
    for (i in this.stores)
        this.obStores[this.stores[i]] = BX(this.config.id+"_"+this.stores[i]);

};

window.JCCatalogStoreSKU.prototype.offerOnChange = function(id)
{
    var curSku = this.sku[id],
        k,
        message,
        icon,
        parent;

    for (k in this.obStores)
    {
        message = (!!this.config.useMinAmount) ? this.getStringCount(0) : '0'+' '+this.config.measure[id];
        BX.adjust(this.obStores[k], {html: message});

        parent = BX.findParent(this.obStores[k], {tagName: 'div'});

        icon = BX.findChild(parent, {tagName: 'i'});
        BX.adjust(icon, {props: {className : this.getIconCount(0)}});

        if (!!curSku[k])
        {
            message = (!!this.config.useMinAmount) ? this.getStringCount(curSku[k]) : curSku[k]+' '+this.config.measure[id];
            BX.adjust(this.obStores[k],  {html: message});
            BX.adjust(icon, {props: {className : this.getIconCount(curSku[k])}});
        }

        if (!!this.config.showEmptyStore || curSku[k] > 0)
            BX.show(parent);
        else
            BX.hide(parent);
    }
};

window.JCCatalogStoreSKU.prototype.getIconCount = function(num)
{
    if (num == 0)
        return 'catalog-store-amount-icon catalog-store-amount-icon-times fas fa-times';
    else if (num >= this.config.minAmount)
        return 'catalog-store-amount-icon fas fa-check';
    else
        return 'catalog-store-amount-icon fas fa-check';
};

window.JCCatalogStoreSKU.prototype.getStringCount = function(num)
{
    if (num == 0)
        return this.messages['ABSENT'];
    else if (num >= this.config.minAmount)
        return this.messages['LOT_OF_GOOD'];
    else
        return this.messages['NOT_MUCH_GOOD'];
};