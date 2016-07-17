<?php if(empty($more)):?>
<div class="row">
    <div class="alert alert-error hide" id="msg-box"></div>
    <?php if(empty($trades)){ ?>
    <div class="alert alert-info no-shared" id="msg-box">还没有被借的书籍~</div>
    <?php } ?>
    <div class='book-list' id='spaceSharedBookList'>
      <?php 
endif;
      foreach ($trades as $key => $trade) { ?>
        <div class="book-item">
          <div class="book-intro">
            <a href="" class="book-img-link">
              <img class="book-image" src="<?php echo $trade['image_url'];?>" >
            </a>
            <div class="book-info">
              <?php
              $title_anchor = $trade['item_title'];
              $borrower_anchor = $trade['borrower_name'];
              ?>
              <!-- title -->
              <h4><?php echo $trade['item_title'];?></h4>
              <!--<p class='book-desc'><?php echo $trade['item_description'];?></p>-->
              <span class='book-owner'>求漂者：<?php echo $trade['borrower_name'];?></span>
            </div>
          </div>
          <div id="trade_record" class='trade-record'>
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
          <div class='shared-action-area trade-record'>

            <?php if($trade['trade_status'] == 1){ //accept or deny?>
              <div class='shared-actions'>
                <button class="btn btn-success trade_op shared-action-button" trade_op="accept" trade_id="<?php echo $trade['trade_id'];?>" type="button">同意</button>
                <button class="btn btn-danger trade_op shared-action-button" trade_op="deny" trade_id="<?php echo $trade['trade_id'];?>" type="button">拒绝</button>
              </div>
            <?php }else if($trade['trade_status'] == 2){?>
              <p>你接受了 <?php echo $borrower_anchor;?> 的求漂.</p>
              <div class='shared-actions'>
                <button class="btn btn-primary trade_op shared-action-button" trade_op="return" trade_id="<?php echo $trade['trade_id'];?>" type="button">已归还</button>
                <button class="btn btn-danger trade_op shared-action-button" trade_op="lost" trade_id="<?php echo $trade['trade_id'];?>" type="button">已丢失</button>
              </div>
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
        </div>
      <?php }
if(empty($more)):
?>
    </div>
    <?php if(!empty($trades)){ ?>
        <div class='more' id='J_Space_Shared_More' data-next-page='2'>加载更多</div>
    <?php }?>
</div>

<script type="text/javascript">
  var post_url = "<?php echo site_url('api/updateTrade');?>";
  updateTrade(post_url);
  $('#J_Space_Shared_More').on('click', function(e){
    //alert(1);
    var $elem = $(e.target);
    var pageNum = parseInt($elem.attr('data-next-page'));
    //alert(pageNum);
    $.ajax({
      data: {
        more: 1,
        page: pageNum
      },
      success: function(data){
        // alert(typeof data);
        if(data == false || data == 'false'){
            // 提示
            dd.device.notification.alert({
                title: "闲书漂流",
                message: '已到最后一页'
            });
        } else {
          $('#spaceSharedBookList').append(data);
          $elem.attr('data-next-page', pageNum + 1 );
        }
      },
      error: function(xhr, type){
        //alert('Ajax error!');
      }
    });
  });
</script>

<?php endif;?>
