window.addEventListener("DOMContentLoaded", function () {
    setDataHeight();

    let fixedManagerBlock = new FixedBlock('.questions__manager', {
    });
    fixedManagerBlock.init();
});

window.addEventListener("load", function () {
    let toggles = document.querySelectorAll(".questions__content_question");

    setDataHeight();
    initToggles(toggles);
    setMinHeightManagerPanel();
    scrollToQuestionForm();
});

window.addEventListener("resize", function () {
    setDataHeight();
    setMinHeightManagerPanel();
    closeAllAnswers();
});


function setDataHeight() {
    let answerWrappers = document.querySelectorAll(".answer-wrapper");
    for (let i = 0; i < answerWrappers.length; i++) {
        answerWrappers[i].dataset.height = answerWrappers[i].querySelector(".answer-text").clientHeight;
    }
}

function setMinHeightManagerPanel() {
    let manager = document.querySelector(".questions__manager");
    let rightSidebar = document.querySelector(".questions__manager_wrapper");
    rightSidebar.style.minHeight = manager.clientHeight + "px";
}

function showAnswer(questionWrapper) {
    let answerWrapper = questionWrapper.querySelector(".answer-wrapper");

    if (questionWrapper.dataset.opened) {
        answerWrapper.style.height = 0 + "px";
        delete questionWrapper.dataset.opened;
    } else {
        answerWrapper.style.height = answerWrapper.dataset.height + "px";
        questionWrapper.dataset.opened = true;
    }
}

function closeAllAnswers() {
    let questionWrappers = document.querySelectorAll(".questions__content_question");

    for (let i = 0; i < questionWrappers.length; i++) {
        let answerWrapper = questionWrappers[i].querySelector(".answer-wrapper");

        if (questionWrappers[i].dataset.opened) {
            answerWrapper.style.height = 0 + "px";
            delete questionWrappers[i].dataset.opened;
        }
    }
}

function initToggles(questionWrapper) {
    for (let i = 0; i < questionWrapper.length; i++) {
        questionWrapper[i].addEventListener("click", function () {
            showAnswer(this);
        });
    }
}

function scrollToQuestionForm() {
    let button = document.querySelector(".questions__manager_button");

    button.addEventListener("click", function () {
        //scroll to form
        let coordY = getYPosition(document
                .querySelector(".questions__form"))
            - getHeadersHeight();

        if (coordY > window.pageYOffset) {
            let scroller = setInterval(function () {
                let scrollBy = 13;

                if ((scrollBy < coordY - window.pageYOffset)
                    && (window.innerHeight + window.pageYOffset < document.body.offsetHeight)
                    && (coordY !== window.pageYOffset)) {

                    window.scrollBy(0, scrollBy);

                    let coordYNew = getYPosition(document
                            .querySelector(".questions__form"))
                        - getHeadersHeight();

                    if(coordYNew !== coordY) {
                        coordY = coordYNew;
                    }

                } else {
                    window.scrollTo(0, coordY);
                    clearInterval(scroller);
                    focusOnForm();
                }

                if (window.pageYOffset > coordY) {
                    window.scrollTo(0, coordY);
                    clearInterval(scroller);
                    focusOnForm();
                }
            }, 0.3);
        }

        // focus on form
        function focusOnForm() {
            if (document.querySelector(".contacts-call-back-form .main-input-md")) {
                document.querySelector(".contacts-call-back-form .main-input-md").focus();
            }
        }
    })
}

function getYPosition(element) {
    let yPosition = 0;

    while (element) {
        yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
        element = element.offsetParent;
    }

    return yPosition;
}

function getHeadersHeight() {
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
}
