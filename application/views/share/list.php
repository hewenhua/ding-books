<?php if(empty($more)):?>
<div class='shake-tip-area' id='J_Shake_tip'>
    <span class='shake-tip-close' id='J_Shake_Close'>╳</span>
    <image class='shake-tip-img' src='https://gw.alicdn.com/tps/TB1MWYIKVXXXXcHXXXXXXXXXXXX-233-251.png' />
    <p class='shake-tip-text'>摇一摇，遇到命中注定的它~</p>
</div>
<div class="row">

   <div class="span9" style="display: none">
    
      <?php if($page == 'user'){ ?>
      <div class="alert alert-info">
        The books in this page are owned by <b><?php echo anchor('share/user/' . $user['id'] , $user['username']);?></b> 
      , you can borrow multiple books from him/her.
      </div>
      <?php }else if($page == 'author'){ ?>
      <div class="alert alert-info">
        The books in this page are wrote by <b><?php echo anchor('share/author?author_id=' . $author['id'] , $author['name']);?></b> 
      </div>
      <?php }else if($page == 'publisher'){ ?>
      <div class="alert alert-info">
        The books in this page are published by <b><?php echo anchor('share/publisher?publisher_id=' . $publisher['id'] , $publisher['name']);?></b> 
      </div>
      <?php } ?>
   </div>

   <div class="search-area">
     <form action="<?php echo site_url('share/' . $page  );?>" method="get" >
     <input placeholder='请输入书名查询' class="search-input" type="text" name="keyword" value="<?php echo $search_data['keyword'];?>">
     <?php if($page != 'book'){?>
     <input class="hide" name="<?php echo $page.'_id';?>" value="<?php echo $search_data[$page.'_id'];?>">
     <?php }?>
     <button style='display:none' id='searchButton' class="btn" name="submit" type="submit" >搜索</button>
     </form>
   </div>

   <div style='width: 100%;'>
    <div style='width: 100%; height: 0.8rem; border-bottom: 1px solid #999' class="nav_bk nav-pills_bk">
      <a style="float: left; display: inline-block; width: 3.265rem; border-right: 1px solid #999; line-height: 0.8rem; font-size: 0.32rem; text-align:center; color: #38adff" href="<?php echo site_url("/");?>">热门书籍</a>
      <!--<a style='float: left; display: inline-block; width: 3.365rem; border-right: 1px solid #999; line-height: 0.8rem; font-size: 0.32rem; text-align:center; color: #38adff' href="<?php echo site_url($link_name);?>">部门书籍</a>-->
      <a style='float: left; display: inline-block; width: 3.265rem; border-right: 1px solid #999; line-height: 0.8rem; font-size: 0.32rem; text-align:center; color: #38adff' href="<?php echo site_url($link_time);?>"><?php if($search_data['order_time']){echo '时间升序';}else{echo '时间降序';}?></a>
      <a style='float: left; display: inline-block; width: 3.265rem; line-height: 0.8rem; font-size: 0.32rem; text-align:center; color: #38adff' href="<?php echo site_url($link_name);?>"><?php if($search_data['order_name']){echo 'Z-A';}else{echo 'A-Z';}?></a>
    </div>
   </div>

   <!--<button class="btn btn-block btn-default J_shake list-share-button" data-method="device.accelerometer.watchShake" data-param='{"sensitivity": 15, "frequency": 150, "callbackDelay": 1000}' data-action="share">摇一摇</button>-->

  <div class="book-list" id='bookList'>
    <?php endif;?>
      <?php 
      foreach ($items as $key => $item) { ?>
        <div class="book-item">
          <div class='book-intro'>
            <a href="<?php echo $item['douban_url']; ?>" class="book-img-link">
                <img class="book-image" src="<?php echo $item['image_url'];?>" >
            </a>
            <a href="<?php echo site_url('share/detail/'. $item['item_id']); ?>" class="book-info">
              <h4 class='book-title'><?php echo $item['title'];?></h4>
              <div class='book-desc'><?php echo $item['description'];?></div>
              <div class='book-author'><?php foreach($item['authors'] as $key => $author){ echo $author['name'] . ' '; }?></div>
              <?php if(!empty($item['price'])){?>
              <span class='book-department'><?php echo intval(intval($item['price'])/2)."漂流币";?></span>
              <?php }  if($item['department']==$department):?>
              <span class='book-department'><?php echo "同部门";?></span>
              <?php endif;?>
            </a>
          </div>
        </div>
      <?php }?>
<?php if(empty($more)):?>
  </div>
    <?php if($page_num*$limit < $total):?>
  <div class='more' id='J_More' data-next-page='2'>加载更多</div>
  </div>
    <?php elseif(empty($items)):?>
    <image class='shake-tip-img J_shake' data-method="device.accelerometer.watchShake" data-param='{"sensitivity": 15, "frequency": 150, "callbackDelay": 1000}' data-action="share" src='https://gw.alicdn.com/tps/TB1MWYIKVXXXXcHXXXXXXXXXXXX-233-251.png' />
    <h4 class='shake-tip-text'>点击摇一摇，查看漂流中的书~</h4>
    <?php endif;?>
<?php endif;?>
