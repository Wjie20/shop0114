<?php
namespace Admin\Controller;


use Think\Controller;

class ArticleController extends BaseController
{
    protected $meta_title = '文章';
    protected $usePostParams = true;

    /**
     * 在编辑页面展示之前,获取文章的分类信息
     */
    protected function _before_edit_view()
    {
        $articleModel = D('ArticleCategory');
        $article_categories = $articleModel->getList('id,name');
        $this->assign('article_categories', $article_categories);
        //如果是编辑,查询出当前文章的内容用作数据回显
        $id = I('get.id');
        if (!empty($id)) {
            $articleContentModel = M('ArticleContent');
            $content = $articleContentModel->getFieldByArticle_id($id, 'content');
            $this->assign('content', $content);
        }
    }

    /**
     * 根据keyword搜索文章
     * @param $keyword
     */
    public function search($keyword)
    {
        $articleModel = D('Article');
        if (!empty($keyword)) {
            $wheres['name'] = array('like', "%{$keyword}%");
        }
        $articles = $articleModel->getList('id,name', $wheres);
        $this->ajaxReturn($articles);

    }
}