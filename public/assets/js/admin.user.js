Admin = {};

Admin.User = (function ($) {
    var urlUserList = "/admin/user/lists"; //GET参数: status (0:未审核，1:已审核, 2:已禁用, -1:全部); account (用户名查询)
    var urlUserAudit = "/admin/user/destroy/"; //GET id:用户Id
    var urlUserRecharge = "/admin/recharge/create?user_id="; //GET id:用户Id
    var urlUserDelete = "/admin/user/deleteUser.json?id=";
    var urlUserEdit = "/admin/user/";
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
     var initDialog = function () {
        $("#dialogUser").dialog({ width: "900px" });
    }

    var userState = 0;
    var initUserList = function () {
        var grants = window.Grants || [];
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
                        n.tool_action = "Admin.User.lockUser(" + n.id + ");";
                        n.tool_text = "禁用";
                        if ($.inArray('/admin/recharge/store', grants) != -1) {
                            n.recharge_button= '<a href="javascript:;" class="text-main" onclick="Admin.User.rechargeUser(' + n.id+ ');">充值</a>';
                        }
                    }
                    if (n.status == 3) {
                        n.tool_action = "Admin.User.unlockUser(" + n.id + ");";
                        n.tool_text = "启用";
                    }
                    $("#gridUser tbody").append(App.template(temp, n));
                });
            }
        });
    }
    var initRechargeList = function () {
        $("#pageRechargeBt").pager({
           // url: '/admin/user/recharge/10',
            size: 10,
            data: function () {
                return { format:'json' };
            },
            onLoad: function (data) {
                var temp = $("#tempRechargeItem").html();
                $("#gridRecharge tbody").empty();
                $.each(data, function (i, n) {
                    $("#gridRecharge tbody").append(App.template(temp, n));
                });
            }
        });
    };
    var initConsumeList = function () {
        $("#pageConsumeBt").pager({
           // url: '/admin/user/recharge/10',
            size: 10,
            data: function () {
                return { format:'json' };
            },
            onLoad: function (data) {
                var temp = $("#tempConsumeItem").html();
                $("#gridConsume tbody").empty();
                $.each(data, function (i, n) {
                    $("#gridConsume tbody").append(App.template(temp, n));
                });
            }
        });
    };
  var initReportList = function () {
        $("#pageReportBt").pager({
           // url: '/admin/user/recharge/10',
            size: 10,
            data: function () {
                return { format:'json' };
            },
            onLoad: function (data) {
                var temp = $("#tempReportItem").html();
                $("#gridReport tbody").empty();
                $.each(data, function (i, n) {
                    $("#gridReport tbody").append(App.template(temp, n));
                });
            }
        });
    };
    var editUser = function (id) {
        $("#dialogUser").dialog("open", {
            url:  urlUserEdit + (id > 0 ? 'edit/' +id : 'create' ),
            success: function () {
                $("#formUser [data-inputmask]").inputmask();
                $("#formUser").ajaxFormExt({
                    success: function () {
                        $("#dialogUser").dialog("close");
                        $("#pageUserBt").pager("load");
                    }
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
        var url = urlUserAudit + id + "?status=" + lock;
        var message = lock == 3 ? "确定要禁用此用户吗？" : "确定要启用此用户吗？";
        var successMsg = lock == 3 ? "用户禁用成功" : "用户启用成功";
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
            initDialog();
        },
        rechargeList: function(){
            initRechargeList();
        },
        reportList: function(){
            initReportList();
        },
        consumeList: function(){
             initConsumeList();
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
        editUser: function (id) {
            editUser(id);
        },
        lockUser: function (id) {
            lockUser(id, 3);
        },
        unlockUser: function (id) {
            lockUser(id, 0);
        }
    };

})(jQuery);

jQuery(function () {
    Admin.User.init();
});
