<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - <?php echo ($meta_title); ?> </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="http://admin.shop.com/Public/Admin/css/general.css" rel="stylesheet" type="text/css" />
<link href="http://admin.shop.com/Public/Admin/css/main.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="http://admin.shop.com/Public/Admin/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <style type="text/css">
        .ztree {
            margin-top: 10px;
            border: 1px solid #617775;
            background: #f0f6e4;
            width: 200px;
            height:auto;
            overflow-y: scroll;
            overflow-x: auto;
        }
    </style>

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
                    <td class="label">父分类</td>
                    <td>
                        <input type='hidden' name='parent_id' class="parent_id" maxlength='60' value='<?php echo ($parent_id); ?>'/>
                        <input type='text' name='parent_name' class="parent_name" maxlength='60' disabled="disabled" value=' 默认是顶级分类'/>
                        <span  class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label"></td>
                    <td>
                        <ul id="treeDemo" class="ztree"></ul>
                    </td>
                </tr>
                <tr>
                    <td class="label">简介</td>
                    <td>
                        <textarea name='intro' cols='30' rows='4'><?php echo ($intro); ?></textarea> <span
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
                    <td class="label">排序</td>
                    <td>
                        <input type='text' name='sort' maxlength='60' value='<?php echo ((isset($sort) && ($sort !== ""))?($sort):20); ?>'/> <span
                            class="require-field">*</span>
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

    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/ztree/js/jquery.ztree.core-3.5.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var setting = {
                data: {
                    simpleData: {
                        enable: true,
                        pIdKey:'parent_id'  //配置使用哪个作为父级id来显示出层级结构

                    }
                },
                //回调函数,给节点对象添加点击事件
                callback: {
                    onClick:function (event, treeId, treeNode) {
                        //当用户选择的时候,给父级分类的文本框赋值,并将当选中的分类id赋值给隐藏域
                        $('.parent_id').val(treeNode.id);
                        $('.parent_name').val(treeNode.name);
                    }
                }
            };
            //获取浏览器发送的json格式的数据,用作树状结构的数据展示
            var zNodes =<?php echo ($jsonData); ?>;

            var treeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //根据id区分当前操作时新增还是编辑
            <?php if(empty($id)): ?>// 如果是新增,展开所有分类
               treeObj.expandAll(true);
            <?php else: ?>
            //如果是编辑,不展开当前父分类的节点树
                var parent_id = <?php echo ($parent_id); ?>;
                //根据当前分类的parent_id找出其父级分类
                var node = treeObj.getNodeByParam('id',parent_id);
                //选择当前分类的父级分类
                treeObj.selectNode(node);
                //将当前父级分类的名写入文本框,parent_id写入隐藏域
                $('.parent_name').val(node.name);
                $('.parent_id').val(node.id);<?php endif; ?>

        });
    </script>

</body>
</html>