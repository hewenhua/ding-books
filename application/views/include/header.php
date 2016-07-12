<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>闲书</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="/public/js/jquery.min.js"></script>
    <link href="/public/css/bootstrap.min.css" rel="stylesheet">
    <script src="/public/js/bootstrap.min.js"></script>
    <script src="/public/js/api.js"></script>
    <script type="text/javascript" src="/openapi/public/javascripts/zepto.min.js"></script>
    <script type="text/javascript" src="https://g.alicdn.com/ilw/ding/0.9.2/scripts/dingtalk.js"></script>
    <script type="text/javascript">var _config = {}</script>

    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
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
    </style>
  </head>

  <body>
    <div class="container-narrow">
      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <li <?php if($this->uri->segment(1) == '') echo "class='active'";?> ><a href="<?php echo site_url();?>">发现</a></li>
          <li><a href="">|</a></li>
          <li><a href="#">放漂</a></li>
          <li><a href="">|</a></li>
          <li <?php if($this->uri->segment(1) == 'space') echo "class='active'";?> ><a href="<?php echo site_url('space/items');?>">我的</a></li>
        </ul>
        <h3 class="muted"><a href="<?php echo site_url();?>"> </a></h3>
      </div>

      <hr>
