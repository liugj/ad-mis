var App = function ($) {
    var setNav = function () {
        var index = window.NavIndex || 0;
        $("#navMain li[data-index=" + index + "]").addClass("active");
    }

    var notify = function (message, type) {
        if (toastr) {
            toastr.options = {
                closeButton: true,
                hideDuration: 2000
            };
            type = type || "success";
            toastr[type](message);
        }        
    }

    var template = function (temp, data) {
        return temp.replace(/\{([\w\.]*)\}/g, function (str, key) {
            var keys = key.split("."), v = data[keys.shift()];
            for (var i = 0, l = keys.length; i < l; i++) v = v[keys[i]];
            return (typeof v !== "undefined" && v !== null) ? v : "";
        });
    }
    var changePwd = function () {
        $("#dialogChangePwd").dialog("open", {
            url: '/change_password',
            success: function () {
                $("#formChangePwd [data-inputmask]").inputmask();
                $("#formChangePwd").ajaxFormExt({
                    success: function (response) {
                        $("#dialogChangePwd").dialog("close");
                        alert(response.message);
                    }
                });
            }
        });
    };
    var change = function () {
        $("#dialogChange").dialog("open", {
            url: '/change',
            success: function () {
                $("#formChange [data-inputmask]").inputmask();
                $("#formChange").ajaxFormExt({
                    success: function (response) {
                        $("#dialogChange").dialog("close");
                        alert(response.message);
                        window.location = "/home";
                    }
                });
            }
        });
    };

    var ajaxAction= function (url, settings) {
        var sets = settings || {};
        var message = settings.message || {
            success: "操作执行成功",
            error: "操作执行失败"
        };

        function success(result) {
            if (result.success) {
                notify(result.message || message.success);
                if (typeof sets.success === 'function') {
                    sets.success(result.data);
                }
            } else {
                alert(result.message || message.error);
            }
        }

        if (typeof sets.before === "function") {
            if (sets.before() === false) {
                return;
            }
        }

        $.ajax(url, {
            data: sets.data,
            type: sets.type || 'POST',
            dataType: 'json',
            success: success
        });
    }

    return {
        init: function () {
            setNav();
        },
        
        notify: function (message, type) {
            notify(message, type);
        },

        template: function (temp, data) {
            return template(temp, data);
        },

        ajaxDelete: function (url, settings) {
            ajaxAction(url, $.extend(settings, {
                message:{
                    success: "数据删除成功",
                    error: "数据删除错误"
                }                
            }));
        },

        ajaxAction: function (url, settings) {
            ajaxAction(url, settings);
        },
        changePwd : function () {
            changePwd();
        },
        change: function () {
            change();
        }
    };
}(jQuery);

jQuery(function () {
    App.init();
});

/*ajaxFormExt*/
(function ($) {

    var AjaxFormExt = function (form, options) {
        this.$form = $(form);
        this.options = $.extend(true, {}, $.fn.ajaxFormExt.defaults, options);

        this.$form.on("submit.ajaxFormExt", $.proxy(function () { this.submit(); return false; }, this));
        this.init();
    };

    AjaxFormExt.prototype = {
        constructor: AjaxFormExt,

        init: function () {
            $.validator.ext.parse(this.$form);

            this.$modal = $("<div class='form-modal'><img src='" + this.options.loadingImageUrl + "' class='loading'></div>").hide();
            this.$form.append(this.$modal);
        },

        submit: function () {
            var self = this;
            var opts = self.options;

            if (opts.beforeSubmit(self) === false) {
                return false;
            }

            if (!self.valid()) {
                return false;
            }

            self.$modal.show();
            self.$form.ajaxSubmit({
                dataType: "json",
                success: function (result) {                    
                    self.$modal.hide();

                    if (opts.message) {
                        if (result.success) {
                            opts.success(result, self);
                            var message = result.message || opts.message.success;
                            App.notify(message);
                        } else {
                            var message = result.message || opts.message.error;
                            alert(message);
                        }
                    } else {
                        opts.success(result, self);
                    }
                }
            });            
        },

        reset: function () {
            this.$form[0].reset();
        },

        valid: function () {
            return this.$form.valid();
        }
    };

    $.fn.ajaxFormExt = function (option, value) {
        var methodReturn;
        var $set = this.each(function () {
            var $this = $(this);
            var data = $this.data("ajaxFormExt");
            var options = typeof option === "object" && option;

            if (!data) $this.data("ajaxFormExt", (data = new AjaxFormExt(this, options)));
            if (typeof option === "string") methodReturn = data[option](value);
        });
        return (methodReturn === undefined) ? $set : methodReturn;
    }

    $.fn.ajaxFormExt.defaults = {
        message: {
            success: "操作执行成功",
            error: "操作执行失败"
        },
        loadingImageUrl: "/assets/img/ajax-loading.gif",
        success: $.noop,
        beforeSubmit: $.noop
    };
})(jQuery);

