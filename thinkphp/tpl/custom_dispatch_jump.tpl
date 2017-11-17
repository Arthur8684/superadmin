<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="format-detection" content="telephone=no">
    <meta charset="UTF-8">

    <meta name="description" content="Violate Responsive Admin Template">
    <meta name="keywords" content="Super Admin, Admin, Template, Bootstrap">

    <title>Super Admin Responsive Template</title>

    <!-- CSS -->
    <link href="__PUBLIC__/css/bootstrap.min.css" rel="stylesheet">
    <link href="__PUBLIC__/css/style.css" rel="stylesheet">
    <link href="__PUBLIC__/css/generics.css" rel="stylesheet">
</head>
<body id="skin-blur-violate">
<section id="error-page" class="tile">
    <?php switch ($code) {?>
    <?php case 1:?>
    <h1 class="m-b-10">SUCCESS</h1>
    <p><?php echo(strip_tags($msg));?></p>
    <?php break;?>
    <?php case 0:?>
    <h1 class="m-b-10">ERROR</h1>
    <p><?php echo(strip_tags($msg));?></p>
    <?php break;?>
    <?php } ?>
    页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
    <!--<a class="underline" href="">返回上一页</a> 或 <a class="underline" href="">转到主页</a>-->
</section>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
</body>
</html>