"use strict";

var WheelOfFortune = function WheelOfFortune(wheelSelector, params) {
  this.weel = document.querySelector(wheelSelector);
  this.params = params;
  this.degreesStart = null;
  this.degreesFinish = null;
  this.degreesDifference = null;
  this.degreesTemp = null;
  this.degrees = null;
  this.direction = null;
  this.items = Array.prototype.slice.call(this.weel.querySelectorAll('li'));
  this._cloneItems = Array.prototype.slice.call(this.weel.querySelectorAll('li'));
  this.itemsList = this.weel.querySelector('ul');
  this.onMouseDown = this.handlerMouseDown.bind(this);
  this.onMouseUp = this.handlerMouseUp.bind(this);
  this.onResize = this.handlerResize.bind(this);
  this.onMouseMove = this.handlerMouseMove.bind(this);
  this.onBtnClose = this.handlerBtnClose.bind(this);
  this.onBtnOpen = this.handlerBtnOpen.bind(this);
  this.QUANTITY_ITEMS = 12; // this.delta = this.items.length - this.QUANTITY_ITEMS / 2;

  this.numItemStart = null;
  this.numItemFinish = null;
  this.isInit = false;
  this.init();
};

WheelOfFortune.prototype.getCenterWeel = function () {
  var weelClientRect = this.weel.getBoundingClientRect();
  var centrWeel = {
    x: weelClientRect.left + weelClientRect.width / 2,
    y: weelClientRect.top + weelClientRect.height / 2
  };
  return centrWeel;
};

WheelOfFortune.prototype.rotateWheel = function (deg) {
  this.weel.firstElementChild.style.transform = 'rotate(' + deg + 'deg)';
};

WheelOfFortune.prototype.defineCoordsEvent = function (evt) {
  var coords;

  if (evt.clientX) {
    coords = {
      x: Math.round(evt.clientX - this.centrWeel.x),
      y: Math.round(evt.clientY - this.centrWeel.y)
    };
  }

  if (evt.targetTouches) {
    coords = {
      x: Math.round(evt.targetTouches[0].clientX - this.centrWeel.x),
      y: Math.round(evt.targetTouches[0].clientY - this.centrWeel.y)
    };
  }

  return coords;
};

WheelOfFortune.prototype.calcGipotenuze = function (coords) {
  return Math.sqrt(Math.pow(coords.x, 2) + Math.pow(coords.y, 2));
};

WheelOfFortune.prototype.getAngle = function (evt) {
  var coords = this.defineCoordsEvent(evt);
  var radians = Math.atan2(coords.x, coords.y);
  var degrees = radians * (180 / Math.PI) * -1 - 180;
  this.degreesCurrent = Math.abs(degrees);
  return degrees;
};

WheelOfFortune.prototype.handlerMouseDown = function (evt) {
  evt.preventDefault();
  this.degreesStart = this.getAngle(evt);
  this.weel.addEventListener('mousemove', this.onMouseMove);
  this.weel.addEventListener('touchmove', this.onMouseMove);
};

WheelOfFortune.prototype.handlerMouseUp = function (evt) {
  // evt.preventDefault();
  this.weel.removeEventListener('mousemove', this.onMouseMove);
  this.weel.removeEventListener('touchmove', this.onMouseMove);
};

WheelOfFortune.prototype.getDirection = function (evt) {
  if (this.degreesTemp > this.degreesCurrent) {
    return true;
  }

  if (this.degreesTemp < this.degreesCurrent) {
    return false;
  }
};

WheelOfFortune.prototype.triggerItems = function (evt) {
  this.numItemStart = Math.floor(this.degrees / 30);

  if (this.numItemStart !== this.numItemFinish) {
    if (this.numItemStart > this.numItemFinish) {
      this.direction = true;
    }

    if (this.numItemStart < this.numItemFinish) {
      this.direction = false;
    }

    this.numItemFinish = this.numItemStart;
    this.start = (this.items.length * 9999999 + this.delta) % this.items.length;
    this.items.forEach(function (item) {
      item.style.display = '';
      item.classList.remove('active');
    });

    for (var i = 0; i < this.QUANTITY_ITEMS; i++) {
      var s = (this.start + i) % this.items.length;
      this.items[s].style.display = 'flex';
      this.items[s].classList.add('active');
    }

    this.degreesFirst = this.items[this.start].getAttribute('data-angle');
    this.degreesLast = this.items[(this.start + this.QUANTITY_ITEMS - 1) % this.items.length].getAttribute('data-angle');

    if (this.direction) {
      this.items[(this.start + this.items.length - 1) % this.items.length].setAttribute('data-angle', this.degreesLast);
      this.items[(this.start + this.items.length - 1) % this.items.length].style.transform = 'rotate(' + this.degreesLast + 'deg) skew(60deg)';
      this.delta -= 1;
    } else {
      this.items[(this.start + this.QUANTITY_ITEMS) % this.items.length].setAttribute('data-angle', this.degreesFirst);
      this.items[(this.start + this.QUANTITY_ITEMS) % this.items.length].style.transform = 'rotate(' + this.degreesFirst + 'deg) skew(60deg)';
      this.delta += 1;
    }
  }
};

WheelOfFortune.prototype.handlerMouseMove = function (evt) {
  this.degreesFinish = this.getAngle(evt);

  if (Math.abs(this.degreesFinish - this.degreesStart) < 90) {
    this.degreesDifference = this.degreesFinish - this.degreesStart;
    this.degrees += this.degreesDifference;
    this.rotateWheel(this.degrees);
  }

  this.degreesStart = this.degreesFinish;
  this.triggerItems(evt);
  this.direction = this.getDirection(evt);
};

