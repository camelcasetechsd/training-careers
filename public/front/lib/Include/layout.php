<?php
$config = include '../lib/Include/Config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ThinkCSI Career Program</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Le styles -->
        <link href="<?php echo $config['basePath'];?>css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css">
        <link href="<?php echo $config['basePath'];?>css/bootstrap-theme.min.css" media="screen" rel="stylesheet" type="text/css">
        <link href="<?php echo $config['basePath'];?>css/style.css" media="screen" rel="stylesheet" type="text/css">
        <link href="<?php echo $config['basePath'];?>img/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
        <!-- Scripts -->
        <!--[if lt IE 9]><script type="text/javascript" src="<?php echo $config['basePath'];?>/js/html5shiv.min.js"></script><![endif]-->
        <!--[if lt IE 9]><script type="text/javascript" src="<?php echo $config['basePath'];?>/js/respond.min.js"></script><![endif]-->
        <script type="text/javascript" src="<?php echo $config['basePath'];?>js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo $config['basePath'];?>js/bootstrap.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo $config['basePath'];?>"><img src="<?php echo $config['basePath'];?>img/thinkcsi-logo.png" alt="ThinkCSI" style="width:121px;height:41px;" />&nbsp; Training Careers</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="<?php echo $config['basePath'];?>">Home</a></li>
                        <li class="active"><a href="<?php echo $config['basePath'];?>front/application">Dashboard</a></li>
                        <li class="active"><a href="<?php echo $config['basePath'];?>admin/career">Career</a></li>
                        <li class="active"><a href="<?php echo $config['basePath'];?>admin/category">Category</a></li>
                        <li class="active"><a href="<?php echo $config['basePath'];?>admin/course">Course</a></li>
                        <li class="active"><a href="<?php echo $config['basePath'];?>admin/skill">Skill</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>
        <div class="container">

            <?php echo $content; ?>
            <hr>
            <footer>

            </footer>
        </div> <!-- /container -->
    </body>
</html>
