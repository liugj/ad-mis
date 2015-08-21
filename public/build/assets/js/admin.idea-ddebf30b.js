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
                        n.tool_action = "Admin.Idea.audit(" + n.id +",2);";
                        n.tool_text = "不通过";
                    }
                    if (n.status == 1) {
                        n.tool_action = "Admin.Idea.audit(" + n.id + ",0);";
                        n.tool_text = "审核";
                    }
                    if (n.status == 2) {
                        n.tool_action = "Admin.Idea.audit(" + n.id + ",0);";
                        n.tool_text = "通过";
                    }
                    if (n.status == 3) {
                        n.tool_action = "Admin.Idea.auditIdea(" + n.id + ",4,'确定暂停投放?');";
                        n.tool_text = "暂停投放";
                        n.operators = '<a href="javascript:;" class="text-main" onclick="' + 'Admin.Idea.audit(' + n.id + ',3);">设置</a>';
                        n.operators += ' <a href="/admin/idea/report/'+n.id+'" class="text-main">报表</a>';
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
    var setStatus = function() {
        if ($('input:radio[name="status"]:checked').length ==0 ||$('input:radio[name="status"]:checked').val() ==0){
            $(".form-group.pass").show();
            $(".form-group.refuse").hide();
        }else{
            $(".form-group.pass").hide();
            $(".form-group.refuse").show();
        }
    }
    var changeSubCategory = function(param){
        var category_id = $('select[name="'+param.data.parent+'"]');
        var category_sub_id = $('select[name="'+param.data.id+'"]');
        $.ajax({
            url : '/categories/'+category_id.val(),
            success : function(data){
              category_sub_id.empty();
              category_sub_id.append("<option value='0'>请选择子级分类</option>");
              $.each(data, function (i, item){
                  if (item.id  == category_sub_id.attr('data-id')) {
                    category_sub_id.append("<option value='" + item.id + "' selected='selected'>" + item.name + "</option>");
                  }else{
                    category_sub_id.append("<option value='" + item.id + "'>" + item.name + "</option>");
                  }
              });
                category_sub_id.select2({ minimumResultsForSearch: -1 });
           },
           async: false ,
           dataType: 'json'
        });
    }
    var addFlow = function (){
        $('#dialogFlow').dialog('open', {
            url: '/admin/flow/create',
            success: function(){
                $("#formFlow").ajaxFormExt({
                    success: function (res) {
                        $("#dialogFlow").dialog("close");
                        $('#flow').append("<option value='" + res.data.id + "'>" + res.data.name + " ["+ res.data.min + "-" + res.data.max + "]</option>");
                    }
                });
            }
        });
    }
   // var setFlow = function( ){

    //}
    var audit = function (id, status) {
        $("#dialogIdeaAudit").dialog("open", {
            url:  '/admin/idea/edit/' + id +'?status='+status,
            success: function () {
                $('#formIdeaAudit .select2').select2({ minimumResultsForSearch: -1 });
                $("#formIdeaAudit input:radio[name='status']").bind("change", setStatus);
                $("#formIdeaAudit select[name='category_id']").bind("change", {parent: 'category_id', id:'category_sub_id'}, changeSubCategory);
                $("#formIdeaAudit select[name='category_sub_id']").bind("change", {parent: 'category_sub_id', id:'category_grandson_id'}, changeSubCategory);
                $("#formIdeaAudit").ajaxFormExt({
                    success: function () {
                        $("#dialogIdeaAudit").dialog("close");
                        $("#pageIdeaBt").pager("load");
                    }
                });
                changeSubCategory({data:{parent:'category_id', id:'category_sub_id'}});
                setStatus();
                changeSubCategory({data:{parent:'category_sub_id', id:'category_grandson_id'}});
               // setFlow();
            }
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
    var initDialog = function () {
        $("#dialogIdeaAudit").dialog({ width: "960px" });
        $("#dialogFlow").dialog({ width: "300px" });
    };
    var addFlowPrice = function () {
        var temp = $("#tempFlowPriceItem").html();
       // $('.form-group.flow.price').remove();
        if ($('#flow').val()){
            $.each($('#flow').val(),function(i, n){
                if ($('.flow'+n).length==0) {
                    $('.append').after(App.template(temp, {
                        flow_name: $('#flow option[value='+ n +']').text(),
                        id: n
                        })
                    );
                }
            });
        }
    }
    var delFlowPrice = function (id){
        $('.flow'+id).remove();
    }
    var initReportList = function () {
        var options ={
            url: '/admin/idea/report/34',
            size: 10,
            data: function () {
                return {
                     date:$('input[name="date"]').val(),
                     consumable_type:$('select[name="consumable_type"]').val(),
                     format:'json'
                  };
            },
            onLoad: function (data) {
                var temp = $("#tempReportItem").html();
                $("#gridReport tbody").empty();
                $.each(data, function (i, n) {
                    $("#gridReport tbody").append(App.template(temp, n));
                });
            }
        };
        $('#pageReportBt').pager(options);
    };
    var initFields = function () {
        $("#query-form input[name='date']").datetimepicker({ allowBlank: false, value: new Date().dateFormat('Y-m-d') });
        $("#query-form select").select2({ minimumResultsForSearch: -1 });
        $("#query-form").submit(function (e) {
            e.preventDefault();
            //initReportList();
            loadReportList();
        });
    };
    var tpReportItem = $("#tempDataItem").html();
    var loadReportList = function () {
        $.get(window.location.href, $('#query-form').serialize(), function (data) {
            $("#gridData tbody").replaceWith('<tbody></tbody>');
            $.each(data.rows, function (i, n) {
              $("#gridData tbody").append(App.template(tpReportItem, n));
           });
        });
    };
    return {
        init: function () {
            initIdeaList();
            initSearch();
            initState();
            initDialog();
           // initFields();
           // initReportList();
        },
        auditIdea: function (id, status, confirm) {
            auditIdea(id, status, confirm);
        },
        audit: function(id, status){
            audit(id,status);
        },
        reportList: function (){
            initFields();
            loadReportList();
        },
        lockIdea: function (id) {
            lockIdea(id, 0);
        },
        unlockIdea: function (id) {
            lockIdea(id, 1);
        },
        addFlowPrice: function(){
            addFlowPrice();
        },
        addFlow: function() {
            addFlow();
        },
        delFlowPrice: function(id){
            delFlowPrice(id);
        }
    };

})(jQuery);

jQuery(function () {
    Admin.Idea.init();
});
