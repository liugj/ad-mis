App.Plan = (function ($) {
    //url定义
    var urlPlanList = "/plan/lists"; //GET, Json, 左侧2级列表数据
    var urlPlanEdit = "/plan/edit/"; //GET, Html, 计划编辑表单
    var urlPlanCreate = "/plan/create/"; //GET, Html, 计划编辑表单
    var urlPlanDelete = "/plan/destroy/"; //POST, Json，计划删除动作
    var urlPlanView = "/plan/show/"; //GET, Html, 计划详情

    var urlUnitEdit = "/idea/edit/"; //GET, Html, 单元编辑表单, 如果是添加操作，还会传人planId参数
    var urlUnitCreate = "/idea/create/"; //GET, Html, 单元编辑表单, 如果是添加操作，还会传人planId参数
    var urlUnitView = "/idea/show/"; //GET, Html, 单元详情
    var urlUnitDelete = "/idea/destroy/"; //POST, Json，计划删除动作
    var urlUnitPreview = "/idea/preview/"; //GET, Json, 预览

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
                loadPlanView(id);
            }
            if (type == "idea") {
                loadUnitView(id);
            }
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

    function loadPlanView(id) {
        $("#viewInfo .view").empty().append('<img src="/assets/img/ajax-loading.gif" />').load(urlPlanView + id);
    }

    function loadUnitView(id) {
        $("#viewInfo .view").empty().append('<img src="/assets/img/ajax-loading.gif" />').load(urlUnitView + id, function () {
            $("#timerange").weekdaypicker({ editable: false });
        });
    }

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
        $("#dialogPreview").dialog({ width: "600px" });
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
                $("#formPlan [name='daterange']").daterangepicker();
                $("#formPlan").ajaxFormExt({
                    success: function (response) {
                        $("#dialogPlan").dialog("close");
                        loadPlanList();
                        loadPlanView(response.id);
                    }
                });
            }
        });
    };

    var deletePlan = function (id, status) {
        confirm(status ==1 ?"确定要停止这个计划吗？": "确定要启动这个计划吗？", function () {
            App.ajaxDelete(urlPlanDelete + id, {
                data:{'status': status},
                success: function () {
                    loadPlanList();
                    resetView();
                }
            });
        });
    }
    var regionData = [];
    var editUnit = function (id, planId) {
        if (id) {
            var url = urlUnitEdit + id;
            if (planId) {
                url += "?plan_id=" + planId;
            }
        }else{
            var url =  urlUnitCreate + '?plan_id=' +planId;
        }
        var selectMinBid =  function (){
            $.get('/idea/bid',  { pay_type: $('#formUnit input:radio[name="pay_type"]:checked').val(),
                    type: $('#formUnit select[name=group]').val()+'_'+$('select[name=device]').val()
                    }, function(data){
                       if (data.minBid) {
                          tip = '最低出价金额' + data.minBid + "元";
                          $('#formUnit #bid-help').text(tip);
                          $('#formUnit #minBid').attr("value", data.minBid)
                        }
                    },'json'
                    );

        };

        $("#dialogUnit").dialog("open", {
            url: url,
            success: function () {
                $("#formUnit [data-inputmask]").inputmask();
                $("#formUnit .select2").select2({ minimumResultsForSearch: 20 });
                $("#formUnit [name='timerange']").weekdaypicker();
                $("#formUnit input:text[name='bid']").bind("click", selectMinBid);
                $("#formUnit input:radio[name='pay_type']").bind("click", selectMinBid);
                $("#formUnit select[name='type']").bind("change", selectMinBid);
                var region =   $("#formUnit [name='region']").multiplepicker();

                if (regionData.length ==0){
                    $.get('/region/lists', function(data){
                        regionData = data;
                        region.multiplepicker('setData', regionData);
                        }, 'json'); 
                }else{
                    region.multiplepicker('setData', regionData);
                }

                setIdeaType();
                selectMinBid();
                $("#group").change(function (e) {
                    setIdeaType();
                });
                $("#device").change(function (e) {
                    setIdeaType();
                });
                $("input[id='platform'").change(function (e) {
                    setIdeaType();
                });

                changePlaceholder();
                $("#click_action_id").on("change", function (e) {
                    changePlaceholder();
                });

                $("#formUnit").ajaxFormExt({
                    success: function (response) {
                        $("#dialogUnit").dialog("close");
                        loadPlanList();
                        loadUnitView(response.id);
                    },
                    beforeSubmit: function(){
                        var minBid = $('#formUnit #minBid').val();
                        if ($('#formUnit input:text[name="bid"]').val()*100 < minBid*100){
                          alert('最低出价金额必须大于'+minBid+"元");
                          return false;
                        }
                  } 
                });
                //$('#media').select2({
                //    multiple: true,
                //    ajax: {
                //        url: "/media/lists/3333",
                //        dataType: 'json',
                //        data: function (params) {
                //            return {
                //                q: params
                //            };
                //        },
                //        results: function (data) {
                //            return {
                //                results: data
                //            };
                //        }
                //    }
                //});

                changePlaceholder();
                $("#click_action_id").on("change", function (e) {
                    changePlaceholder();
                });

                $("#formUnit").ajaxFormExt({
                    success: function (response) {
                        $("#dialogUnit").dialog("close");
                        loadPlanList();
                        loadUnitView(response.id);
                    },
                    beforeSubmit: function(){
                        var minBid = $('#formUnit #minBid').val();
                        if ($('#formUnit input:text[name="bid"]').val()*100 < minBid*100){
                          alert('最低出价金额必须大于'+minBid+"元");
                          return false;
                        }
                  } 
                });
                $('#media').select2({
                    multiple: true,
                    ajax: {
                        url: "/media/lists",
                        dataType: 'json',
                        data: function (params) {
                            return {
                                device:  $('#device').val(),
                                q: params
                            };
                        },
                        results: function (data) {
                            return {
                                results: data
                            };
                        }
                    }
                });

                var mediaData = [];
                var mediaData_id = ($('#media').attr('data-id') || '').split(',');
                var mediaData_text = ($('#media').attr('data-text') || '').split(',');
                $.each(mediaData_id, function (i, id) {
                    if (id) {
                        mediaData.push({ id: id, text: mediaData_text[i] });
                    }                    
                });
                $('#media').data('select2').updateSelection(mediaData);
            }
        });
    }

    var deleteUnit = function (id, status) {
        confirm(status==4 ? "确定要停止这个广告创意吗？" : '确定要投放这个广告创意吗？', function () {
            App.ajaxDelete(urlUnitDelete + id, {
                data: {'status':status},
                success: function () {
                    loadPlanList();
                    resetView();                    
                }
            });
        });
    }

    function setIdeaType() {
        var type = $("#group").select2("val")+'_'+$("#device").select2("val")+'_' + $('#formUnit input:radio[name="platform"]:checked').val();
        $("#ideaSize div:not(." + type + ")").hide();

        var showDiv = $("#ideaSize div." + type).show();

        if (showDiv.find(":radio:checked").length){
           showDiv.find(":radio:checked").prop("checked", true);
        }else{
           showDiv.find(":radio:first").prop("checked", true);
        }

        if (type == "banner_text") {
            $(".form-group.text").show();
            $(".form-group.img").hide();
        } else {
            $(".form-group.text").hide();
            $(".form-group.img").show();
        }
        if ($('#formUnit input:radio[name="platform"]:checked').val() ==0){
            $(".form-group.platform").show();
        }else{
            $(".form-group.platform").hide();
            }
    }

    function changePlaceholder() {
        var opt = $("#click_action_id option:selected");
        if (opt.length > 0) {
            var placeholder = opt.attr("data-placeholder");
            $("#link").attr("placeholder", placeholder);
            $("#linkLabel").text(placeholder);
            var value = opt.attr("value");
            $(".form-group.link_text").hide();
            if (value==2) {
                 $(".form-group.link_text").show();
            }
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
        cropper.options.uploadData= {width:width, height:height};
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
    function preview(id) {
        $("#dialogPreview").dialog("open");
        var box = $("#dialogPreview .preview").empty();
        $.get(urlUnitPreview + id, function (data) {
            box.css("background-image", "url(/assets/img/" + data.type + ".jpg)");
            var ad = $();
            switch (data.type) {
                case "banner_iphone":
                    ad = $('<img src="' + data.value + '" />').css({ position: "absolute", top: "77px", left: "201px", width: "165px" });
                    break;
                case "banner_ipad":
                    ad = $('<img src="' + data.value + '" />').css({ position: "absolute", top: "48px", left: "169px", width: "225px" });
                    break;
                case "banner_android":
                    ad = $('<img src="' + data.value + '" />').css({ position: "absolute", top: "62px", left: "194px", width: "150px" });
                    break;
                case "banner_text":
                    ad = $('<p></p>').text(data.value).css({ position: "absolute", top: "78px", left: "206px", width: "155px", color: "#00c", fontSize: "12px", lineHeight: "16px" });
                    break;
                case "plaqueX_iphone":
                    ad = $('<img src="' + data.value + '" />').css({ position: "absolute", top: "144px", left: "190px", width: "188px", height: "115px" });
                    break;
                case "plaqueX_ipad":
                    ad = $('<img src="' + data.value + '" />').css({ position: "absolute", top: "134px", left: "180px", width: "198px", height: "125px" });
                    break;
                case "plaqueX_android":
                    ad = $('<img src="' + data.value + '" />').css({ position: "absolute", top: "150px", left: "160px", width: "210px", height: "100px" });
                    break;
                case "plaqueY_iphone":
                    ad = $('<img src="' + data.value + '" />').css({ position: "absolute", top: "100px", left: "211px", width: "145px", height: "200px" });
                    break;
                case "plaqueY_ipad":
                    ad = $('<img src="' + data.value + '" />').css({ position: "absolute", top: "100px", left: "200px", width: "165px", height: "200px" });
                    break;
                case "plaqueY_android":
                    ad = $('<img src="' + data.value + '" />').css({ position: "absolute", top: "100px", left: "204px", width: "130px", height: "190px" });
                    break;
            }

            box.append(ad);
        }, "json");
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
        deletePlan: function (id, status) {
            deletePlan(id, status);
        },
        editUnit: function (id, planId) {
            editUnit(id, planId);
        },
        deleteUnit: function (id, status) {
            deleteUnit(id, status);
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
        gotoSetp: gotoSetp, 
        preview: preview
    };
})(jQuery);

jQuery(function () {
    App.Plan.init();
});
