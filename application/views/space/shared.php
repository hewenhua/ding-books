<div class="row">
  <div class="span9">
    <div class="alert alert-error hide" id="msg-box"></div>
    <?php if(empty($trades)){ ?>
    <div class="alert alert-info" id="msg-box">None of your books has been borrowed yet.</div>
    <?php } ?>
    <ul class="thumbnails">
      <?php 
      foreach ($trades as $key => $trade) { ?>
      <li class="span9">
        <div class="thumbnail">
          <div class="row">
            <a href="" class="span2">
              <img class="book_image" src="<?php echo $trade['image_url'];?>" >
            </a>
            <div class="span6">
              <?php
              $title_anchor = anchor_popup(site_url('item/detail/'.$trade['item_id']) , $trade['item_title'] );
              $borrower_anchor = anchor_popup(site_url('#') , $trade['borrower_name'] );
              ?>
              <!-- title -->
              <h4><?php echo $title_anchor;?></h4>
              <!-- book owner infomation  -->
              <!-- trade_record -->
              <div id="trade_record">
              <?php 
              foreach ($trade['trade_record'] as $key => $record) {?>
                 <p><?php echo $record['create_time'];?> : 
                  <?php if($record['op'] == 1){
                    echo $borrower_anchor .' 向你发起了求漂.';
                   }else if($record['op'] == 2){ 
                    echo '你接受了求漂 .';
                   }else if($record['op'] == 3){ 
                    echo '你拒绝了求漂 .'; 
                   }else if($record['op'] == 4){ 
                    echo '求漂者取消了请求 .'; 
                   }else if($record['op'] == 5){ 
                    echo '你确认了书已归还 .';
                   }else if($record['op'] == 6){ 
                    echo '你确认了书已丢失 .';
                   } ?>
                 </p>
              <?php } ?>
              </div>
              <!-- change book -->
              
              <?php if($trade['trade_status'] == 1){ //accept or deny?>
                <p>
                <button class="btn btn-success trade_op" trade_op="accept" trade_id="<?php echo $trade['trade_id'];?>" type="button">同意</button>
                <button class="btn btn-danger trade_op" trade_op="deny" trade_id="<?php echo $trade['trade_id'];?>" type="button">拒绝</button>
                </p>
              <?php }else if($trade['trade_status'] == 2){?>
                <p>你接受了 <?php echo $borrower_anchor;?> 的求漂.</p>
                <p>
                <button class="btn btn-primary trade_op" trade_op="return" trade_id="<?php echo $trade['trade_id'];?>" type="button">已归还</button>
                <button class="btn btn-danger trade_op" trade_op="lost" trade_id="<?php echo $trade['trade_id'];?>" type="button">已丢失</button>
                </p>
              <?php }else if($trade['trade_status'] == 3){?>
                <p>你拒绝了 <?php echo $borrower_anchor;?> 的求漂.</p>
              <?php }else if($trade['trade_status'] == 4){?>
                <p>求漂者取消了请求.</p>
              <?php }else if($trade['trade_status'] == 5){?>
                <p>你已确认书归还.</p>
                <p>感谢使用闲书漂流.</p>
              <?php }else if($trade['trade_status'] == 6){?>
                <p>你已确认书丢失.</p>
                <p>非常抱歉.</p>
              <?php }else{?>
                <p>系统错误.</p>
              <?php }?>

            </div>
            <div class="span1">

            </div>
          </div>
        </div>
      </li>
      <?php }?>
    </ul>
    <div class="pagination">
      <ul>
      <?php foreach ($link_array as $key => $value) {echo $value;}?>
      </ul>
    </div>
    
  </div>
</div>

<script type="text/javascript">
  var post_url = "<?php echo site_url('api/updateTrade');?>";
  updateTrade(post_url);
</script>

 <style type="text/css">
      .book_image {
        margin-left: 5px;
        margin-top: 5px;
        margin-bottom: 5px;

        width: 100px; 
        height: 130px;
      }
      .share_status{
        margin-left: 25px;
      }
</style>
