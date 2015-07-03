Admin = {};

Admin.Idea = (function ($) {
    var urlIdeaList = "/admin/idea/listIdea.json"; //GET参数: state (0:未审核，1:已审核, 2:已禁用, -1:全部); name (创意名称查询)
    var urlIdeaAudit = "/admin/idea/auditIdea.json?id="; //GET id:用户Id
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
        $("[data-state]").on("click", function () {
            $("[data-state]").removeClass("active");
            $(this).addClass("active");
            var state = $(this).attr("data-state");
            ideaState = state;
            $("#pageIdeaBt").pager("load");
        });
    };

    var ideaState = 0;
    var initIdeaList = function () {
        $("#pageIdeaBt").pager({
            url: urlIdeaList,
            size: 10,
            data: function () {
                return { state: ideaState };
            },
            onLoad: function (data) {
                var temp = $("#tempIdeaItem").html();
                $("#gridIdea tbody").empty();
                $.each(data, function (i, n) {
                    if (n.state == 0) {
                        n.tool_action = "Admin.Idea.auditIdea(" + n.id + ");";
                        n.tool_text = "审核";
                    }
                    if (n.state == 1) {
                        n.tool_action = "Admin.Idea.lockIdea(" + n.id + ");";
                        n.tool_text = "禁用";
                    }
                    if (n.state == 2) {
                        n.tool_action = "Admin.Idea.unlockIdea(" + n.id + ");";
                        n.tool_text = "开启";
                    }
                    if (n.idea_type == "banner_text") {
                        n.content = n.text;
                    } else {
                        n.content = '<a href="' + n.img + '" target="_blank" class="text-main"><i class="fa fa-photo"></i> 查看图片</a>';
                    }

                    $("#gridIdea tbody").append(App.template(temp, n));
                });
            }
        });
    };

    var auditIdea = function (id) {
        confirm("确定要审核通过吗？", function () {
            App.ajaxAction(urlIdeaAudit + id, {
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
        auditIdea: function (id) {
            auditIdea(id);
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