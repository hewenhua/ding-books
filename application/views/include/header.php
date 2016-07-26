<!DOCTYPE html>
<?php
$corpId = isset($_GET['corpId']) && !empty($_GET['corpId']) ? $_GET['corpId'] : 0;
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>企业图书馆</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="x5-fullscreen" content="yes"/>
    <meta name="full-screen" content="yes"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <script src="//g.alicdn.com/kg/m-base/2.0.3/index.js"></script>
    <link href="//g.alicdn.com/kg/m-base/2.0.3/reset.css" rel="styleSheet" type="text/css"/>

    <link href="/public/css/book.css" rel='stylesheet' />
    <script src="/public/js/api.js"></script>
    <script type="text/javascript" src="/openapi/public/javascripts/zepto.min.js"></script>
    <script type="text/javascript" src="https://g.alicdn.com/ilw/ding/0.9.2/scripts/dingtalk.js"></script>
	<?php if(!empty($corpId)):
    ?>
	<script type="text/javascript">var _config = <?php echo Auth::isvConfig($corpId);?></script>
	<?php else:?>
    <script type="text/javascript">var _config = {}</script>
	<?php endif;?>

    <style type="text/css">
      body {width: 10rem;}
      .container-narrow {margin: 0 auto;max-width: 700px;}
      .container-narrow > hr { margin: 30px 0;}
      .jumbotron { margin: 60px 0; text-align: center;}
      .jumbotron h1 {font-size: 72px;line-height: 1;}
      .jumbotron .btn {font-size: 21px;padding: 14px 24px;}
      .marketing { margin: 60px 0;}
      .marketing p + h4 {margin-top: 28px;}
    </style>
  </head>

  <body>
    <div class="container-narrow">
      <div class="app">
        <span class='app-name'>&#xe600; 闲书  </span>
        <a href="<?php echo site_url('share/help')?>"> <span style="margin-left:0.1rem;font-family:iconfont;font-size:0.32rem;color:#fff;">&#xe605; </span></a>
        <span class='app-username'><?php echo $this->session->userdata('name');?>&nbsp;&nbsp;<span style="font-size:0.32rem;font-family:iconfont" id="user-score"><?php echo isset($user_score)?$user_score:intval($this->session->userdata('score'));?></span><span style="font-size:0.36rem;"><?php if(!empty($this->session->userdata('name'))){echo "&#xe604; ";}?></span></span>
      </div>
      <div class="masthead">
        <div class="head-nav">
         <a href="<?php echo site_url();?>" <?php if($this->uri->segment(2) == 'detail' || $this->uri->segment(2) == 'list' || $this->uri->segment(1)=='') echo "class='active'";?> ><span class='nav-tab-icon'>&#xe601;</span><br /><span >发现</span></a>
          <div <?php if($this->uri->segment(2) == 'detail'){echo "class='head-nav-item'";} else {echo "class='head-nav-item'";}?> id='J_Share_Button' data-method="biz.util.scan" data-param='{"type":"barCode"}' data-action="share"><span class='nav-tab-icon'>&#xe606;</span><br /><span>扫描</span></div>
          <a href="<?php echo site_url('space/items');?>" <?php if($this->uri->segment(1) == 'space') echo "class='active'";?> ><span class='nav-tab-icon'>&#xe602;</span><br /><span>我的</span></a>
        </div>
      </div>
