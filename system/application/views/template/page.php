<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <script type="text/javascript" src="<?php echo site_url("js/jquery-1.5.1.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url("js/jquery-ui-1.8.13.custom.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url("js/sha256.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url("js/menu.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url("js/ui.selectmenu.js"); ?>"></script>
        <!--<script type="text/javascript" src="<?php echo site_url("js/browser.js"); ?>"></script>-->

        <link rel="shortcut icon" href="<?php echo site_url("favicon.ico"); ?>" type="image/x-icon" />

        <link rel="stylesheet" href="<?php echo site_url("css/themes/" . CurrentUser::getTheme() . "/jquery.ui.all.css"); ?>" type="text/css" />
        <link rel="stylesheet" href="<?php echo site_url("css/theme" . (CurrentUser::user() ? CurrentUser::user()->theme : 0) . ".css"); ?>" type="text/css" />
        <link rel="stylesheet" href="<?php echo site_url("css/structure.css"); ?>" type="text/css" />
        <link rel="stylesheet" href="<?php echo site_url("css/menu.css"); ?>" type="text/css" />
        <link rel="stylesheet" href="<?php echo site_url("css/ui.selectmenu.css"); ?>" type="text/css" />
    </head>
    <body>
        <div id="container">
            <?php if (empty($is_dialog)) { ?>
                <div id="content">
                    <div id="title">
                        <h1><?php echo $this->config->item("web_title"); ?></h1>
                        <div id="slogan"><img src="<?php echo site_url("img/logo.png"); ?>" alt="" /></div>&nbsp;
                    </div>
                    <?php //<div id="banner">&nbsp;</div> ?>
                    <div id="headers"><?php echo $header; ?></div>
                    <div><br /></div>
                    <?php echo $content; ?>
                </div>
                <div id="footer">
                    <hr />
                    Page rendered in {elapsed_time} seconds | <?php echo $footer; ?>
                </div>
            <?php } else
                echo $content; ?>
        </div>
        <span class="hidden"><a href="http://apycom.com/">jQuery Menu by Apycom</a></span>
    </body>
    <script type="text/javascript">
        $(function() {
            //if(BrowserDetect.browser == "Chrome" || BrowserDetect.browser == "Firefox")
            $("li.dummy").remove();
            $("select:not([name$='length'], .ignore)").each(function() {
                $(this).selectmenu({
                    style: 'dropdown',
                    width: 150,
                    maxHeight: 150
                });
            });
        });
    </script>
</html>
