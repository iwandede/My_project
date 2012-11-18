<?php
$p404 = "http://" . $_SERVER['HTTP_HOST'] . "/home/error/404";
?>
<html>
    <head>
        <title>404 Page Not Found</title>
        <style type="text/css">
            body {
                background-color:	#fff;
                margin:				40px;
                font-family:		Lucida Grande, Verdana, Sans-serif;
                font-size:			12px;
                color:				#000;
            }

            #content  {
                border:				#999 1px solid;
                background-color:	#fff;
                padding:			20px 20px 12px 20px;
            }

            h1 {
                font-weight:		normal;
                font-size:			14px;
                color:				#990000;
                margin: 			0 0 4px 0;
            }
        </style>
        <script type="text/javascript">
            location = "<?php echo $p404; ?>";
        </script>
    </head>
    <body>
        <div id="content">
            <h1><?php echo $heading; ?></h1>
            <?php echo $message; ?>
        </div>
    </body>
</html>
