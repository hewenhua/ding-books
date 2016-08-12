<!DOCTYPE html>
<?php
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/util/Http.php");
require_once(__DIR__ . "/api/Auth.php");
$corpId =  $_GET['corpId'];
?>
<html>
<head>
    <title>闲书</title>
    <link rel="stylesheet" href="./public/stylesheets/style.css" type="text/css" />
    <!-- config中signature由jsticket产生，若jsticket失效，则signature失效，表现为dd.error()返回“权限校验失败”之错误。 -->
    <!-- 在请求新的jsticket之后，旧的ticket会失效，导致旧ticket产生的signature失效。 -->
    <script type="text/javascript">var _config = <?php echo Auth::isvConfig($corpId);?></script>
    <script type="text/javascript" src="./public/javascripts/zepto.min.js"></script>
    <script type="text/javascript" src="https://g.alicdn.com/ilw/ding/0.9.9/scripts/dingtalk.js"></script>
</head>
<body>
<a href="#" class="J_profile_btn" data-method="biz.util.open" data-param='{"id":"051734","corpId":"dingd8e1123006514592"}' data-action="">aaaaaaa</a>
<!--<button class="btn btn-block btn-default chooseonebtn">选择朋友发消息</button>
<button class="btn btn-block btn-default phonecall">给朋友打电话</button>-->
<button class="btn btn-block btn-default J_method_btn" data-method="biz.util.qrcode" data-param='{}' data-action="share">扫码</button>
<button class="btn btn-block btn-default J_profile_btn" data-method="biz.util.open" data-param='{"id":"051734","corpId":"dingd8e1123006514592"}' data-action="" onclicks="dd.biz.util.open({name:'profile',
    params:,
    onSuccess : function() {
        /**/
    },
    onFail : function(err) {}});">联系人</button>

<a href="http://120.26.118.14/index.php/share/book" target="_blank">书架</a>
<script>
 $('.J_sprofile_btn').on('click', function() {
    var $this = $(this);
    var method = $this.data('method');
    var action = $this.data('action');
    var param = $this.data('param') || {};

    dd.biz.util.open({name:'profile',
    params: eval('(' + param + ')'),
    onSuccess : function() {
        /**/
    },
    onFail : function(err) {alert(err);}});

  });

$('.J_profile_btns').on('click', function() {
var $this = $(this);
    var method = $this.data('method');
    var action = $this.data('action');
    var param = $this.data('param') || {};
if (typeof param === 'string') {
      //param = JSON.parse(param);
    }
dd.biz.util.open({name:'profile',
//    params:{"id":"051734","corpId":"dingd8e1123006514592"},
    params: eval('(' + param + ')'),
//    params:  param ,
    onSuccess : function() {
        /**/
    },
    onFail : function(err) {alert(err);}});

});
</script>

</body>
<script type="text/javascript" src="./public/javascripts/logger.js"></script>
<script type="text/javascript" src="./public/javascripts/demo_bk.js"></script>
</html>
