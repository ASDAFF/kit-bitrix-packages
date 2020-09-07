"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var SotbitRegionsDelivery =
/*#__PURE__*/
function () {
  function SotbitRegionsDelivery(params) {
    _classCallCheck(this, SotbitRegionsDelivery);

    this.tabID = document.getElementById('TAB_DELIVERY');
    this.contentID = document.getElementById('DELIVERY_CONTENT');

    var _this = this;

    this.tabID.addEventListener('click', function (event) {
      _this.clickTab();
    });
    this.componentPath = params.componentPath;
    this.parameters = params.parameters;
    this.siteId = params.siteId;
    this.template = params.template;
    this.isContentLoad = false;

    this.handlerResponse = function () {
      var _this2 = this;

      this.rootId = params.root;
      this.root = document.querySelector('.detailed-tabs__tabs-content [id^="sotbit-delivery"]') || document.querySelector('.detailed-feat__item-content [id^="sotbit-delivery"]'); // this.changeRegion = this.root.querySelector('[data-entity="change-region"]');

      this.modal = this.root.querySelector('[data-entity="modal"]');
      this.overlay = this.root.querySelector('[data-entity="overlay"]');
      this.close = this.root.querySelector('[data-entity="close"]');
      this.country = this.root.querySelectorAll('[data-entity="country"]');
      this.tab = this.root.querySelectorAll('[data-entity="tab-content"]');
      this.item = this.root.querySelectorAll('[data-entity="item"]');

      if (this.item.length > 1) {
        var _loop = function _loop(i) {
          _this2.item[i].addEventListener('click', function (event) {
            return _this2.chooseLocation(_this2.item[i].dataset.index);
          });
        };

        for (var i = 0; i < this.item.length; ++i) {
          _loop(i);
        }
      }

      this.filter = this.root.querySelector('[data-entity="filter-regions"]'); // this.changeRegion.addEventListener('click',event => this.showPopup());

      document.addEventListener('click', function (evt) {
        handlerClickChangeRegion(evt, _this);
      });

      function handlerClickChangeRegion(evt, item) {
        if (evt.target.closest("[data-entity = 'change-region']")) {
          item.showPopup();
        }
      } // let target = document.querySelector('.detailed-tabs__tabs-content #DELIVERY_CONTENT').cloneNode(true);
      // console.log(target);
      // let mobileDelivery = document.querySelector('.detailed-tabs__tabs.js-tabs #DELIVERY_CONTENT');
      // if (target && mobileDelivery){
      //     mobileDelivery.replaceWith(target);
      // }


      if (this.overlay !== null) {
        this.overlay.addEventListener('click', function (event) {
          return _this.closePopup();
        });
      }

      if (this.close !== null) {
        this.close.addEventListener('click', function (event) {
          return _this2.closePopup();
        });
      }

      if (this.country.length > 1) {
        var _loop2 = function _loop2(_i) {
          _this2.country[_i].addEventListener('click', function (event) {
            return _this2.chooseCountry(_this2.country[_i].dataset.countryId);
          });
        };

        for (var _i = 0; _i < this.country.length; ++_i) {
          _loop2(_i);
        }
      }

      if (this.filter !== null) {
        this.filter.addEventListener('input', function (event) {
          return _this2.filterItems(_this2.filter.value);
        });
      }

      var regions = document.querySelectorAll('.detailed-tabs__delivery-description');

      if (regions.length > 1) {
        var currentRegion = regions[regions.length - 1].textContent;

        for (var _i2 = 0; regions.length - 1 > _i2; _i2++) {
          regions[_i2].textContent = currentRegion;
        }
      }

      window.toggle = this.toggleWaysDelivery();
    };

    if (params.startAjax) {
      this.startAjax();
    }
  }

  _createClass(SotbitRegionsDelivery, [{
    key: "toggleWaysDelivery",
    value: function toggleWaysDelivery() {
      var COUNT_VISIBLE_ITEM = 3;
      var itemsWrapper = this.root.querySelector('.detailed-tabs__delivery-way-items-wrapper');
      var itemsContainer = this.root.querySelector('.detailed-tabs__delivery-way-items-container');
      var items = this.root.querySelectorAll('.detailed-tabs__delivery-way-item');
      var btnShow = this.root.querySelector('.detailed-tabs__delivery-way-btn-show');
      var heightWrapper = null;
      var MAGRIN_BOTTOM = 20;
      var heightItemsVisible = null;

      if (items.length > COUNT_VISIBLE_ITEM) {
        heightWrapper = itemsWrapper.offsetHeight;
        heightItemsVisible = getHeightVisible(COUNT_VISIBLE_ITEM, items) + MAGRIN_BOTTOM * (COUNT_VISIBLE_ITEM - 1);
        btnShow.style.display = 'flex';
        itemsWrapper.style.height = heightItemsVisible + 'px';
        itemsContainer.classList.add('overflow');
        btnShow.addEventListener('click', handlerClickBtn);
      }

      function calcStartHeight() {
        itemsWrapper.style.height = 'auto';
        heightWrapper = itemsWrapper.offsetHeight;
        heightItemsVisible = getHeightVisible(COUNT_VISIBLE_ITEM, items) + MAGRIN_BOTTOM * (COUNT_VISIBLE_ITEM - 1);
        itemsWrapper.style.height = heightItemsVisible + 'px';
      }

      function getHeightVisible(count, items) {
        var height = null;

        for (var i = 0; COUNT_VISIBLE_ITEM > i; i++) {
          height += items[i].offsetHeight;
        }

        return height;
      }

      function handlerClickBtn() {
        if (!itemsWrapper.classList.contains('open')) {
          itemsWrapper.classList.add('open');
          itemsWrapper.style.height = heightWrapper + 'px';
        } else {
          itemsWrapper.classList.remove('open');
          itemsWrapper.style.height = getHeightVisible(COUNT_VISIBLE_ITEM, items) + MAGRIN_BOTTOM + 'px';
        }
      }

      window.addEventListener('resize', function () {
        calcStartHeight();
      });
      return {
        calcHeight: calcStartHeight
      };
    }
  }, {
    key: "showPopup",
    value: function showPopup() {
      this.modal.style.display = 'block';
      this.overlay.style.display = 'block';
    }
  }, {
    key: "closePopup",
    value: function closePopup() {
      this.modal.style.display = 'none';
      this.overlay.style.display = 'none';
    }
  }, {
    key: "chooseCountry",
    value: function chooseCountry(countryId) {
      if (this.tab.length > 1) {
        for (var i = 0; i < this.tab.length; ++i) {
          this.country[i].classList.remove('active');
          this.tab[i].classList.remove('active');

          if (this.tab[i].dataset.countryId == countryId) {
            this.tab[i].classList.add('active');
            this.country[i].classList.add('active');
          }
        }
      }
    }
  }, {
    key: "filterItems",
    value: function filterItems(value) {
      var list = this.root.querySelectorAll('[data-entity="item"]');
      var letters = this.root.querySelectorAll('[data-entity="letter"]');
      value.toLowerCase();

      if (list.length) {
        for (var i = 0; i < list.length; ++i) {
          list[i].style.display = "block";
          var city = list[i].innerHTML.toLowerCase().trim();

          if (value.length > 0) {
            if (city.substr(0, value.length) != value) {
              list[i].style.display = "none";
            }
          }
        }
      }

      if (letters.length) {
        for (var i = 0; i < letters.length; ++i) {
          var was = false;
          var child = letters[i].childNodes;

          for (var j = 0; j < child.length; ++j) {
            if (child[j].dataset !== undefined && child[j].dataset.entity == 'item-list') {
              var child2 = child[j].childNodes;

              if (child2.length) {
                for (var k = 0; k < child2.length; ++k) {
                  if (child2[k].dataset !== undefined && child2[k].dataset.entity == 'item') {
                    var style = getComputedStyle(child2[k]);

                    if (style.display != 'none') {
                      was = true;
                      break;
                    }
                  }
                }
              }
            }
          }

          letters[i].style.display = "block";

          if (!was) {
            letters[i].style.display = "none";
          }
        }
      }
    }
  }, {
    key: "chooseLocation",
    value: function chooseLocation(id) {
      BX.showWait();
      var defaultData = {
        siteId: this.siteId,
        template: this.template,
        parameters: this.parameters
      };
      var data = {
        Id: id
      };
      BX.ajax({
        url: this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
        method: 'POST',
        dataType: 'html',
        timeout: 60,
        data: BX.merge(defaultData, data),
        onsuccess: BX.delegate(function (result) {
          this.root.outerHTML = result;
          this.handlerResponse();
        }, this)
      });
      BX.closeWait();
    }
  }, {
    key: "clickTab",
    value: function clickTab() {
      if (this.isContentLoad) {
        return;
      }

      var defaultData = {
        siteId: this.siteId,
        template: this.template,
        parameters: this.parameters
      };
      createLoadersMore($(this.contentID));
      BX.ajax({
        url: this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
        method: 'POST',
        dataType: 'html',
        timeout: 60,
        async: true,
        data: defaultData,
        onsuccess: BX.delegate(function (result) {
          this.contentID.innerHTML = result;
          this.isContentLoad = true;
          removeLoadersMore($(this.contentID));
          this.handlerResponse();

          if (window.innerWidth <= 768) {
            $('#TAB_DELIVERY').click();
            $('#TAB_DELIVERY').click();
          }
        }, this)
      });
    }
  }, {
    key: "startAjax",
    value: function startAjax() {
      this.clickTab();
    }
  }]);

  return SotbitRegionsDelivery;
}();