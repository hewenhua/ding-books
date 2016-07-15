<div class="detail">
  <div class="alert alert-error hide" id="msg-box"></div>
  <div class="detail-book-info">
    <img class="detail-book-image" src="<?php echo $item['image_url'];?>" >
    <div class='detail-book-basicinfo'>
      <h3><?php echo $item['title']; ?></h3>
      <span ><?php foreach($item['authors'] as $key => $author){ echo $author['name'] . ' '; }?></span>
      <span><?php echo $item['publisher_name'];?></span>
      <span><?php echo $item['pubdate'];?></span>
      <span><?php echo $item['username'];?></span>
      <span class="label label-success share_status detail-share-state">
        <?php if($item['item_status'] == 1){
          echo '在漂';
        }else if($item['item_status'] == 2){
          echo '收漂';
        }else if($item['item_status'] == 3){
          echo '删除';
        }else if($item['item_status'] == 4){
          echo '已转';
        }
        ?>
      </span>
    </div>
    <span id="item_id" item_id="<?php echo $item['item_id'];?>"></span>
  </div>
  <p class="detail-book-desc">
    <?php echo $item['description'];?>
  </p>
  <p>
    <?php if($this->session->userdata('user_id') == FALSE){?>
      <p>This book's owner is <?php echo $item['username'];?> .<br> You can request him/her for borrowing this book.</p>
      <a class="btn btn-primary" href="<?php echo site_url('user/login');?>" type="button">Request for Borrowing </a>
    <?php }else if($item['user_id'] != $this->session->userdata('user_id') AND $item['item_status'] == 1){?>
      <p>This book's owner is <?php echo $item['username'];?> .<br> You can request him/her for borrowing this book.</p>
      <button class="btn btn-primary" id="request" type="button">Request for Borrowing </button>
    <?php }else{ ?>
      <p>This book is not available for you now . The reasons might be :</p>
      <p> * You are the owner of the book .</p>
      <p> * The status of the book is not sharing .</p>
    <?php } ?>
  </p>
</div>

<script type="text/javascript">
  var post_url = "<?php echo site_url('api/requestBorrow?corpId='.$corpId);?>";
  requestBorrow(post_url);
</script>
