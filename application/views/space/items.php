<?php if(empty($more)):?>
<div class="row">
   <div class="span8 alert alert-error hide" id="msg-box"></div>
   <?php if(empty($items)){ ?>
      <div class="alert alert-info no-on-piao" id="msg-box">暂无在漂的书籍，来一本呗儿~</div>
   <?php } ?>
   <div style='display:none' class="span6">
    <ul class="nav nav-pills">
      <li><a href="<?php echo site_url($link_time);?>"><?php if($search_data['order_time']){echo 'Oldest';}else{echo 'Newest';}?></a></li>
      <li><a href="<?php echo site_url($link_name);?>"><?php if($search_data['order_name']){echo 'Z-A';}else{echo 'A-Z';}?></a></li>
    </ul>
   </div>

   <div style='display:none' class="span3">
    <div class="input-append">
      <form>
      <input class="span2" type="text" name="keyword" value="<?php echo $search_data['keyword'];?>">
      <button class="btn" name="submit" type="submit" >Search</button>
      </form>
    </div>
   </div>
<div class='shake-tip-area' id='J_Shake_tip'>
    <span class='shake-tip-close' style="color:#666;" id='J_Shake_Close'>╳</span>
    <image class='shake-tip-img' src='https://gw.alicdn.com/tps/TB1MWYIKVXXXXcHXXXXXXXXXXXX-233-251.png' />
    <p class='shake-tip-text'>摇一摇，遇到命中注定的它~</p>
</div>
<button class="btn btn-block btn-default J_shake list-share-button" data-method="device.accelerometer.watchShake" data-param='{"sensitivity": 15, "frequency": 150, "callbackDelay": 1000}' data-action="share">摇一摇</button>

  <div class="book-list" id='spaceOnBookList'>
      <?php 
endif;
      foreach ($items as $key => $item) { ?>
        <div class="book-item">
          <div class="book-intro">
            <a href="<?php echo $item['douban_url']; ?>" class="book-img-link">
                <img class="book-image" src="<?php echo $item['image_url'];?>" onerror="this.src='http://log.mmstat.com/m.gif'">
            </a>
            <a href="<?php echo site_url('share/detail/'. $item['item_id']); ?>" class="book-info">
              <h4 class='book-title'><?php echo $item['title'];?></h4>
              <div class='book-desc'><?php echo $item['description'];?></div>
              <div class='book-author'><?php foreach($item['authors'] as $key => $author){ echo $author['name'] . ' '; }?></div>
              <?php if($item['item_status'] == 1) { ?>
                <span class="label label-success share_status space-on-status">放漂中</span>
                <?php }else if($item['item_status'] == 2){ ?>
                <span class="label label-warning share_status space-on-status">已收漂</span>
                <?php }else if($item['item_status'] == 4){ ?>
                <span class="label label-warning share_status space-on-status">已放漂</span>
              <?php } ?>
            </a>
          </div>
          <div class="space-on-actions">
              <?php if($item['item_status'] != 4){ //shared book can not edit?>
                <?php if($item['item_status'] == 1) { ?>
                <button class="btn btn-warning unshare item_status" item_id="<?php echo $item['item_id'];?>" type="button">收漂</button>
                <?php }else if($item['item_status'] == 2){ ?>
                <button class="btn btn-success share item_status" item_id="<?php echo $item['item_id'];?>" type="button">放漂</button>
                <?php } ?>
                <button class="btn btn-danger delete item_status" item_id="<?php echo $item['item_id'];?>" type="button">删除</button>
              <?php }?>
          </div>
        </div>
      <?php }
if(empty($more)):
?>
  </div>
  <?php if(!empty($items)){ ?>
    <div class='more' id='J_Space_On_More' data-next-page='2'>加载更多</div>
  <?php }?>
</div>
</div>
<script type="text/javascript">
  var post_url = "<?php echo site_url('api/updateItem');?>";
  updateItemStatus(post_url);
  $('#J_Space_On_More').on('click', function(e){
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
            dd.device.notification.toast({
                    "global": true, "text": "已到最后一页", "duration": 1, "delay": 0
                    });
        } else {
          $('#spaceOnBookList').append(data);
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
