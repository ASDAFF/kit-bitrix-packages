(function ($, api) {
    if (!api.isFunction(BX))
        return;

    console.log(typeof BX.CDialog);

    if (api.isFunction(BX.CDialog)) {
        BX.CDialogForm = function (url, settings) {
            var dialog;

            settings = api.extend({}, settings, {
                'content_url': url
            });

            dialog = new BX.CDialog(settings);
            dialog.Save = function () {
                var form;

                form = $(dialog.GetForm());
                form.ajaxSubmit({
                    'success': function (response) {
                        try {
                            response = $.parseJSON(response);
                        } catch (exception) {}

                        if (response === true)
                            dialog.Close();

                        dialog.SetContent(response);
                    }
                });
            };

            return dialog;
        }
    }
})(jQuery, intec);