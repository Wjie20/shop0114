<?php

/**
 * 获取model中的错误信息
 * @param $model
 * @return string  错误信息
 */
function show_model_error($model)
{
    //得到model中的错误信息
    $errors = $model->getError();
    $errorMsg = '<ul>';
    if (is_array($errors)) {
        //如果是数组将错误信息拼成一个ul
        foreach ($errors as $error) {
            $errorMsg .= "<li>{$error}</li>";
        }
    } else {
        $errorMsg .= "<li>{$errors}</li>";
    }
    $errorMsg .= '</ul>';
    return $errorMsg;
}


if (!function_exists('array_column')) {
    function array_column($params, $field)
    {
        $result = array();
        foreach ($params as $row) {
            $result [] = $row[$field];
        }
       return $result;
    }
}