WheelOfFortune.prototype.handlerResize = function (evt) {
  this.centrWeel = this.getCenterWeel();
};

WheelOfFortune.prototype.handlerBtnClose = function (evt) {
  this.weel.classList.add('hide');
  this.btnMenuOpen.classList.remove('hide');
};

WheelOfFortune.prototype.handlerBtnOpen = function (evt) {
  var _this = this;

  this.weel.classList.remove('hide');
  this.btnMenuOpen.classList.add('hide');
  this.centrWeel = this.getCenterWeel();
  setTimeout(function () {
    _this.centrWeel = _this.getCenterWeel();
  }, 500);
};

WheelOfFortune.prototype.clonedItems = function () {
  if (this._cloneItems.length < this.QUANTITY_ITEMS) {
    var itemsList = this.weel.querySelector('ul');
    this.cloneItems = this.items.slice();
    this.cloneItems.forEach(function (item) {
      var cloneItem = item.cloneNode(true);
      cloneItem.classList.add('clone');
      itemsList.appendChild(cloneItem);
    });
    this._cloneItems = Array.prototype.slice.call(this.weel.querySelectorAll('li'));
    this.clonedItems();
  }

  return;
};

WheelOfFortune.prototype.init = function (evt) {
  if (!this.isInit) {
    this.weel.classList.add('nav-round-menu-box');
    this.weel.classList.add('hide');
    this.itemsList.classList.add('nav-round-menu__wrapper'); // this.centrWeel = this.getCenterWeel();

    this.weel.addEventListener('mousedown', this.onMouseDown);
    document.addEventListener('mouseup', this.onMouseUp);
    this.weel.addEventListener('touchstart', this.onMouseDown);
    document.addEventListener('touchend', this.onMouseUp);
    window.addEventListener('resize', this.onResize);
    this.items.forEach(function (item) {
      item.classList.add('nav-round-menu__item');
    });
    this.roundMenu = document.createElement('div');
    this.roundMenu.classList.add('nav-round-menu');
    this.roundMenu.appendChild(this.itemsList);
    this.weel.appendChild(this.roundMenu);
    this.btnMenu = document.createElement('button');
    this.btnMenu.classList.add('btn-round-menu-close');
    this.weel.appendChild(this.btnMenu);
    this.btnMenu.addEventListener('click', this.onBtnClose);
    this.btnMenu.addEventListener('touchstart', this.onBtnClose);
    this.btnMenuOpen = document.createElement('button');
    this.btnMenuOpen.classList.add('btn-round-menu-open');

    if (this.params.classBtnOpen) {
      this.btnMenuOpen.classList.add(this.params.classBtnOpen);
    }

    document.body.appendChild(this.btnMenuOpen);
    this.btnMenuOpen.addEventListener('click', this.onBtnOpen);
    this.btnMenuOpen.addEventListener('touchstart', this.onBtnOpen);
    this.clonedItems();
    this.items = Array.prototype.slice.call(this.weel.querySelectorAll('li'));
    this.delta = this.items.length - this.QUANTITY_ITEMS / 2;

    for (var i = 0; i < this.QUANTITY_ITEMS; i++) {
      var index = (this.delta + i) % this.items.length;
      this.items[index].style.display = 'flex';
      this.items[index].style.transform = 'rotate(' + 30 * i + 'deg) skew(60deg)';
      this.items[index].setAttribute('data-angle', 30 * i);
      this.items[index].classList.add('active');
    }

    this.isInit = true;
  }
};

WheelOfFortune.prototype.destroy = function (evt) {
  if (this.isInit) {
    this.weel.classList.remove('nav-round-menu-box');
    this.weel.classList.remove('hide');
    this.itemsList.classList.remove('nav-round-menu__wrapper');
    this.weel.removeEventListener('mousedown', this.onMouseDown);
    document.removeEventListener('mouseup', this.onMouseUp);
    this.weel.removeEventListener('mousemove', this.onMouseMove);
    this.weel.removeEventListener('touchmove', this.onMouseMove);
    this.weel.removeEventListener('touchstart', this.onMouseDown);
    document.removeEventListener('touchend', this.onMouseUp);
    window.removeEventListener('resize', this.onResize);
    this.btnMenu.removeEventListener('click', this.onBtnClose);
    this.btnMenu.removeEventListener('touchstart', this.onBtnClose);
    this.btnMenuOpen.removeEventListener('click', this.onBtnOpen);
    this.btnMenuOpen.removeEventListener('touchstart', this.onBtnOpen);
    this.items.forEach(function (item) {
      item.removeAttribute('data-angle');
      item.classList.remove('nav-round-menu__item');
      item.classList.remove('active');
      item.style = '';
    });
    this.weel.appendChild(this.itemsList);
    this.weel.removeChild(this.roundMenu);
    this.weel.removeChild(this.btnMenu);
    document.body.removeChild(this.btnMenuOpen);
    var itemsList = this.itemsList;
    var cloneItems = Array.prototype.slice.call(this.itemsList.querySelectorAll('.clone'));
    cloneItems.forEach(function (item) {
      itemsList.removeChild(item);
    });
    this.items = Array.prototype.slice.call(this.weel.querySelectorAll('li'));
    this._cloneItems = Array.prototype.slice.call(this.weel.querySelectorAll('li'));
    this.isInit = false;
  }
};