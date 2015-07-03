App.Plan = (function ($) {
    //url定义
    var urlPlanList = "/plan/lists"; //GET, Json, 左侧2级列表数据
    var urlPlanEdit = "/plan/edit/"; //GET, Html, 计划编辑表单
    var urlPlanCreate = "/plan/create/"; //GET, Html, 计划编辑表单
    var urlPlanDelete = "/plan/deletePlan.json?id="; //POST, Json，计划删除动作
    var urlPlanView = "/plan/show/"; //GET, Html, 计划详情

    var urlUnitEdit = "/idea/edit/"; //GET, Html, 单元编辑表单, 如果是添加操作，还会传人planId参数
    var urlUnitCreate = "/idea/create/"; //GET, Html, 单元编辑表单, 如果是添加操作，还会传人planId参数
    var urlUnitView = "/idea/show/"; //GET, Html, 单元详情
    var urlUnitDelete = "/unit/deleteUnit.json?id="; //POST, Json，计划删除动作

    var urlIdeaList = "/idea/listIdea.json?unitId="; //GET, Json, 创意列表数据, unitId:单元ID
    var urlIdeaView = "/idea/viewIdea.html?id="; //GET, Html, 创意详情
    var urlIdeaEdit = "/idea/editIdea.html?id="; //GET, Html, 创意编辑表单, 如果是添加操作，还会传人unitId参数
    var urlIdeaDelete = "/idea/deleteIdea.json?id="; //POST, Json，计划删除动作

    var urlUpload = "/idea/upload"; //POST, File, 原始图片上传
    var urlCrop = "/idea/crop"; //POST, File, 裁剪后图片上传 传入参数：imgUrl，imgInitW，imgInitH，imgW，imgH，imgY1，imgX1，cropH，cropW

    var initPlanList = function () {
        var oldTitle = $("#viewInfo .doc-h3").html();
        var oldView = $("#viewInfo .view").html();
        $("#viewInfo .doc-h3").data("title", oldTitle);
        $("#viewInfo .view").data("view", oldView);

        $("#listAdv").on("click", "li.adv-item a", function (event) {
            event.preventDefault();
            var $item = $(this).parent();
            $("#listAdv .active").removeClass("active");
            $item.addClass("active");

            var id = $item.attr("data-id");
            var type = $item.data("type");
            var text = $item.data("text");

            $("#viewInfo .doc-h3").text(text);
            if (type == "plan") {
                $("#viewInfo .view").empty().append('<img src="/assets/img/ajax-loading.gif" />').load(urlPlanView + id);
            }
            if (type == "idea") {
                $("#viewInfo .view").empty().append('<img src="/assets/img/ajax-loading.gif" />').load(urlUnitView + id, function () {
                    $("#timerange").weekdaypicker({ editable: false });
                });              
            }
        });
    };

    function resetView() {
        var oldTitle = $("#viewInfo .doc-h3").data("title");
        var oldView = $("#viewInfo .view").data("view");
        $("#viewInfo .doc-h3").text(oldTitle);
        $("#viewInfo .view").html(oldView);
    }

    var initDialog = function () {
        $("#dialogPlan").dialog({ width: "400px" });
        $("#dialogUnit").dialog({ width: "900px" });
        $("#dialogIdea").dialog({ width: "600px" });
        $("#dialogImage").dialog({ width: "auto", zIndex: 12 });
    };

    var tpPlanItem = '<li class="adv-item" data-id="{id}"><a class="adv-handle" href="javascript:;"> {name}</a></li>';
    function renderPlanItem(item) {
        var $item = $(App.template(tpPlanItem, item));
        $item.find(".adv-handle").prepend('<i class="fa adv-item-icon"></i>');
        if (item.state) {
            $item.prepend('<span class="adv-state">' + item.state + '</span>');
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

    var editPlan = function (id) {
        $("#dialogPlan").dialog("open", {
            url: id>0 ? urlPlanEdit + id:urlPlanCreate,
            success: function () {
                $("#formPlan [data-inputmask]").inputmask();
                $("#formPlan").ajaxFormExt({
                    success: function () {
                        $("#dialogPlan").dialog("close");
                        loadPlanList();
                        $("#viewInfo .view").empty().append('<img src="/assets/img/ajax-loading.gif" />').load(urlPlanView + id);
                    }
                });
            }
        });
    };

    var deletePlan = function (id) {
        confirm("确定要删除这个计划吗？", function () {
            App.ajaxDelete(urlPlanDelete + id, {
                success: function () {
                    loadPlanList();
                    resetView();
                }
            });
        });
    }

    var editUnit = function (id, planId) {
        if (id) {
            var url = urlUnitEdit + id;
            if (planId) {
                url += "?plan_id=" + planId;
            }
        }else{
            var url =  urlUnitCreate + '?plan_id=' +planId;
        }

        $("#dialogUnit").dialog("open", {
            url: url,
            success: function () {
                $("#formUnit [data-inputmask]").inputmask();
                $("#formUnit .select2").select2({ minimumResultsForSearch: 20 });
                $("#formUnit [name='daterange']").daterangepicker();
                $("#formUnit [name='timerange']").weekdaypicker();

                setIdeaType();
                $("#type").change(function (e) {
                    setIdeaType();
                });

                changePlaceholder();
                $("#click_action_id").on("change", function (e) {
                    changePlaceholder();
                });

                $("#formUnit").ajaxFormExt({
                    success: function () {
                        $("#dialogUnit").dialog("close");
                        loadPlanList();
                        $("#viewInfo .view").empty().append('<img src="/assets/img/ajax-loading.gif" />').load(urlUnitView + id);
                    }
                });
            }
        });
    }

    var deleteUnit = function (id) {
        confirm("确定要删除这个单元吗？", function () {
            App.ajaxDelete(urlUnitDelete + id, {
                success: function () {
                    loadPlanList();
                    resetView();                    
                }
            });
        });
    }

    function setIdeaType() {
        var type = $("#type").select2("val");
        $("#ideaSize div:not(." + type + ")").hide();

        var showDiv = $("#ideaSize div." + type).show();
        showDiv.find(":radio:first").prop("checked", true);

        if (type == "banner_text") {
            $(".form-group.text").show();
            $(".form-group.img").hide();
        } else {
            $(".form-group.text").hide();
            $(".form-group.img").show();
        }
    }

    function changePlaceholder() {
        var opt = $("#click_action_id option:selected");
        if (opt.length > 0) {
            var placeholder = opt.attr("data-placeholder");
            $("#link").attr("placeholder", placeholder);
        }
    }

    var cropper;
    var initCropper = function () {
        cropper = new Croppic("croppic", {
            uploadUrl: urlUpload,
            cropUrl: urlCrop,
            zoomFactor: 10,
            outputUrlId: "imgUpload",
            doubleZoomControls: false,
            onAfterImgCrop: function () {
                $("#dialogImage").dialog("close");
            }
        });
    }

    var upload = function () {
        var size_id = $("#setp-1 input[name='size_id']:checked").parent().text();
        var ss = size_id.split("x");
        var width = parseInt(ss[0]);
        var height = parseInt(ss[1]);
        $("#croppic").height(height).width(width);
        cropper.croppedImg = null;
        cropper.reset();
        $("#dialogImage").dialog("open");
    }

    function gotoSetp(index) {
        if (index == 1) {
            $('#setp-1').show();
            $('#setp-2').hide();
        }

        if (index == 2) {
            $('#setp-2').show();
            $('#setp-1').hide();
        }
    }

    return {
        init: function () {
            initPlanList();
            loadPlanList();
            initDialog();
            initCropper();
        },

        editPlan: function (id) {
            editPlan(id);
        },
        deletePlan: function (id) {
            deletePlan(id);
        },
        editUnit: function (id, planId) {
            editUnit(id, planId);
        },
        deleteUnit: function (id) {
            deleteUnit(id);
        },
        editIdea: function (id) {
            editIdea(id);
        },
        deleteIdea: function (id) {
            deleteIdea(id);
        },
        upload: function () {
            upload();
        },
        gotoSetp: gotoSetp
    };
})(jQuery);

jQuery(function () {
    App.Plan.init();
});
