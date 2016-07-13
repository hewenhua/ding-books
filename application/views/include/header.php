<!DOCTYPE html>
<?php
$corpId = isset($_GET['corpId']) && !empty($_GET['corpId']) ? $_GET['corpId'] : 0;
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>闲书</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//g.alicdn.com/kg/m-base/2.0.3/index.js"></script>
    <link href="//g.alicdn.com/kg/m-base/2.0.3/reset.css" rel="styleSheet" type="text/css"/>
    <script src="/public/js/jquery.min.js"></script>
    <link href="/public/css/bootstrap.min_bk.css" rel="stylesheet">
    <script src="/public/js/bootstrap.min_bk.js"></script>
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
      body {
        //padding-top: 20px;
        //padding-bottom: 40px;
        width: 10rem;
        overflow: hidden;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
      .head-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 10rem;
        height: 0.8rem;
      }
      .head-nav div a{
        display: inline-block;
        float: left;
        height: 0.8rem;
        line-height: 0.8rem;
        width: 3.333333333rem;
        text-align: center;
        background-color: #aaaaaa;
        color: white;
      }
      .head-nav .active{
        background-color: #666666;
      }
    </style>
  </head>

  <body>
    <div class="container-narrow">
      <div class="masthead">
        <div class="head-nav">
          <div <?php if($this->uri->segment(1) == '') echo "class='active'";?> ><a href="<?php echo site_url();?>">发现</a></div>
          <div><a href="#">放漂</a></div>
          <div <?php if($this->uri->segment(1) == 'space') echo "class='active'";?> ><a href="<?php echo site_url('space/items');?>">我的</a></div>
        </div>
        <h3 class="muted" style='display:none'><a href="<?php echo site_url();?>"> </a></h3>
      </div>
