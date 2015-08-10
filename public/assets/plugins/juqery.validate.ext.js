(function ($) {

    $.validator.setDefaults({
        onsubmit: false,
        ignore: ':hidden',
        errorElement: 'span',
        errorClass: 'help-inline',
        errorPlacement: function (error, element) {
            var place = element.closest('.field');
            place.append(error);
        },
        highlight: function (element, errorClass) {
            $(element).parents('.form-group').removeClass('success').addClass('error');
        },
        unhighlight: function (element, errorClass) {
            $(element).parents('.form-group').removeClass('error').addClass('success');
        }
    });

    $.validator.addMethod('regexp', function (value, element, param) {
        var rep = new RegExp(param);
        return this.optional(element) || rep.test(value);
    });

    $.validator.ext = {
        adapters: [],

        setOptions: function (element, options) {

            var $element = $(element);
            var rules = {};
            var messages = {};

            $.each(this.adapters, function () {
                var prefix = 'data-val-' + this.prefix;
                var message = $element.attr(prefix);

                if (message !== undefined) {
                    rules[this.name] = this.adapt(prefix, element, options.form);
                    messages[this.name] = message;
                }
            });

            options.rules[element.name] = rules;
            options.messages[element.name] = messages;
        },

        parse: function (selector) {
            $(selector).each(function () {
                var form = $(this);

                var options = {
                    rules: {},
                    messages: {},
                    form: this
                };

                form.find('[data-val="true"]').each(function () {
                    $.validator.ext.setOptions(this, options);
                });

                form.validate(options);
            });
        }
    };

    var adapters = $.validator.ext.adapters;

    adapters.add = function (adapterName, prefixName, adapt) {
        this.push({ name: adapterName, prefix: prefixName, adapt: adapt });
        return this;
    };

    adapters.add('required', 'required', function () {
        return true;
    });

    adapters.add('remote', 'remote', function (prefix, element, form) {
        var $element = $(element);
        return {
            url: $element.attr(prefix + '-url'),
            data: {
                key: function () { return $element.attr(prefix + '-key') || 0; }
            }
        };
    });

    //小数
    adapters.add('number', 'number', function () {
        return true;
    });

    //整数
    adapters.add('digits', 'digits', function () {
        return true;
    });

    adapters.add('min', 'min', function (prefix, element, form) {
        return $(element).attr(prefix + '-value');
    })

    adapters.add('minlength', "minlength", function (prefix, element, form) {
        return $(element).attr(prefix + '-value');
    });

    adapters.add('regexp', 'regexp', function (prefix, element, form) {
        return $(element).attr(prefix + '-rule');
    });

    //一致性检查
    adapters.add("equalTo", "equalto", function (prefix, element, form) {
        var other = $(element).attr(prefix + '-other');
        return $(form).find('input[name="' + other + '"]:eq(0)');
    });

}(jQuery));
