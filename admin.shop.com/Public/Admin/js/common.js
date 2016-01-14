//下面的代码在页面加载完毕执行
$(function () {
    //实现全选影响到下面的复选框的选中效果
    $('.selectAll').click(function () {
        $('.ids').prop('checked', $(this).prop('checked'));
    });

    $('.ids').click(function () {
        $('.selectAll').prop('checked', $('.ids:not(:checked)').length == 0);
    });


    //>>1 通用ajax的get请求
    $('.ajax-get').click(function () {
        //>>1.发送ajax请求
        //从当前标签上得到请求地址
        var url = $(this).attr('href');
        //回调函数中的data表示响应数据
        $.get(url, showAjaxLayer);
        return false;
    });

    //>>2 通用ajax的post请求
    $('.ajax-post').click(function () {
        //发送ajax的post请求
        //通过当前按钮向上找到form
        var form = $(this).closest('form');
        if (form.length != 0) {
            form.ajaxSubmit({success: showAjaxLayer});   //使用jquery.form.js插件实现的.
        } else {
            var url = $(this).attr('url');  //得到删除按钮上面的url
            var params = $('.ids:checked').serialize(); //不仅可以得到整个表单中的值, 也可以单独来获取每个表单元素的值
            $.post(url, params, showAjaxLayer);
        }
        /* //找到form上的action
         var url =  form.attr('action');
         //序列化form中的请求参数
         var params =form.serialize();
         $.post(url,params,showAjaxLayer);*/

        return false;  //取消默认操作
    });


    //显示一个提示框
    function showAjaxLayer(data) {
        var icon;
        if (data.status) {
            icon = 1;//成功符号
        } else {
            icon = 2;//错误符号
        }
        //显示一个提示框
        layer.msg(data.info, {
            time: 1000,  //等待时间后关闭
            offset: 0,  //设置位置
            icon: icon,  //设置提示框中的图标
            //                  shift: 6  //设置动画
        }, function () {         //提示框隐藏之后该函数执行
            //如果data中有url地址才转向
            if (data.url) {
                location.href = data.url;
            } else {
                location.reload();
            }
        });
    }

});