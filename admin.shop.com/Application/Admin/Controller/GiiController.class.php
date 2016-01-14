<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/9
 * Time: 15:52
 */

namespace Admin\Controller;


use Think\Controller;

class GiiController extends Controller
{

    public function index()
    {
        if (IS_POST) {
            header('Content-Type: text/html;charset=utf-8');
            //>>1.根据用户传递过来的表名，
            $table_name = I('post.table_name');
            //>>2.通过表名生成thinkphp中的规范的名称  goods==>Goods  goods_type==>GoodsType
            $name = parse_name($table_name, 1);

            //>>3.通过表名得到表的注解
            $sql = "select  TABLE_COMMENT from information_schema.`TABLES`  where TABLE_SCHEMA = '" . C('DB_NAME') . "' and TABLE_NAME = '{$table_name}'";
            $model = M();
            $rows = $model->query($sql);
            $meta_title = $rows[0]['table_comment'];

            //>>4. 查询表中的字段信息. 为index.html,edit.html和模型提供数据
            $sql = "show full columns from " . $table_name;
            $fields = $model->query($sql);  //fields中包含了当前表字段的信息
            //遍历获取到的字段信息,使用正则表达式将每个字段中的注释中的需要使用的内容截取出来单独保存在原数组中,
            //如:  状态@radio|1=是&0=否.
            $reg = '/(.*)@([a-z]*)\|?(.*)/';
            foreach ($fields as &$field) {
                $comment = $field['comment'];
                if (strpos($comment, '@') !== false) {
                    preg_match($reg, $comment, $result);
                    $field['field_type'] = $result[2];
                    $field['comment'] = $result[1];
                    //将状态后面的注释转化成数组保存在原数组中
                    if (!empty($result[3])) {
                        parse_str($result[3], $params);
                        $field['option_values'] = $params;
                    }
                } else {
                    $field['field_type'] = 'text';
                }

            }
            unset($field);
//            dump($fields);
//            exit;
            //定义代码模板的目录
            defined('TPL_PATH') or define('TPL_PATH', ROOT_PATH . 'Template/');

            //>>生成控制器
            require TPL_PATH . 'Controller.tpl';
            $controller_content = "<?php\r\n" . ob_get_clean();

            $controller_path = APP_PATH . 'Admin/Controller/' . $name . 'Controller.class.php';
            file_put_contents($controller_path, $controller_content);


            //>>生成模型
            ob_start();//再次开启ob缓存
            require TPL_PATH . 'Model.tpl';
            $model_content = "<?php\r\n" . ob_get_clean();

            $model_path = APP_PATH . 'Admin/Model/' . $name . 'Model.class.php';
            file_put_contents($model_path, $model_content);

            //生成edit
            ob_start();//再次开启ob缓存
            require TPL_PATH . 'edit.tpl';
            $edit_content = ob_get_clean();
            $edit_dir = APP_PATH . 'Admin/View/' . $name;
            if (!is_dir($edit_dir)) {
                mkdir($edit_dir, 0777, true);
            }
            $edit_path = $edit_dir . '/edit.html';
            file_put_contents($edit_path, $edit_content);


            //生成index
            ob_start();//再次开启ob缓存
            require TPL_PATH . 'index.tpl';
            $index_content = ob_get_clean();

            $index_path = $edit_dir . '/index.html';
            file_put_contents($index_path, $index_content);

        } else {
            $this->assign('meta_title', '代码生成器');
            $this->display('index');
        }


    }

}