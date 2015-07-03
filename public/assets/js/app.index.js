App.Index = (function ($) {
    var urlLoadData = "/report/summary";

	var initFields = function () {
	    $("#query-form input[name='date']").datetimepicker({ allowBlank: false, value: new Date().dateFormat('Y-m-d') });
        $("#query-form").submit(function (e) {
            e.preventDefault();
            loadData();
        });
	};

	var tempDataItem = $('#tempDataItem').html();
	var loadData = function () {
	    $("#gridData tbody").empty();
	    $.get(urlLoadData, $('#query-form').serialize(), function (data) {
	        $.each(data.rows, function (i, n) {
	            $("#gridData tbody").append(App.template(tempDataItem, n));
	        });
	    });
	};

	return {
		init: function () {
			initFields();
			loadData();
		}
	};
})(jQuery);

jQuery(function () {
	App.Index.init();
});
