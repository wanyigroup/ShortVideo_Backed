define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'payplan/index' + location.search,
                    add_url: 'payplan/add',
                    edit_url: 'payplan/edit',
                    del_url: 'payplan/del',
                    multi_url: 'payplan/multi',
                    table: 'payplan',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'iconimage', title: __('Iconimage'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'title', title: __('Title')},
                        {field: 'days', title: __('Days')},
                        {field: 'freedays', title: __('Freedays')},
                        {field: 'amount', title: __('Amount')},
                        {field: 'created_at', title: __('Created_at'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'updated_at', title: __('Updated_at'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});