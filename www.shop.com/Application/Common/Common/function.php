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


function arr2select($name, $rows, $select_option = '', $fieldValue = 'id', $fieldText = 'name')
{
    $select_html = "<select name='{$name}'>";
    $select_html .= "<option value=''> - 请选择 - </option>";
    foreach ($rows as $row) {
        //如果传递的第三个参数等于当前行中的当前字段的值,选中当前option
        $selected = '';
        if ($row[$fieldValue] == $select_option) {
            $selected = 'selected';
        }
        $select_html .= "<option value='{$row[$fieldValue]}' {$selected} >{$row[$fieldText]}</option>";
    }
    $select_html .= '</select>';
    echo $select_html;
}