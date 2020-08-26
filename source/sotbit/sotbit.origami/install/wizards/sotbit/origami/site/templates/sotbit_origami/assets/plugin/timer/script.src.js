/*
params ==>
    @endTime: string, example 'December 17, 2019 03:24:00'
    @day: string, title day
    @hours: string, title hours
    @minutes: string, title minutes
    @seconds: string, title seconds
    @size: string, title size
    @size: string, lg || md || sm
    @customClass: string, CSS class for custom

metods ==>
    @timerInit()        <== inicial slider
    @timerDestroy()     <== destroy slider

props ==>
    @isShow  <== true/false
 */

const TimerAction = function (params) {
    this.params = params ? params : {};
    if (this.params.itemParent) {
        this.item = document.querySelector(this.params.itemParent);
    } else {
        return;
    }
    this.endTime = this.params.endTime;
    this.isShow = false;
    this.timerInit();
}

TimerAction.prototype.timerInit = function () {
    this.timer = this.createTimer();
    this.date = Array.prototype.slice.call(this.timer.querySelectorAll('.timerDate'));
    this.hours = Array.prototype.slice.call(this.timer.querySelectorAll('.timerHours'));
    this.minutes = Array.prototype.slice.call(this.timer.querySelectorAll('.timerMinutes'));
    this.seconds = Array.prototype.slice.call(this.timer.querySelectorAll('.timerSeconds'));
    this.timeRender();
    let currentTime = Date.parse(Date());
    let restTime = this.endTime - currentTime;
    if(restTime > 1000) {
        this.timeInterval = setInterval(this.timeRender.bind(this), 1000);
    } else {
        this.date[0].innerHTML = '00';
        this.hours[0].innerHTML = '00';
        this.minutes[0].innerHTML = '00';
        this.seconds[0].innerHTML = '00';
    }
    this.isShow = true;
}

TimerAction.prototype.timerDestroy = function () {
    this.timer.remove();
    this.isShow = false;
}

TimerAction.prototype.createEl = function (tag, className) {
    let item = document.createElement(tag);
    item.classList.add(className);
    return item;
}

TimerAction.prototype.createTimer = function () {
    let timer = this.createEl('div', 'timer');
    if (this.params.size) {
        timer.classList.add(this.params.size);
    } else {
        timer.classList.add('lg');
    }

    if (this.params.customClass) {
        timer.classList.add(this.params.customClass);
    }

    let template =
        `<div class="timer-wrapper">
            <div class="timer-section">
                <div class="timer-count timerDate"></div>
                <div class="timer-title timerDateTitle">${this.params.day ? this.params.day : ''}</div>
            </div>
            <div class="timer-section">
                <div class="timer-count timerHours"></div>
                <div class="timer-title timerHoursTitle">${this.params.hours ? this.params.hours : ''}</div>
            </div>
            <div class="timer-section">
                <div class="timer-count timerMinutes"></div>
                <div class="timer-title timerMinutesTitle">${this.params.minutes ? this.params.minutes : ''}</div>
            </div>
            <div class="timer-section">
                <div class="timer-count timerSeconds"></div>
                <div class="timer-title timerSecondsTitle">${this.params.seconds ? this.params.seconds : ''}</div>
            </div>
        </div>`;
    timer.innerHTML = template;
    this.item.style.position = 'relative';
    this.item.appendChild(timer);
    return timer;
}

TimerAction.prototype.calcRestTime = function () {
    let currentTime = Date.parse(Date());
    let restTime = this.endTime - currentTime;

    let formatRestTime = {
        day: Math.floor((restTime / (1000 * 60 * 60 * 24))),
        hours: Math.floor((restTime / (1000 * 60 * 60) % 24)),
        minutes: Math.floor((restTime / 1000 / 60) % 60),
        seconds: Math.floor((restTime / 1000) % 60)
    }

    if (restTime <= 999) {
        clearInterval(this.timeInterval);
        this.seconds[0].innerHTML = '00';
    }

    if (formatRestTime.day <= 0) {
        this.date[0].parentElement.style.display = 'none';
    }

    return formatRestTime;
}

TimerAction.prototype.timeRender = function () {
    let restTime = this.calcRestTime();
    this.date[0].innerHTML = ('0' + restTime.day).slice(-2);
    this.hours[0].innerHTML = ('0' + restTime.hours).slice(-2);
    this.minutes[0].innerHTML = ('0' + restTime.minutes).slice(-2);
    this.seconds[0].innerHTML = ('0' + restTime.seconds).slice(-2);
}

