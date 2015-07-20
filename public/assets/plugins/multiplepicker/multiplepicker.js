(function ($) {
    var MultiplePicker = function (element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn.multiplepicker.defaults, options);
        
        render(this.element);
        var wrap = this.element.parent();
        var input = wrap.find('div.input');
        var choices = wrap.find('.choices');
        var leftEl = wrap.find('.choices-left');
        var rightEl = wrap.find('.choices-right');

        $.extend(this, {
            input: input,
            leftEl: leftEl,
            rightEl: rightEl,
            values: [],
            data: [],
            open: false
        });

        var self = this;

        input.on('click.picker', function (e) {
            e.stopPropagation();
            if (self.open) {
                choices.hide();
                self.open = false;
            } else {
                choices.show();
                self.open = true;
                $(document).one('click.picker', function () {
                    choices.hide();
                    self.open = false;
                });
            }
        });

        choices.on('click.picker', '.close', function (e) {
            choices.hide();
            self.open = false;
        });

        choices.on('click.picker', function (e) { e.stopPropagation();});

        leftEl.on('click.picker', '.item', function () {
            var item = $(this);
            leftEl.find('.item.active').removeClass('active');
            item.addClass('active');

            var checkbox = item.find('input:checkbox');
            var dataItem = checkbox.data('dataItem');
            var checked = checkbox.prop('checked');

            rightEl.empty();
            
            $.each(self.data, function (i, n) {
                if (n.parent == dataItem.id) {
                    var cItem = $('<div class="item"><input value=' + n.id + ' type="checkbox"/> ' + n.name + '</div>');
                    var cCheckbox = cItem.find('input:checkbox');
                    cCheckbox.data('dataItem', n);

                    if (checked) {
                        cCheckbox.prop('checked', true);
                    } else {
                        $.each(self.values, function (j, k) {
                            if (k.id == n.id) {
                                cCheckbox.prop('checked', true);
                                return false;
                            }
                        });
                    }
                    rightEl.append(cItem);
                }
            });
        });

        rightEl.on('click.picker', ':checkbox', function () {
            var total = rightEl.find('input:checkbox').length;
            var count = rightEl.find('input:checked').length;

            var dataItem = $(this).data('dataItem');
            var checkbox = leftEl.find('input:checkbox[value="' + dataItem.pid + '"]');

            if (count == 0) {
                checkbox.prop('checked', false);
                checkbox.prop('indeterminate', false); 
            }

            if (count > 0 && total > count) {
                checkbox.prop('indeterminate', true);
                checkbox.prop('checked', false);
            }

            if (total == count) {
                checkbox.prop('indeterminate', false);
                checkbox.prop('checked', true);
            }
            
            var odataItem = checkbox.data('dataItem');
            var ochecked = checkbox.prop('checked');
            self.checkValue(odataItem, ochecked);
        });

        leftEl.on('change.picker', ':checkbox', function () {
            var dataItem = $(this).data('dataItem');
            var checked = $(this).prop('checked');
            rightEl.find('input:checkbox').prop('checked', checked);
            self.checkValue(dataItem, checked);
        });
    };

    function render(element) {
        element.wrap('<div class="multiplepicker"></div>');
        element.after('<div class="input"></div><div class="choices"><div class="choices-head"><span class="close"></span></div><div class="choices-body"><div class="choices-left"></div><div class="choices-right"></div></div></div>');
    }

    function initData(element, data) {
        var wrap = element.parent();
        var left = wrap.find('.choices-left');
        $.each(data, function (i, n) {
            if (n.parent == 0) {
                var item = $('<div class="item"><input value=' + n.id + ' type="checkbox"/> ' + n.name + '</div>');
                item.find('input:checkbox').data('dataItem', n);
                left.append(item);
            }            
        });
    }

    MultiplePicker.prototype = {
        constructor: MultiplePicker,
        setData: function (data) {
            this.data = data;
            initData(this.element, data);            
            this.initValue();
        },
        checkValue: function (dataItem, checked) {
            var self = this;
            var values = self.values;

            if (checked) {
                values = $.grep(values, function (n, i) {
                    return n.parent != dataItem.id;
                });
                values.push(dataItem);
            } else {
                values = $.grep(values, function (n, i) {
                    return !(n.id == dataItem.id || n.parent == dataItem.id);
                });

                self.rightEl.find('input:checked').each(function () {
                    var dd = $(this).data('dataItem');
                    values.push(dd);
                });
            }

            self.setValue(values);
        },
        initValue: function () {
            var self = this;

            var ids = self.element.val().split(',');
            var values = [];
            $.each(self.data, function (i, n) {
                $.each(ids, function (j, t) {
                    if (t == n.id) {
                        values.push(n);
                        return false;
                    }
                });
            });

            self.setValue(values);

            $.each(values, function (i, n) {
                self.leftEl.find(':checkbox[value="' + n.id + '"]').prop('checked', true);
                self.leftEl.find(':checkbox[value="' + n.parent + '"]').prop('indeterminate', true);
            });
        },
        setValue: function (values) {
            this.values = values;
            var ids = [], texts = [];
            $.each(this.values, function (i, n) {
                ids.push(n.id);
                texts.push(n.name);
            });
            this.element.val(ids.join(','));
            this.input.empty().text(texts.join(','));
        }
    };

    $.fn.multiplepicker = function (option, value) {
        var methodReturn;

        var $set = this.each(function () {
            var $this = $(this);
            var data = $this.data("multiplepicker");
            var options = typeof option === "object" && option;

            if (!data) $this.data("multiplepicker", (data = new MultiplePicker(this, options)));
            if (typeof option === "string") methodReturn = data[option](value);
        });

        return (methodReturn === undefined) ? $set : methodReturn;
    };

    $.fn.multiplepicker.defaults = {
        editable: true
    };
})(jQuery);
