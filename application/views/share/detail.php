<div class="detail">
  <div class="alert alert-error hide" id="msg-box"></div>
  <div class="detail-book-info">
    <img class="detail-book-image" src="<?php echo $item['image_url'];?>" >
    <div class='detail-book-basicinfo'>
      <h3 class='book-title'><?php echo $item['title']; ?></h3>
      <span class='book-info-item'>作者：<?php foreach($item['authors'] as $key => $author){ echo $author['name'] . ' '; }?></span><br/>
      <?php if($user_corpid == $item["corpid"] && empty($_GET['shake'])):?>
      <span class='book-info-item'>拥有者：<a href="javascript:;" class="J_profile_btn" style="color:#38adff;" data-method="biz.util.open" data-param='{"id":"<?php echo $item["userid"];?>","corpId":"<?php echo $item["corpid"];?>"}' data-action=""><?php echo $item['username'];?></a></span><br/>
      <?php endif;?>
      <?php if(!empty($item['publisher_name'])):?><span class='book-info-item'>出版社：<?php echo $item['publisher_name'];?></span><br/><?php endif;?>
      <span class='book-info-item'>出版日期：<?php echo $item['pubdate'];?></span><br/>
      <span class="label label-success share_status detail-share-state book-info-item">书籍状态：
        <?php if($item['item_status'] == 1){
          echo '在漂中';
        }else if($item['item_status'] == 2){
          echo '已收漂';
        }else if($item['item_status'] == 3){
          echo '已丢失';
        }else if($item['item_status'] == 4){
          echo '求漂中';
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
      <a href="<?php echo $item['douban_url']; ?>" class="btn btn-primary detail-request-button" type="button">查看书评 </a>
    <?php }else if($item['user_id'] != $this->session->userdata('user_id') AND $item['item_status'] == 1){?>
      <?php if($user_corpid == $item["corpid"]):?>
      <p class='detail-state-info'>这本书的拥有者是 <a href="javascript:;" class="J_profile_btn" style="color:#38adff;" data-method="biz.util.open" data-param='{"id":"<?php echo $item["userid"];?>","corpId":"<?php echo $item["corpid"];?>"}' data-action=""><?php echo $item['username'];?></a> .<br> 你可以<?php if(!empty($item['price'])):?>花<span class='book-info-item'><?php echo intval(intval($item['price'])/2)?>漂流币</span> <?php endif;?>向他/她借阅这本书.</p>
      <button class="btn btn-primary detail-request-button" id="request" type="button">申请借阅 </button>
      <?php else:?>
      <a href="<?php echo $item['douban_url']; ?>#!/ckDefault!/buyinfoCard" class="btn btn-primary detail-request-button" type="button">立即购买 </a>
      <?php endif?>
    <?php }else{ ?>
      <p class='detail-state-info'><span style="color:red;">本书暂时不可借阅</span>，可能因为 :<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* 你是本书的拥有者；<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* 本书被其他人求漂；</p>
    <?php if($item['user_id'] != $this->session->userdata('user_id')) :?>
      <a href="<?php echo $item['douban_url']; ?>#!/ckDefault!/buyinfoCard" class="btn btn-primary detail-request-button" type="button">立即购买 </a>
    <?php else:?>
      <a href="<?php echo $item['douban_url']; ?>" class="btn btn-primary detail-request-button" type="button">查看书评 </a>
    <?php endif;?>
    <?php } ?>
  </p>
</div>

<script type="text/javascript">
  var post_url = "<?php echo site_url('api/requestBorrow?corpId='.$corpId);?>";
  requestBorrow(post_url);
</script>