/*dialog*/
(function ($) {
    Dialog = function (element, options) {
        this.$element = $(element);
        this.options = $.extend(true, {}, $.fn.dialog.defaults, options);

        this.$element.on('click.dialog', '[data-handler="close"]', $.proxy(function () { this.close(); }, this));
        this.init();
    };

    Dialog.prototype = {
        constructor: Dialog,
        init: function () {                     
            this.$element.css({
                "position": "absolute",
                "top": this.options.top,
                "width": this.options.width,
                "height": this.options.height,
                "zIndex": this.options.zIndex
            }).hide();

            this.$mask = $();
            if (this.options.mask) {
                var mask = $(".dialog-mask");
                if (mask.length == 0) {
                    mask = $('<div class="dialog-mask"></div>').hide();
                    $("body").append(mask);
                }

                this.$mask = mask;
            }
        },

        load: function (option) {
            var self = this;
            var element = self.$element.find(".dialog-body");
            element.html("<img src='" + this.options.loadingImageUrl + "' class='loading'/>");

            var url, success;
            if (typeof (option) === "string") {
                url = option;
            }
            if (typeof (option) === "object") {
                url = option.url;
                success = option.success;
            }
            element.load(url, function () {
                if (success) {
                    success(self);
                }
            });
        },

        open: function (option) {
            this.$mask.show();
            var x = parseInt($(window).width() - this.$element.outerWidth()) / 2;
            if (this.options.fadeIn) {
                this.$element.fadeIn();
            } else {
                this.$element.show();
            }
            this.$element.css("left", x);

            if (option) {
                if ($().select2) {
                    this.$element.find(".select2").select2("destroy");
                }

                this.load(option);
            }
        },

        close: function () {
            this.$mask.hide();
            this.$element.hide();

        }
    };

    $.fn.dialog = function (option, value) {
        var methodReturn;
        var $set = this.each(function () {
            var $this = $(this);
            var data = $this.data("dialog");
            var options = typeof option === "object" && option;

            if (!data) $this.data("dialog", (data = new Dialog(this, options)));
            if (typeof option === "string") methodReturn = data[option](value);
        });
        return (methodReturn === undefined) ? $set : methodReturn;
    }

    $.fn.dialog.defaults = {
        top: "10%",
        width: "600px",
        height:"auto",
        mask: true,
        fadeIn: true,
        zIndex: 11,
        loadingImageUrl: "/assets/img/ajax-loading.gif"
    };
})(jQuery);

/*alert, confirm*/
(function ($) {
    $(function () {
        var boxAlert = $('<div id="modalAlert" class="modalbox"><span class="modalbox-icon"><i class="fa fa-info-circle"></i></span><div class="modalbox-body"></div><div class="modalbox-foot"><button class="button button-small bg-sub" data-handler="close">确定</button></div></div>');
        $(document.body).append(boxAlert);
        boxAlert.dialog({ width: "250px", top: "30%", fadeIn: false });

        var boxConfirm = $('<div id="boxConfirm" class="modalbox"><span class="modalbox-icon"><i class="fa fa-question-circle"></i></span><div class="modalbox-body"></div><div class="modalbox-foot"><button class="button button-small bg-sub" data-handler="ok">确定</button> <button class="button button-small" data-handler="close">取消</button></div></div>');
        $(document.body).append(boxConfirm);
        boxConfirm.dialog({ width: "250px", top: "30%", fadeIn: false });

        boxConfirm.on("click", "button[data-handler='ok']", function () {
            boxConfirm.dialog("close");
            fnConfirm();
        });
    });

    window.alert = function (text) {
        $("#modalAlert").find(".modalbox-body").html(text);
        $("#modalAlert").dialog("open");
        $("#modalAlert button").focus();
    };

    var fnConfirm = $.noop;

    window.confirm = function (text, callback) {
        $("#boxConfirm").find(".modalbox-body").html(text);
        $("#boxConfirm").dialog("open");
        $("#boxConfirm button[data-handler='ok']").focus();
        if (typeof (callback) === "function") {
            fnConfirm = callback;
        } else {
            fnConfirm = $.noop;
        }
    };

})(jQuery);
