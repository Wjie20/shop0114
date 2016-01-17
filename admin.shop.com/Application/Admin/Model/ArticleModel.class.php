<?php
namespace Admin\Model;


use Think\Model;
use Think\Page;

class ArticleModel extends BaseModel
{
    //自动完成,将当前时间作为文章的录入时间
    protected $_auto = array(
        array('input_time', NOW_TIME),
    );
    // 每个表单都有自己的验证规则
    protected $_validate = array(
        array('name', 'require', '文章名称不能够为空'),
        array('article_category_id', 'require', '文章分类ID不能够为空'),
        array('input_time', 'require', '录入时间不能够为空'),
        array('status', 'require', '是否显示不能够为空'),

    );

    /**
     * 新增文章
     * 将文章的基本信息添加到article表中,将文章的内容添加到article_content表中
     * @param mixed|string $requestData
     */
    public function add($requestData)
    {
        $article_id = parent::add();
        if ($article_id === false) {
            $this->error = '添加文章出错!';
            return false;
        }
        $articleContentModel = M('ArticleContent');
        $result = $articleContentModel->add(array('article_id' => $article_id, 'content' => $requestData['content']));
        if ($result === false) {
            $this->error = '添加文章内容时出错!';
            return false;
        }
        return $article_id;
    }

    public function save($updateData)
    {
        $result = parent::save();
        if ($result === false) {
            $this->error = '编辑文章时出错!';
            return false;
        }
        $articleContentModel = M('ArticleContent');
        $result = $articleContentModel->where(array('article_id' => $updateData['id']))->setField('content', $updateData['content']);
        if ($result === false) {
            $this->error = '编辑文章内容时出错!';
            return false;
        }
    }

    /**
     * 覆盖父类中的钩子方法
     * 使用连接查询
     * 根据当前表的文章分类id查询出article_category表中的文章分类名称,用于列表页面的文章展示
     */
    protected function _setModel(){
        $this->join("__ARTICLE_CATEGORY__ as ac on obj.article_category_id=ac.id");
        $this->field("obj.*,ac.name as article_category_name");
    }
}