Admin = {};

Admin.Idea = (function ($) {
    var urlIdeaList = "/admin/idea/lists"; //GET参数: status (0:未审核，1:已审核, 2:已禁用, -1:全部); name (创意名称查询)
    var urlIdeaAudit = "/admin/idea/destroy/"; //GET id:用户Id
    var urlIdeaLock = "/admin/idea/lockIdea.json?id="; //GET参数：id(用户Id)；lock(0:禁用，1:开启)

    var initSearch = function () {
        $("[data-filter]").on("keydown", function (event) {            
            if (event.keyCode == 13) {
                var value = $.trim($(this).val());
                if (value.length > 0) {
                    $("#pageIdeaBt").pager("load", { name: value });
                } else {
                    $("#pageIdeaBt").pager("load");
                }
            }
        });
    };

    var initState = function () {
        $("[data-status]").on("click", function () {
            $("[data-status]").removeClass("active");
            $(this).addClass("active");
            var status = $(this).attr("data-status");
            ideaState = status;
            $("#pageIdeaBt").pager("load");
        });
    };

    var ideaState = 1;
    var initIdeaList = function () {
        $("#pageIdeaBt").pager({
            url: urlIdeaList,
            size: 10,
            data: function () {
                return { status: ideaState };
            },
            onLoad: function (data) {
                var temp = $("#tempIdeaItem").html();
                $("#gridIdea tbody").empty();
                $.each(data, function (i, n) {
                    n.operators = '';
                    if (n.status == 0) {
                        n.tool_action = "Admin.Idea.auditIdea(" + n.id +",2,'确认审核不通过？');";
                        n.tool_text = "不通过";
                    }
                    if (n.status == 1) {
                        n.tool_action = "Admin.Idea.auditIdea(" + n.id + ",0, '确认审核通过？');";
                        n.tool_text = "通过";
                        n.operators = '<a href="javascript:;" class="text-main" onclick=Admin.Idea.auditIdea(' + n.id + ',2,"确认审核不通过？");>不通过</a>'
                    }
                    if (n.status == 2) {
                        n.tool_action = "Admin.Idea.auditIdea(" + n.id + ",0, '确认审核通过？');";
                        n.tool_text = "通过";
                    }
                    if (n.status == 3) {
                        n.tool_action = "Admin.Idea.auditIdea(" + n.id + ",4,'确定暂停投放?');";
                        n.tool_text = "暂停投放";
                    }
                    if (n.type == "banner_text") {
                        n.content = '<a href="' +n.link + '" alt="'+ n.alt + '" target="_blank">' +n.alt+ '</a>';
                    } else {
                        n.content= '<a href="' +n.link + '" alt="'+ n.alt + '" target="_blank"> <img src="' + n.src + '"/> </a>';
                        n.type = n.type + "(" + n.size.width +'x'+ n.size.height +")";
                    }

                    $("#gridIdea tbody").append(App.template(temp, n));
                });
            }
        });
    };

    var auditIdea = function (id, status, s) {
        confirm(s, function () {
            App.ajaxAction(urlIdeaAudit + id, {
                data:{'status': status},
                message: {
                    success: "审核已通过"
                },
                success: function () {
                    $("#pageIdeaBt").pager("load");
                }
            });
        });
    };

    var lockIdea = function (id, lock) {
        var url = urlIdeaLock + id + "&lock=" + lock;
        var message = lock == 0 ? "确定要禁用此创意吗？" : "确定要开启此创意吗？";
        var successMsg = lock == 0 ? "创意禁用成功" : "创意开启成功";
        confirm(message, function () {
            App.ajaxAction(url, {
                message: {
                    success: successMsg
                },
                success: function () {
                    $("#pageIdeaBt").pager("load");
                }
            });
        });
    };

    return {
        init: function () {
            initIdeaList();
            initSearch();
            initState();
        },
        auditIdea: function (id, status, confirm) {
            auditIdea(id, status, confirm);
        },
        lockIdea: function (id) {
            lockIdea(id, 0);
        },
        unlockIdea: function (id) {
            lockIdea(id, 1);
        }
    };

})(jQuery);

jQuery(function () {
    Admin.Idea.init();
});
