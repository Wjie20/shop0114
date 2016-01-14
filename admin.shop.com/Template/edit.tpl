<extend name="Common/edit"/>

<block name="form">
    <div class="main-div">
        <form method="post" action="{:U()}">
            <table cellspacing="1" cellpadding="3" width="100%">
                <?php foreach($fields as $field):

                     if($field['field'] == 'id'){
                                continue;
                             }
                ?>
                <tr>
                    <td class="label"><?php echo $field['comment']?></td>
                    <td>
                        <?php

                        if($field['field_type'] == 'text'){
                            if($field['field']=='sort'){
                                echo "<input type='text' name='{$field['field']}' maxlength='60' value='{\${$field['field']}|default=20}'/>";
                            }else{
                                echo "<input type='text' name='{$field['field']}' maxlength='60' value='{\${$field['field']}}'/>";
                            }
                        }elseif($field['field_type'] == 'textarea'){
                            echo "<textarea  name='{$field['field']}' cols='30' rows='4'>{\${$field['field']}}</textarea>";
                        }elseif($field['field_type'] == 'radio'){
                            foreach($field['option_values'] as $key=>$val){
                                 echo "<input type='radio' class='{$field['field']}' name='{$field['field']}' value='{$key}'/> {$val}";
                            }
                        }elseif($field['field_type'] == 'file'){
                            echo "<input type='file' name='{$field['field']}'/>";
                        }

                        ?>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <?php endforeach;?>
                <tr>
                    <td colspan="2" align="center"><br />
                        <input type="hidden" name="id" value="{$id}"/>
                        <input type="submit" class="button ajax-post" value=" 确定 " />
                        <input type="reset" class="button" value=" 重置 " />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</block>