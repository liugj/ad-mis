(function ($) {
    var Pager = function (element, options) {
        this.$element = $(element);
        this.options = $.extend({}, $.fn.pager.defaults, options);
        this.index = 1;
        this.pageCount = 0;

        this.$element.on('click', 'a', $.proxy(this.onClick, this));
        if (this.options.autoLoad) {
            this.load();
        }
    }

    function number(idx, text) {
        var node = $('<li><a href="javascript:;">' + text + '</a></li>');
        node.data("index", idx);
        return node;
    }

    Pager.prototype = {
        constructor: Pager,

        render: function (total) {
            var wrap = this.$element;
            var opts = this.options;
            var index = this.index;

            wrap.empty();

            if (total == 0) {
                return;
            }

            var pageCount = Math.ceil(total / opts.size); //总页数
            this.pageCount = pageCount;

            if (index > 1) {
                var g1 = $('<ul class="pagination"></ul> ');
                g1.append(number(index - 1, "上一页"));
                wrap.append(g1);
            }

            if (opts.showNumber) {
                var g2 = $('<ul class="pagination pagination-group"></ul>');

                var beginIdx = index - ((index - 1) % opts.numberCount);
                var endIdx = beginIdx + opts.numberCount - 1;
                if (endIdx > pageCount) endIdx = pageCount;

                for (var i = beginIdx; i <= endIdx; i++) {
                    var node = number(i, i);
                    if (i == index) {
                        node.addClass("active");
                    }
                    g2.append(node);
                }

                wrap.append(g2);
            }

            if (index < pageCount) {
                var g3 = $(' <ul class="pagination"></ul>');
                g3.append(number(index + 1, "下一页"));
                wrap.append(g3);
            }

            if (opts.showText) {
                var beginRow = (index - 1) * opts.size + 1;
                var endRow = index * opts.size;
                if (endRow > total) endRow = total;
                var html = pageCount > 0 ? (beginRow + "-" + endRow + "/" + total) : "";
                wrap.append('<span class="pager-text">' + html + '</span>');
            }
        },

        onClick: function (event) {
            event.preventDefault();
            var index = $(event.target).parent().data("index");
            this.load({ page: index });
        },

        prev: function () {
            if (this.index > 1) {
                this.load({ page: this.index - 1 });
            }
        },

        next: function () {
            if (this.index < this.pageCount) {
                this.load({ page: this.index + 1 });
            }
        },

        load: function (data) {
            var self = this;

            data = data || {};
            var index = data.page || 1;
            self.index = index;

            var opts = self.options;
            var baseParams = opts.data;
            if (typeof (opts.data) === "function") {
                baseParams = opts.data(self);
            }

            var data = $.extend({ page: index, size: opts.size }, baseParams, data);

            opts.onBeforeLoad(data);

            $.ajax(opts.url, {
                data: data,
                dataType: 'json',
                type: 'GET',
                cache: false,
                success: function (data) {
                    var total = data.total;
                    $.proxy(self.render, self)(total);
                    opts.onLoad(data.rows);
                }
            });
        }
    };

    $.fn.pager = function (option, value) {
        var methodReturn;

        var $set = this.each(function () {
            var $this = $(this);
            var data = $this.data("pager");
            var options = typeof option === "object" && option;

            if (!data) $this.data("pager", (data = new Pager(this, options)));
            if (typeof option === "string") methodReturn = data[option](value);
        });

        return (methodReturn === undefined) ? $set : methodReturn;
    };

    $.fn.pager.defaults = {
        url: '#', //后台URL     
        autoLoad: true,
        size: 10, //每页记录数
        data: {},
        //showNumber:是否显示数字按钮,showText:是否显示页码
        showNumber: true,
        numberCount: 10,
        showText: true,
        onBeforeLoad: $.noop,
        onLoad: $.noop
    };
})(jQuery);