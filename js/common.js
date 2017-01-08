/**
 * Common JavaScript
 *
 * @package     DuckSell
 * @author      Milos Stojanovic <info@interactive32.com>
 * @copyright   Copyright 2008-2016 Interactive32.com
 */

$(function () {

    // handle POST actions with csrf support
    var postAction = {
        initialize: function() {
            this.methodLinks = $('a[data-method]');
            this.registerEvents();
        },

        registerEvents: function() {
            this.methodLinks.on('click', this.handleMethod);
        },

        handleMethod: function(e) {
            var link = $(this);
            var httpMethod = link.data('method').toUpperCase();
            var form;

            if ( $.inArray(httpMethod, ['PUT', 'POST', 'DELETE']) === - 1 ) {
                return;
            }

            if ( link.data('confirm') ) {
                BootstrapDialog.confirm({
                    title: trans_warning,
                    message: link.data('confirm'),
                    type: BootstrapDialog.TYPE_WARNING,
                    closable: true,
                    draggable: true,
                    btnCancelLabel: trans_cancel,
                    btnOKLabel: trans_ok,
                    callback: function(result) {
                        if(result) {
                            form = postAction.createForm(link);
                            form.submit();
                            e.preventDefault();
                            return true;
                        }else {
                            return false;
                        }
                    }
                });
                return false;
            } else {
                form = postAction.createForm(link);
                form.submit();
                e.preventDefault();
            }
        },

        createForm: function(link) {
            var form =
                $('<form>', {
                    'method': 'POST',
                    'action': link.attr('href')
                });

            var token =
                $('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': php_csrf_token
                });

            var hiddenInput =
                $('<input>', {
                    'name': '_method',
                    'type': 'hidden',
                    'value': link.data('method')
                });

            return form.append(token, hiddenInput)
                .appendTo('body');
        }
    };
    postAction.initialize();

    $('#flash-notification-box .alert-success').delay(3000).slideUp(300);

    if (typeof $(this).iCheck === "function") {
        $('input.icheck-enable').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    }

    if (typeof $(this).slimScroll === "function") {
        $('.slimScrollDiv').slimScroll({
            height: '100%'
        });
    }

});