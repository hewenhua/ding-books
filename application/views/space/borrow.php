<?php if(empty($more)):?>
<div class="row">
    <div class="alert alert-error hide" id="msg-box"></div>
    <?php if(empty($trades)){ ?>
    <div class="alert alert-info no-borrow" id="msg-box">你还没有求漂，去找一本</div>
    <?php } ?>
    <div class="book-list" id='spaceQiuBookList'>
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
              $owner_anchor = $trade['owner_name'];
              ?>
              <!-- title -->
              <h4 class='book-title'><?php echo $title_anchor;?></h4>
              <span class='book-owner'>拥有者：<?php echo $trade['owner_name'];?></span>
            </div>
          </div>
          <div class='trade-record' id="trade_record">
            <?php
            foreach ($trade['trade_record'] as $key => $record) {?>
               <p><?php echo format_date($record['create_time']);?> :
                <?php if($record['op'] == 1){
                  echo '你向 ' . $owner_anchor . ' 申请借阅该书';
                 }else if($record['op'] == 2){
                  echo '书籍所有者同意了你的申请';
                 }else if($record['op'] == 3){
                  echo '书籍所有者拒绝了你的申请';
                 }else if($record['op'] == 4){
                  echo '你取消了申请';
                 }else if($record['op'] == 5){
                  echo '书籍所有者确认该书已归还';
                 }else if($record['op'] == 6){
                  echo '书籍所有者确认该书已丢失';
                 } ?>
               </p>
            <?php } ?>
          </div>
          <div class='borrow-action-area trade-record'>
            <?php if($trade['trade_status'] == 1){ //accept or deny?>
              <p>书籍所有者尚未回应，你可以撤销申请：</p>
              <div class='borrow-actions'>
                <button class="btn btn-danger trade_op borrow-action-button" trade_op="cancel" trade_id="<?php echo $trade['trade_id'];?>" type="button">取消</button>
              </div>
            <?php }else if($trade['trade_status'] == 2){?>
              <p>书籍所有者同意了你的申请，联系拥有者:</p>
              <div class='borrow-actions'>
                <a><?php echo $trade['owner_name'];?></a>
                <!--<a class='borrow-action-button' href='tel:<?php echo $trade['owner_cellphone'];?>'><?php echo $trade['owner_cellphone'];?></a>-->
                <!--<a class="borrow-action-button J_method_btn" data-method="biz.telephone.call" data-param='{"corpId": "dingea786bf7dcce0e0c", "users": ["26713232"]}' type="button">联系</a>-->
              </div>
            <?php }else if($trade['trade_status'] == 3){?>
              <p>很抱歉书籍所有者拒绝了你的申请</p>
            <?php }else if($trade['trade_status'] == 4){?>
              <p>你取消了对本书的申请</p>
            <?php }else if($trade['trade_status'] == 5){?>
              <p>书籍所有者确认该书已归还</p>
              <p>Thanks for using our system.</p>
            <?php }else if($trade['trade_status'] == 6){?>
              <p>书籍所有者确认该书已丢失</p>
            <?php }else{?>
              <p>系统错误</p>
            <?php }?>
          </div>
        </div>
      <?php }
if(empty($more)):
?>
    </div>
    <?php if(!empty($trades)){ ?>
        <div class='more' id='J_Space_Qiu_More' data-next-page='2'>加载更多</div>
    <?php }?>
</div>

<script type="text/javascript">
  var post_url = "<?php echo site_url('api/updateTrade');?>";
  updateTrade(post_url);
  $('#J_Space_Qiu_More').on('click', function(e){
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
            $('#spaceQiuBookList').append(data);
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
