(function ($) {
    var WeekDayPicker = function (element, options) {
        this.$element = $(element);
        this.options = $.extend({}, $.fn.weekdaypicker.defaults, options);

        this.isShift = false;
        this.init();
        this.initValue();
    }

    WeekDayPicker.prototype = {
        constructor: WeekDayPicker,

        init: function () {
            var parent = this.$element.parent();

            var handler = $('<a href="javascript:;" class="weekdaypicker-handler">点击编辑 <i class="fa fa-calendar"></i></a>');
            parent.append(handler);

            var wrap = $('<div class="weekdaypicker"><div class="weekdaypicker-head">' +
                '<a href="javascript:;" data-filter="0,167">全部时间</a><a href="javascript:;" data-filter="0,119">周一至周五</a><a href="javascript:;" data-filter="120,167">周末</a>' +
                '<div class="info"><i class="full"></i> <span>投放时间段</span> <i class="stop"></i> <span>暂停时间段</span> <span>（按住Ctrl键连选）</span></div></div>' +
                '<div class="weekdaypicker-body"></div>' +
                '<div class="weekdaypicker-foot"><button type="button" class="button ok">确定</button><button type="button" class="button cancel">取消</button></div></div>').hide();
            parent.append(wrap);

            var index = 0;
            var weeks = ["星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"];
            $.each(weeks, function (i, n) {
                var row = $('<div class="row"></div>');
                var label = $('<label class="i-check"><input type="checkbox" /><i></i>' + n + '</label>');
                row.append(label);

                for (var i = 0; i < 24; i++) {
                    if (i % 5 == 0) {
                        row.append('<span class="split"></span>');
                    }
                    var item = $('<span class="item"></item>').text(i);
                    item.attr("data-index", index);
                    row.append(item);

                    index++;
                }

                wrap.find(".weekdaypicker-body").append(row);
            });

            var lastIndex = -1;

            wrap.on("click.weekdaypicker", "input:checkbox", function () {
                var checked = $(this).prop("checked");
                var items = $(this).parents(".row").find(".item");
                if (checked) {
                    items.addClass("active");
                } else {
                    items.removeClass("active");
                }
            });

            var self = this;
            handler.on("click.weekdaypicker", function () { self.open(); });

            wrap.on("click.weekdaypicker", ".item", function () {
                var item = $(this);
                item.toggleClass("active");

                var index = parseInt(item.attr("data-index"));
                if (self.isShift && lastIndex > -1) {
                    var beginIndex = Math.min(index, lastIndex);
                    var endIndex = Math.max(index, lastIndex);
                    for (var i = beginIndex; i <= endIndex; i++) {
                        var oItem = wrap.find(".item[data-index=" + i + "]");
                        if (item.hasClass("active")) {
                            oItem.addClass("active");
                        } else {
                            oItem.removeClass("active");
                        }
                    }
                }

                lastIndex = index;
            });

            wrap.on("click.weekdaypicker", ".weekdaypicker-head [data-filter]", function () {
                var filter = $(this).attr("data-filter").split(",");
                wrap.find(".item").removeClass("active");
                var beginIndex = parseInt(filter[0]);
                var endIndex = parseInt(filter[1]);
                for (var i = beginIndex; i <= endIndex; i++) {
                    var oItem = wrap.find(".item[data-index='" + i + "']");
                    oItem.addClass("active");
                }
            });
            wrap.on("click.weekdaypicker", ".weekdaypicker-foot button.ok", function () { self.setValue(); self.close(); });
            wrap.on("click.weekdaypicker", ".weekdaypicker-foot button.cancel", function () { self.close(); });

            this.$wrap = wrap;
        },

        open: function () {
            var self = this;
            self.$wrap.show();
            $(document).on("keydown.weekdaypicker", function (event) {
                if (event.keyCode == 17) {
                    self.isShift = true;
                }
            });

            $(document).on("keyup.weekdaypicker", function (event) {
                if (event.keyCode == 17) {
                    self.isShift = false;
                }
            });
        },

        close: function () {
            $(document).off(".weekdaypicker");
            this.$wrap.hide();
        },

        initValue: function () {
            var values = this.$element.val().split(",");            
            var self = this;
            $.each(values, function (i, n) {
                self.$wrap.find(".item[data-index='" + n + "']").addClass("active");
            });
        },

        setValue: function () {
            var items = this.$wrap.find(".item.active");
            var value = [];
            items.each(function () {
                value.push($(this).attr("data-index"));
            });
            this.$element.val(value.join(","));
        }
    };

    $.fn.weekdaypicker = function (option, value) {
        var methodReturn;

        var $set = this.each(function () {
            var $this = $(this);
            var data = $this.data("weekdaypicker");
            var options = typeof option === "object" && option;

            if (!data) $this.data("weekdaypicker", (data = new WeekDayPicker(this, options)));
            if (typeof option === "string") methodReturn = data[option](value);
        });

        return (methodReturn === undefined) ? $set : methodReturn;
    };

    $.fn.weekdaypicker.defaults = {
        
    };
})(jQuery);