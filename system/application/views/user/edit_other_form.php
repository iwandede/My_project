<div id="main_content">
    <ul>
        <li><a href="#tabs-1">Pengaturan User</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("user/edit_other_process"); ?>
        <?php echo form_hidden("id", $user->id); ?>
        <table class="form">
            <tbody id="user" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="username">Username</label></td>
                    <td><input type="text" id="username" name="username" class="medium" value="<?php echo $user->username; ?>" /></td>
                </tr>
                <tr>
                    <td><label for="new_password">Password baru</label></td>
                    <td><input type="password" id="new_password" name="new_password" class="medium" value="" /></td>
                </tr>
                <tr>
                    <td><label for="passconf">Ketik ulang password baru</label></td>
                    <td><input type="password" id="passconf" name="passconf" class="medium" value="" /></td>
                </tr>
            </tbody>
            <tbody class="fieldset">
                <tr class="button">
                    <td>&nbsp;</td>
                    <td>
                        <input id="submit" type="submit" value="Simpan" />
                    </td>
                </tr>
            </tbody>
        </table>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- script section -->
<script type="text/javascript">
    $(function() {
        $("#main_content").tabs({
            selected: 0,
            select: function(event, ui) {
                var url = $.data(ui.tab, "load.tabs");
                if(url) {
                    location.href = url;
                    return false;
                }
                return true;
            }
        });
        
        $("select").addClass("def");
        
        $("input:submit").button();
    });
</script>
