//fixItem <=== item for fix
//params: marginTop
//params: marginBottom
//  example  let fixBlock = new FixedBlock ('.item', {
//      marginTop: 50,
//      marginBottom: 50,
//  });
//  fixBlock.init();
//  fixBlock.destroy();


const FixedBlock = function (fixItem, params) {
    this.fixItem = document.querySelector(fixItem);
    if (!this.fixItem) {
        return;
    }
    this.parentfixItem = this.fixItem.parentElement;
    if (!this.parentfixItem) {
        return;
    }
    this.params = params ? params : {};
    this.params.marginTop = this.params.marginTop || 0;
    this.params.marginBottom = this.params.marginBottom || 0;
    this.handlerScroll = this.initFixedManager.bind(this, this.params.marginTop, this.params.marginBottom);
};

FixedBlock.prototype.init = function () {
    this.parentfixItem.style.position = 'relative';
    this.fixItem.style.position = 'sticky';
    this.fixItem.style.top = '0px';
    window.addEventListener("scroll", this.handlerScroll);
};

FixedBlock.prototype.destroy = function () {
    this.parentfixItem.style.position = '';
    this.fixItem.style.top = '';
    this.fixItem.style.position = '';
    this.fixItem.style.transition = '';
    this.fixItem.style.marginBottom = '';
    window.removeEventListener("scroll", this.handlerScroll);
};

FixedBlock.prototype.initFixedManager = function (marginTop, marginBottom) {
    if(!marginTop) {
        this.fixItem.style.top = this.getHeadersHeight() + 'px';
    } else {
        this.fixItem.style.top = this.getHeadersHeight() + marginTop + 'px';
    }
    if(marginBottom) {
        this.fixItem.style.marginBottom = marginBottom + 'px';
    }
};

FixedBlock.prototype.getHeadersHeight = function () {
    let headersHeight = 0;

    if (document.querySelector(".fix-header-one")) {
        headersHeight += document.querySelector(".fix-header-one").clientHeight;
    }

    if (document.querySelector(".fix-header-two")) {
        headersHeight += document.querySelector(".fix-header-two").clientHeight;
    }

    if (document.querySelector(".bx-panel-fixed")) {
        headersHeight += document.querySelector(".bx-panel-fixed").clientHeight;
    }

    if (document.querySelector(".header-two__nav.active")) {
        headersHeight += document.querySelector(".header-two__nav.active").clientHeight;
    }

    return headersHeight;
};
