Admin = {};

Admin.Media = (function ($) {
    var urlMediaList = "/admin/media/lists";
    var urlMediaEdit = "/admin/media/";
    var urlMediaDelete = "/admin/admin/deleteMedia.json?id=";

    var initSearch = function () {
        $("[data-filter]").on("keydown", function (event) {            
            if (event.keyCode == 13) {
                var value = $.trim($(this).val());
                if (value.length > 0) {
                    $("#pageMediaBt").pager("load", { name: value });
                } else {
                    $("#pageMediaBt").pager("load");
                }
            }
        });
    };

    var initMediaList = function () {
        $("#pageMediaBt").pager({
            url: urlMediaList,
            size: 20,
            onLoad: function (data) {
                var temp = $("#tempMediaItem").html();
                $("#gridMedia tbody").empty();
                $.each(data, function (i, n) {
                    n.content= '';
                    n.updated_at = n.updated_at.substring(0,10);
                    $.each(n.devices, function (k, device){
                        if (k>0) n.content += "<br/>";
                        n.content += device.name+'/'+device.pivot.group; 
                        //'/' +device.pivot.adx;
                    });
                    $("#gridMedia tbody").append(App.template(temp, n));
                });
            }
        });
    };

    var editMedia = function (id) {
        $("#dialogMedia").dialog("open", {
            url:  urlMediaEdit + (id > 0 ? 'edit/' +id : 'create' ),
            success: function () {
                $('#formMedia .select2').select2({ minimumResultsForSearch: -1 });
                $("#formMedia [data-inputmask]").inputmask();
                $("#formMedia select[id='classify_id']").bind("change", {parent:'classify_id', sub:'classify_id_1', first:false, default:"请选择二级分类"}, changeClassify);
                $("#formMedia select[id='classify_id_1']").bind("change", {parent:'classify_id_1', sub:'classify_id_3', first:false, default: '请选择三级分类'}, changeClassify);
                changeClassify({data:{parent:'classify_id', sub:'classify_id_1', first: true, default:'请选择二级分类'}});
                changeClassify({data:{parent:'classify_id_1', sub:'classify_id_3', first: true, default:'请选择三级分类'}});

                $("#formMedia").ajaxFormExt({
                    success: function () {
                        $("#dialogMedia").dialog("close");
                        $("#pageMediaBt").pager("load");
                    },
                    beforeSubmit: function(){
                    }
                });
            }
        });
    };
        var changeClassify = function (param) {
           var parent = $('#'+param.data.parent);
           var sub    = $('#'+param.data.sub);
           if (!param.data.first && param.data.parent == 'classify_id'){
               sub.empty();
               $('#classify_id_3').empty();
               $('#classify_id_3').append("<option value='0'>请选择三级分类</option>");
               $('#classify_id_3').select2({ minimumResultsForSearch: -1 });
           }
           if (parent.val()!=null && parent.val()>0) {
              $.ajax({
                url    : '/classification/'+parent.val()+'?_='+Date.parse(new Date()),
                success : function(data){
                   sub.empty();
                   sub.append('<option value="0">'+param.data.default+'</option>');
                   $.each(data, function (i, item){
                       if (param.data.first  && item.id == sub.attr('data-id')) {
                         sub.append("<option value='" + item.id + "' selected='selected'>" + item.name + "</option>");
                       }else{
                         sub.append("<option value='" + item.id + "'>" + item.name + "</option>");
                       }
                   });
                 sub.select2({ minimumResultsForSearch: -1 });
                },
                async: false ,
                dataType:'json'
             });
           }
        }

    var deleteMedia = function (id) {
        confirm("确定要删除此管理员吗？", function () {
            App.ajaxDelete(urlMediaDelete + id, {
                success: function () {
                    $("#pageMediaBt").pager("load");
                }
            });
        });
    };
    var deleteDevice= function (id) {
        $('.device'+id).remove();
    }
    var addDevice = function(){
        var temp = $("#tempDeviceItem").html();
        var id = Date.parse(new Date()) ;
        $('.addDevice').before(App.template(temp,{id: id}));
        $('#formMedia .device'+id+' .select2').select2({ minimumResultsForSearch: -1 });
        $("#formMedia [data-inputmask]").inputmask();
    }

    var initDialog = function () {
        $("#dialogMedia").dialog({ width: "800px" });
    };

    return {
        init: function () {
            initMediaList();
            initSearch();
            initDialog();
        },
        editMedia: function (id) {
            editMedia(id);
        },
        deleteMedia: function (id) {
            deleteMedia(id);
        },
        deleteDevice: function(id){
            deleteDevice(id);
        },
        addDevice:function (){
            addDevice();
            }
    };

})(jQuery);

jQuery(function () {
    Admin.Media.init();
});
