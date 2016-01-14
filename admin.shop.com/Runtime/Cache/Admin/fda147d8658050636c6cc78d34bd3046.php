<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - <?php echo ($meta_title); ?> </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="http://admin.shop.com/Public/Admin/css/general.css" rel="stylesheet" type="text/css" />
<link href="http://admin.shop.com/Public/Admin/css/main.css" rel="stylesheet" type="text/css" />

    <link href="http://admin.shop.com/Public/Admin/uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
    <link href="http://admin.shop.com/Public/Admin/css/common.css" rel="stylesheet" type="text/css"/>

</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('index');?>"> <?php echo mb_substr($meta_title,2,null,'utf-8');?>列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo ($meta_title); ?> </span>
    <div style="clear:both"></div>
</h1>

    <div class="main-div">
        <form method="post" action="<?php echo U();?>">
            <table cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <td class="label">名称</td>
                    <td>
                        <input type='text' name='name' maxlength='60' value='<?php echo ($name); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">网址</td>
                    <td>
                        <input type='text' name='site_url' maxlength='60' value='<?php echo ($site_url); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">品牌LOGO</td>
                    <td>
                        <input type='file' id="logo_uploader" class="uploadify-button" name='logo_uploader' maxlength='60'/>
                        <input type='hidden' class="logo" name='logo' maxlength='60'/>

                        <div class="upload-img-box" style="display: none">
                            <div class="upload-pre-item">
                                <img src="">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="label">品牌简介</td>
                    <td>
                        <textarea name='intro' cols='30' rows='4'><?php echo ($intro); ?></textarea> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">排序</td>
                    <td>
                        <input type='text' name='sort' maxlength='60' value='<?php echo ((isset($sort) && ($sort !== ""))?($sort):20); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">状态</td>
                    <td>
                        <input type='radio' class='status' name='status' value='1'/> 是<input type='radio' class='status'
                                                                                             name='status' value='0'/> 否
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><br/>
                        <input type="hidden" name="id" value="<?php echo ($id); ?>"/>
                        <input type="submit" class="button ajax-post" value=" 确定 "/>
                        <input type="reset" class="button" value=" 重置 "/>
                    </td>
                </tr>
            </table>
        </form>
    </div>


<div id="footer">
共执行 1 个查询，用时 0.018952 秒，Gzip 已禁用，内存占用 2.197 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/layer/layer.js"></script>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/js/jquery.form.js"></script>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/js/common.js"></script>
<script type="text/javascript">
    $(function(){
        //选中是否显示
        $('.status').val([<?php echo ((isset($status) && ($status !== ""))?($status):1); ?>]);

    });
</script>

    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript">
        $(function(){

            $("#logo_uploader").uploadify({
                height        : 30,
                width         : 120,
                'buttonText' : '上传图片', //指定按钮上面的文字
//                'debug' : true,  //是否调试
                'fileSizeLimit' : '1MB',   //最大上传大小
                'fileTypeExts' : '*.gif; *.jpg; *.png',  //允许上传的文件
                'formData':{'dir':'shop-brand-logo'},  //上传文件时额外传递过去的参数---->告知服务器上的IndexController中的index方法将来将文件上传到哪个文件夹下
                // 'fileObjName': 'xxx', //上传该文件时,以什么名字上传..   $_FIELS['Filedata']  . 默认为:Filedata
                'swf'           : 'http://admin.shop.com/Public/Admin/uploadify/uploadify.swf',  //flash插件地址
                'uploader'      : "<?php echo U('Upload/index');?>",     //处理上传插件 上传上来的 文件
                'onUploadSuccess' : function(file, data, response) {  //data就是响应的 上传后的地址
                    $('.upload-img-box').show(); //显示出div
                    $('.upload-img-box .upload-pre-item img').attr('src','http://shop-brand-logo.b0.upaiyun.com/'+data);
                    $('.logo').val(data);  //将上传后的路径同时也放到隐藏域中.. 一起提交给服务器.
                },
                'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                    alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
                }


            });


        })
    </script>

</body>
</html>