Admin = {};

Admin.Admin = (function ($) {
    var urlAdminList = "/admin/admin/listAdmin.json";
    var urlAdminEdit = "/admin/admin/editAdmin.html?id=";
    var urlAdminDelete = "/admin/admin/deleteAdmin.json?id=";

    var initSearch = function () {
        $("[data-filter]").on("keydown", function (event) {            
            if (event.keyCode == 13) {
                var value = $.trim($(this).val());
                if (value.length > 0) {
                    $("#pageAdminBt").pager("load", { name: value });
                } else {
                    $("#pageAdminBt").pager("load");
                }
            }
        });
    };

    var initAdminList = function () {
        $("#pageAdminBt").pager({
            url: urlAdminList,
            size: 20,
            onLoad: function (data) {
                var temp = $("#tempAdminItem").html();
                $("#gridAdmin tbody").empty();
                $.each(data, function (i, n) {
                    $("#gridAdmin tbody").append(App.template(temp, n));
                });
            }
        });
    };

    var editAdmin = function (id) {
        $("#dialogAdmin").dialog("open", {
            url: urlAdminEdit + id,
            success: function () {
                $("#formAdmin").ajaxFormExt({
                    success: function () {
                        $("#dialogAdmin").dialog("close");
                        $("#pageAdminBt").pager("load");
                    }
                });
            }
        });
    };

    var deleteAdmin = function (id) {
        confirm("确定要删除此管理员吗？", function () {
            App.ajaxDelete(urlAdminDelete + id, {
                success: function () {
                    $("#pageAdminBt").pager("load");
                }
            });
        });
    };

    var initDialog = function () {
        $("#dialogAdmin").dialog({ width: "400px" });
    };

    return {
        init: function () {
            initAdminList();
            initSearch();
            initDialog();
        },
        editAdmin: function (id) {
            editAdmin(id);
        },
        deleteAdmin: function (id) {
            deleteAdmin(id);
        }
    };

})(jQuery);

jQuery(function () {
    Admin.Admin.init();
});