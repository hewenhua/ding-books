<?php if(empty($more)):?>
<div class="row">
   <div class="span8 alert alert-error hide" id="msg-box"></div>
   <?php if(empty(items)){ ?>
      <div class="alert alert-info no-on-piao" id="msg-box">你还没有受求漂</div>
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

  <div class="book-list" id='spaceOnBookList'>
      <?php 
endif;
      foreach ($items as $key => $item) { ?>
        <div class="book-item">
          <div class="book-intro">
            <a href="<?php echo $item['douban_url']; ?>" class="book-img-link">
                <img class="book-image" src="<?php echo $item['image_url'];?>" >
            </a>
            <a href="<?php echo site_url('share/detail/'. $item['item_id']); ?>" class="book-info">
              <h4 class='book-title'><?php echo $item['title'];?></h4>
              <div class='book-desc'><?php echo $item['description'];?></div>
              <div class='book-author'><?php foreach($item['authors'] as $key => $author){ echo $author['name'] . ' '; }?></div>
              <?php if($item['item_status'] == 1) { ?>
                <span class="label label-success share_status space-on-status">放漂中</span>
                <?php }else if($item['item_status'] == 2){ ?>
                <span class="label label-warning share_status space-on-status">搜漂中</span>
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
          alert && alert('已加载到最后一页');
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
