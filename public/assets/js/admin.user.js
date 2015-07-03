Admin = {};

Admin.User = (function ($) {
    var urlUserList = "/admin/user/listUser.json"; //GET参数: state (0:未审核，1:已审核, 2:已禁用, -1:全部); account (用户名查询)
    var urlUserAudit = "/admin/user/auditUser.json?id="; //GET id:用户Id
    var urlUserDelete = "/admin/user/deleteUser.json?id=";
    var urlUserLock = "/admin/user/lockUser.json?id="; //GET参数：id(用户Id)；lock(0:禁用，1:开启)

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

    var initState = function () {
        $("[data-state]").on("click", function () {
            $("[data-state]").removeClass("active");
            $(this).addClass("active");
            var state = $(this).attr("data-state");
            userState = state;
            $("#pageUserBt").pager("load");
        });
    };

    var userState = 0;
    var initUserList = function () {
        $("#pageUserBt").pager({
            url: urlUserList,
            size: 10,
            data: function () {
                return { state: userState };
            },
            onLoad: function (data) {
                var temp = $("#tempUserItem").html();
                $("#gridUser tbody").empty();
                $.each(data, function (i, n) {
                    if (n.state == 0) {
                        n.tool_action = "Admin.User.auditUser(" + n.id + ");";
                        n.tool_text = "审核";
                    }
                    if (n.state == 1) {
                        n.tool_action = "Admin.User.lockUser(" + n.id + ");";
                        n.tool_text = "禁用";
                    }
                    if (n.state == 2) {
                        n.tool_action = "Admin.User.unlockUser(" + n.id + ");";
                        n.tool_text = "开启";
                    }
                    $("#gridUser tbody").append(App.template(temp, n));
                });
            }
        });
    };

    var auditUser = function (id) {
        confirm("确定要审核通过吗？", function () {
            App.ajaxAction(urlUserAudit + id, {
                message: {
                    success: "审核已通过"
                },
                success: function () {
                    $("#pageUserBt").pager("load");
                }
            });
        });
    };

    var deleteUser = function (id) {
        confirm("确定要删除此用户吗？", function () {
            App.ajaxDelete(urlUserDelete + id, {
                success: function () {
                    $("#pageUserBt").pager("load");
                }
            });
        });
    };

    var lockUser = function (id, lock) {
        var url = urlUserAudit + id + "&lock=" + lock;
        var message = lock == 0 ? "确定要禁用此用户吗？" : "确定要开启此用户吗？";
        var successMsg = lock == 0 ? "用户禁用成功" : "用户开启成功";
        confirm(message, function () {
            App.ajaxAction(url, {
                message: {
                    success: successMsg
                },
                success: function () {
                    $("#pageUserBt").pager("load");
                }
            });
        });
    };

    return {
        init: function () {
            initUserList();
            initSearch();
            initState();
        },
        auditUser: function (id) {
            auditUser(id);
        },
        deleteUser: function (id) {
            deleteUser(id);
        },
        lockUser: function (id) {
            lockUser(id, 0);
        },
        unlockUser: function (id) {
            lockUser(id, 1);
        }
    };

})(jQuery);

jQuery(function () {
    Admin.User.init();
});