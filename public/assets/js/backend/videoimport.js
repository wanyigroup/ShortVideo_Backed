define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            $("#startscan").click(function()
            {
                //event.preventDefault();
                $.ajax({//发送Ajax请求
                    type: "POST",
                    url: "videoimport/scandir",
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    data: JSON.stringify({
                        'step': '1',
                        'scandir': $("#scandir").val()
                    }),
                    success: function(datas){
                        console.log(datas);
                        var html = '';
                        $.each(datas.rows, function(index, value) {
                            html += '<div class="form-group">';
                            html += '<label class="control-label col-xs-12 col-sm-2">视频'+index+1 +'标题:</label>';
                            html += '<div class="col-xs-12 col-sm-8">';
                            html += '<input type="hidden" value="'+value.path+'" name="row[path][]">';
                            html += '<input id="scandir" data-rule="required" class="form-control" name="row[title][]" type="text" value="'+value.newname+'">';
                            html += '</div>';
                            html += '</div>';
                        });
                        //console.log(html);
                        $('#res').html(html);
                    },
                    failure: function(errMsg) {
                        alert(errMsg);
                    }
                });
            });

            $("#startimport").click(function(event)
            {
                event.preventDefault();
                $.ajax({
                    url : "videoimport/import",
                    type: "POST",
                    data : $("#add-form").serialize(),
                    success: function(data, textStatus, jqXHR) {
                        console.log(data);//
                        alert(data.msg);
                        location.reload();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);// if there is an error
                    }
                });
            });
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                $(document).on("change", "#c-type", function () {
                    $("#c-pid option[data-type='all']").prop("selected", true);
                    $("#c-pid option").removeClass("hide");
                    $("#c-pid option[data-type!='" + $(this).val() + "'][data-type!='all']").addClass("hide");
                    $("#c-pid").data("selectpicker") && $("#c-pid").selectpicker("refresh");
                });
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});