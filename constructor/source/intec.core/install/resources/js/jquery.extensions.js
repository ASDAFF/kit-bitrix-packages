(function ($, document, window) {
    if (!$)
        return;

    $.fn.extend({
        'insertAtCaret': function (value) {
            this.each(function() {
                if (document.selection) {
                    var selection;

                    this.focus();

                    selection = document.selection.createRange();
                    selection.text = value;

                    this.focus();
                } else if (this.selectionStart || this.selectionStart == '0') {
                    var positionStart = this.selectionStart;
                    var positionEnd = this.selectionEnd;
                    var scroll = this.scrollTop;

                    this.value = this.value.substring(0, positionStart) +
                        value + this.value.substring(positionEnd, this.value.length);

                    this.focus();
                    this.selectionStart = positionStart + value.length;
                    this.selectionEnd = positionStart + value.length;
                    this.scrollTop = scroll;
                } else {
                    this.value += value;
                    this.focus();
                }
            });

            return this;
        }
    });
})(jQuery, document, window);