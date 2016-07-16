<?php if(empty($more)):?>
<div class="row">
    <div class="alert alert-error hide" id="msg-box"></div>
    <?php if(empty($trades)){ ?>
    <div class="alert alert-info" id="msg-box">你还没有受求漂</div>
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
              <p class='book-desc'><?php echo $trade['item_description'];?></p>
              <span class='book-owner'>拥有者：<?php echo $owner_anchor;?></span>
            </div>
          </div>
          <div id="trade_record">
            <?php
            foreach ($trade['trade_record'] as $key => $record) {?>
               <p><?php echo $record['create_time'];?> :
                <?php if($record['op'] == 1){
                  echo 'You sent ' . $owner_anchor . ' a request for this book .';
                 }else if($record['op'] == 2){
                  echo 'The onwer accepted your request .';
                 }else if($record['op'] == 3){
                  echo 'The onwer denied your request .';
                 }else if($record['op'] == 4){
                  echo 'You cancelled the request .';
                 }else if($record['op'] == 5){
                  echo 'The onwer confirm the book is returned .';
                 }else if($record['op'] == 6){
                  echo 'The onwer confirm the book is lost .';
                 } ?>
               </p>
            <?php } ?>
          </div>
          <div>
            <?php if($trade['trade_status'] == 1){ //accept or deny?>
              <p>The owner has not responsed yet , you can cancel the request .</p>
              <p>
              <button class="btn btn-danger trade_op" trade_op="cancel" trade_id="<?php echo $trade['trade_id'];?>" type="button">Cancel</button>
              </p>
            <?php }else if($trade['trade_status'] == 2){?>
              <p>The owner has agreed to lend you the book.</p>
              <p>You can contact the owner using infomation below :</p>
              <div class="alert alert-info">
                <p>Cellphone : <?php echo $trade['owner_cellphone'];?></p>
                <p>Email : <?php echo $trade['owner_email'];?></p>
              </div>
            <?php }else if($trade['trade_status'] == 3){?>
              <p>The owner has denied your request for this book.</p>
              <p>Sorry for that .</p>
            <?php }else if($trade['trade_status'] == 4){?>
              <p>You have canceled the request for this book.</p>
            <?php }else if($trade['trade_status'] == 5){?>
              <p>The owner has confirm the book has been returned .</p>
              <p>Thanks for using our system.</p>
            <?php }else if($trade['trade_status'] == 6){?>
              <p>The owner has confirm the book has been lost .</p>
              <p>Sorry for that .</p>
            <?php }else{?>
              <p>Sytem error.</p>
            <?php }?>
          </div>
        </div>
      <?php }
if(empty($more)):
?>
    </div>
    <div class='more' id='J_Space_Qiu_More' data-next-page='2'>加载更多</div>

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
            alert && alert('已加载到最后一页');
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
