<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/16
 * Time: 23:00
 */

namespace Admin\Controller;


use Think\Controller;

class GoodsGalleryController extends Controller
{
    /**
     * 根据浏览器的ajax请求中的gallery_id删除数据库中对应的商品相册信息
     * @param $galleryId
     */
    public function remove($galleryId)
    {
        $goodsGalleryModel = M('GoodsGallery');
        $result = $goodsGalleryModel->delete($galleryId);
        if ($result === false) {
            $this->error('删除商品图出错!');
        } else {
            $this->success('该商品图已成功删除!');
        }
    }
}