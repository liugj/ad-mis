App.Report = (function ($) {
    //url定义
    var urlPlanList = "/plan/lists"; //GET, Json, 左侧2级列表数据
    var urlIdeaList = "/idea/listIdea.json"; //GET, Json, 创意列表数据
    var urlReportList = "/report/lists"; //GET, Json, 参数（type:plan/unlt/idea;typeId:计划/单元/创意ID; tag:查看html的data-tag值;daterange:日期范围），报表数据

    var oUrlReportList = "";
    var initPlanList = function () {
        $("#listAdv").on("click", "li.adv-item a", function (event) {
            event.preventDefault();
            var $item = $(this).parent();
            $("#listAdv .active").removeClass("active");
            $item.addClass("active");

            var id = $item.attr("data-id");
            var type = $item.data("type");
            var text = $item.data("text");

            $("#viewInfo .doc-h3").text(text);

            oUrlReportList = urlReportList + "?" + type + "_id=" + id;

            $("#helpReport").hide();
            $("#tabReport").show();
            
            loadReportList();
        });
        $("#listAdv").on("click", ".adv-handle > .adv-item-icon", function (e) {
            e.stopPropagation();
            var sub = $(this).parent().next('ul');
            if (sub.length > 0) {
                sub.toggle();
                $(this).toggleClass('fold');
            }            
        });
    };

    var tpPlanItem = '<li class="adv-item" data-id="{id}"><a class="adv-handle" href="javascript:;"> {name}</a></li>';
    function renderPlanItem(item) {
        var $item = $(App.template(tpPlanItem, item));
        $item.find(".adv-handle").prepend('<i class="fa adv-item-icon"></i>');
        if (!item.open) {
            $item.prepend('<span class="adv-state"><i class="fa fa-lock"></i></span>');
        }
        return $item;
    }

    var loadPlanList = function () {
        $("#listAdv").empty().append('<li class="adv-item"><img src="/assets/img/ajax-loading.gif" /></li>');
        $.ajax({
            dataType: "json",
            url: urlPlanList,
            success: function (data) {
                $("#listAdv").empty();
                if (data.length == 0) {
                    $("#listAdv").append('<li class="adv-item"><div class="alert alert-blue">暂无广告计划</div></li>');
                    return;
                }

                $.each(data, function (i, n) {
                    var $item = renderPlanItem(n);
                    $item.data("type", "plan");
                    $item.data("text", n.name);
                    $("#listAdv").append($item);

                    if (n.ideas && n.ideas.length > 0) {
                        var $parent = $('<ul class="adv-list"></ul>');
                        $item.append($parent);

                        $.each(n.ideas, function (j, c) {
                            var $idea = renderPlanItem(c);
                            $idea.data("type", "idea");
                            $idea.data("text", n.name + " / " + c.name);
                            $parent.append($idea);
                        });
                    }
                });
            }
        });
    };

    var initFields = function () {
        $("#query-form input[name='date']").datetimepicker({ allowBlank: false, value: new Date().dateFormat('Y-m-d') });
        $("#query-form select[name='consumable_type']").select2({ minimumResultsForSearch: -1 });
        $("#query-form").submit(function (e) {
            e.preventDefault();
            loadReportList();
        });
    };

    var tpReportItem = $("#tempDataItem").html();
    var loadReportList = function () {
        $.get(oUrlReportList, $('#query-form').serialize(), function (data) {
            $("#gridData tbody").replaceWith('<tbody></tbody>');
            $.each(data.rows, function (i, n) {
                $("#gridData tbody").append(App.template(tpReportItem, n));
            });
        });
    };

    return {
        init: function () {
            initPlanList();
            loadPlanList();
            initFields();
        }
    };
})(jQuery);

jQuery(function () {
    App.Report.init();
});
