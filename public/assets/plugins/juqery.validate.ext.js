(function ($) {

    $.extend($.validator.defaults, {
        onsubmit: false,
        ignore: ":hidden",
        errorElement: "span",
        errorClass: "help-inline",
        errorPlacement: function (error, element) {
            var place = element.parent();
            error.appendTo(place);
        },
        highlight: function (element, errorClass) {
            $(element).parents(".form-group").removeClass("check-success").addClass("check-error");
        },
        unhighlight: function (element, errorClass) {
            $(element).parents(".form-group").removeClass("check-error").addClass("check-success");
        }
    });

    $.validator.addMethod("regexp", function (value, element, param) {        
        var rep = new RegExp(param);
        return this.optional(element) || rep.test(value);
    });

    console.dir($.validate);

	$.validator.ext = {
		adapters: [],

		setOptions: function (element, options) {

		    var $element = $(element);
		    var rules = {};
		    var messages = {};		    

			$.each(this.adapters, function () {				
			    var prefix = "data-val-" + this.prefix;
				var message = $element.attr(prefix);
				var paramValues = {};

				if (message !== undefined) {
					prefix += "-";

					$.each(this.params, function () {
						paramValues[this] = $element.attr(prefix + this);
					});
					
					rules[this.name] = this.adapt(paramValues, options.form);
					messages[this.name] = message;
				}
			});

			options.rules[element.name] = rules;
			options.messages[element.name] = messages;
		},

		parse: function (selector) {
			var $forms = $(selector);

			$forms.each(function () {
			    var $form = $(this);

				var options = {
					rules: {},
					messages: {},
					form: $form
				};

				$form.find(":input[data-val='true']").each(function () {
				    $.validator.ext.setOptions(this, options);
				});

				$form.validate(options);
			});			
		}
	};

	var adapters = $.validator.ext.adapters;

	adapters.add = function (adapterName, prefixName, params, fn) {
	    if (!fn) {
	        fn = params;
	        params = [];
	    }
	    this.push({ name: adapterName, prefix: prefixName, params: params, adapt: fn });
	    return this;
	};

	adapters.add("required", "required", function () {
	    return true;
	});

	adapters.add("number", "number", function () {
	    return true;
	});

	adapters.add("remote", "remote", ["url", "additionalfields"], function (params) {
	    return params;
	});

	adapters.add("min", "min", ["min"], function (params) {
	    return params.min;
	});

	adapters.add("range", "range", ["min", "max"], function (params) {
	    return [params.min, params.max];
	});

	adapters.add("equalTo", "equalto", ["other"], function (params, form) {
	    var other = params.other.replace('*.', '');
	    return form.find('input[name="' + other + '"]:eq(0)');
	});

	adapters.add("email", "email", function () {
	    return true;
	});

	adapters.add("minlength", "minlength", ["value"], function (params) {
	    return params.value;
	});

	adapters.add("regexp", "regexp", ["rule"], function (params, form) {
	    return params.rule;
	});

}(jQuery));