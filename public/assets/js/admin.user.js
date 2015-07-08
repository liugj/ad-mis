Admin = {};

Admin.User = (function ($) {
    var urlUserList = "/admin/user/lists"; //GET参数: status (0:未审核，1:已审核, 2:已禁用, -1:全部); account (用户名查询)
    var urlUserAudit = "/admin/user/auditUser.json?id="; //GET id:用户Id
    var urlUserRecharge = "/admin/recharge/create?user_id="; //GET id:用户Id
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
        $("[data-status]").on("click", function () {
            $("[data-status]").removeClass("active");
            $(this).addClass("active");
            var status = $(this).attr("data-status");
            userState = status;
            $("#pageUserBt").pager("load");
        });
    };

    var userState = 0;
    var initUserList = function () {
        $("#pageUserBt").pager({
            url: urlUserList,
            size: 10,
            data: function () {
                return { status: userState };
            },
            onLoad: function (data) {
                var temp = $("#tempUserItem").html();
                $("#gridUser tbody").empty();
                $.each(data, function (i, n) {
                    n.recharge_button= '';
                    if (n.status == 0) {
                        n.tool_action = "Admin.User.auditUser(" + n.id + ");";
                        n.tool_text = "禁用";
                        n.recharge_button= '<a href="javascript:;" class="text-main" onclick="Admin.User.rechargeUser(' + n.id+ ');">充值</a>';
                    }
                    if (n.status == 1) {
                        n.tool_action = "Admin.User.lockUser(" + n.id + ");";
                        n.tool_text = "审核";
                    }
                    if (n.status == 2) {
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
    var rechargeUser = function (id) {
        $("#dialogBalance").dialog("open", {
            url: urlUserRecharge + id,
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
        rechargeUser: function (id) {
            rechargeUser(id);
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
