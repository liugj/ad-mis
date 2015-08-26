Admin = {};

Admin.Balance = (function ($) {
    var urlUserList = "/admin/balance/listBalance.json"; //GET 参数: account (用户名查询)
    var urlBalanceEdit = "/admin/balance/editBalance.html?id=";

    var initSearch = function () {
        $("[data-filter]").on("keydown", function (event) {            
            if (event.keyCode == 13) {
                var value = $.trim($(this).val());
                if (value.length > 0) {
                    $("#pageUserBt").pager("load", { account: value });
                } else {
                    $("#pageUserBt").pager("load");
                }
            }
        });
    };


    var initUserList = function () {
        $("#pageUserBt").pager({
            url: urlUserList,
            size: 10,
            onLoad: function (data) {
                var temp = $("#tempUserItem").html();
                $("#gridUser tbody").empty();
                $.each(data, function (i, n) {
                    $("#gridUser tbody").append(App.template(temp, n));
                });
            }
        });
    };

    var initDialog = function () {
        $("#dialogBalance").dialog({ width: "400px" });
    };

    var editBalance = function (id) {
        $("#dialogBalance").dialog("open", {
            url: urlBalanceEdit + id,
            success: function () {
                $("#formBalance [data-inputmask]").inputmask();
                $("#formBalance").ajaxFormExt({
                    success: function () {
                        $("#dialogBalance").dialog("close");
                        $("#pageUserBt").pager("load");
                    }
                });
            }
        });
    };

    return {
        init: function () {
            initUserList();
            initSearch();
            initDialog();
        },
        editBalance: function (id) {
            editBalance(id);
        }
    };

})(jQuery);

jQuery(function () {
    Admin.Balance.init();
});