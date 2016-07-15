<div class="detail">
  <div class="alert alert-error hide" id="msg-box"></div>
  <div class="detail-book-info">
    <img class="detail-book-image" src="<?php echo $item['image_url'];?>" >
    <div class='detail-book-basicinfo'>
      <h3 class='book-title'><?php echo $item['title']; ?></h3>
      <span class='book-info-item'><?php foreach($item['authors'] as $key => $author){ echo $author['name'] . ' '; }?></span><br/>
      <span class='book-info-item'><?php echo $item['publisher_name'];?></span><br/>
      <span casss='book-info-item'><?php echo $item['pubdate'];?></span><br/>
      <span class="label label-success share_status detail-share-state book-info-item">
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
      <p>这本书的拥有者是 <?php echo $item['username'];?> .<br> 你可以向他/她借阅这本书.</p>
      <a class="btn btn-primary" href="<?php echo site_url('user/login');?>" type="button">申请借阅</a>
    <?php }else if($item['user_id'] != $this->session->userdata('user_id') AND $item['item_status'] == 1){?>
      <p>这本书的拥有者是 <?php echo $item['username'];?> .<br> 你可以向他/她借阅这本书.</p>
      <button class="btn btn-primary" id="request" type="button">申请借阅 </button>
    <?php }else{ ?>
      <p>本书暂时不可借阅，可能因为 :</p>
      <p> * 你是本书的拥有者；</p>
      <p> * 本书暂时不可借阅；</p>
    <?php } ?>
  </p>
</div>

<script type="text/javascript">
  var post_url = "<?php echo site_url('api/requestBorrow?corpId='.$corpId);?>";
  requestBorrow(post_url);
</script>
