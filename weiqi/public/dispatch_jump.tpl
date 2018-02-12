{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>跳转提示</title>
    <link rel="stylesheet" type="text/css" href="__FONTAWESOME__/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="__CSS__/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="__CSS__/global.css">
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        body{ background: #fff; font-family: "Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif; color: #333; font-size: 16px; }
        .system-message{margin-top: 20%; padding: 24px 48px; }
        .system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
        .system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display: none; }
    </style>
</head>
<body>
    <div class="system-message text-center">
        <div class="jumbotron jumbotron-fluid">
          <div class="container">
            <?php switch ($code) {?>
                <?php case 1:?>
                <h5>
                    <p class="alert alert-success">
                        <i class="fa fa-check-circle-o"></i>&nbsp;<?php echo(strip_tags($msg));?>
                    </p>
                </h5>
                <?php break;?>
                <?php case 0:?>
                <h5>
                    <p class="error alert alert-danger">
                        <i class="fa fa-exclamation-triangle"></i>&nbsp;<?php echo(strip_tags($msg));?>
                    </p>
                </h5>
                <?php break;?>
            <?php } ?>
            <p class="detail"></p>
            <p class="">
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>&nbsp;页面自动 <a id="href" href="<?php echo($url);?>" class="btn btn-primary">跳转</a><br/> 
                等待时间： <b id="wait"><?php echo($wait);?></b>
            </p> 
          </div>
        </div>

    </div>
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
<script type="text/javascript" src="__JS__/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="__JS__/popper.min.js"></script>
<script type="text/javascript" src="__JS__/bootstrap.min.js"></script>
</html>
