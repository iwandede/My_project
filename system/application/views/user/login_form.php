<div id="title" class="hidden">
    <h1><?php echo $this->config->item("web_title"); ?></h1>
    <div id="slogan"><img src="<?php echo site_url("img/logo.png"); ?>" alt="" /></div>&nbsp;
</div>
<div id="login_dialog">
    <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
        <?php echo $msg["content"]; ?>
    </div>
    <?php echo form_open("index.php/user/login_process"); ?>
        <table class="form">
            <tr>
                <td><label for="username">Username</label></td>
                <td><input id="username" name="username" type="text" /></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td><input id="password" name="password" type="password" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input id="submit" type="submit" value="Login" /></td>
            </tr>
        </table>
    <?php echo form_close(); ?>
</div>

<!-- script section -->
<script type="text/javascript">
    $(function() {
        $("#login_dialog").dialog({
            title: "Form Login",
            modal: true,
            minWidth: 350,
            minHeight: 200,
            beforeClose: function() {
                return false;
            }
        });
        
        $("input:submit").button();
    });
</script>
