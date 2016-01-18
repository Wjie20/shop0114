<?php
defined('WEB_URL') or define('WEB_URL', 'http://www.shop.com/');
return array(
    'TMPL_PARSE_STRING' => array(
        '__CSS__' => WEB_URL . 'Public/Home/css',
        '__JS__' => WEB_URL . 'Public/Home/js',
        '__IMG__' => WEB_URL . 'Public/Home/images',
    )
);