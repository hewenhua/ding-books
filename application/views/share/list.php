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
      <a style='float: left; display: inline-block; width: 4.965rem; border-right: 1px solid #999; line-height: 0.8rem; font-size: 0.32rem; text-align:center; color: #38adff' href="<?php echo site_url($link_time);?>"><?php if($search_data['order_time']){echo '时间升序';}else{echo '时间降序';}?></a>
      <a style='float: left; display: inline-block; width: 4.965rem; line-height: 0.8rem; font-size: 0.32rem; text-align:center; color: #38adff' href="<?php echo site_url($link_name);?>"><?php if($search_data['order_name']){echo 'Z-A';}else{echo 'A-Z';}?></a>
    </div>
   </div>
   <button style='display:none' class="btn btn-block btn-default J_method_btn" data-method="biz.util.scan" data-param='{"type":"barCode"}' data-action="share">扫码分享</button>

  <div class="book-list">
      <?php 
      foreach ($items as $key => $item) { ?>
        <div class="book-item">
          <div class='book-intro'>
            <a href="<?php echo $item['douban_url']; ?>" class="book-img-link">
                <img class="book-image" src="<?php echo $item['image_url'];?>" >
            </a>
            <div class="book-info">
              <?php
              $title_anchor = anchor_popup(site_url('share/detail/'.$item['item_id']) , $item['title'] );
              $authors_anchor = array();
              foreach ($item['authors'] as $key => $author) {
                $author_anchor = anchor_popup( site_url('share/author?author_id=' . $author['author_id']) , $author['name'] );
                array_push($authors_anchor, $author_anchor);
              }
              $translators_anchor = array();
              foreach ($item['translators'] as $key => $translator) {
                $translator_anchor = anchor_popup(site_url( 'share/author?author_id=' . $translator['translator_id'] ), $translator['name'] );
                array_push($translators_anchor, $translator_anchor);
              }
              $publisher_anchor = anchor_popup(site_url('share/publisher?publisher_id=' . $item['publisher_id'] ), $item['publisher_name'] );
              $user_anchor = anchor_popup(site_url('share/user?user_id='.$item['user_id']) , $item['username'] );
              ?>
              <!-- title -->
              <h4 class='book-title'><?php echo $title_anchor;?></h4>
              <!-- author and translator -->
              <p class='book-author'>
              <?php
              foreach ($authors_anchor as $key => $author_anchor) {
                echo "<span>" . $author_anchor . "</span>";
                if($key != count($authors_anchor) - 1 )
                   echo " , ";
              }
              ?>
              <?php 
              if(!empty($translators_anchor)) echo " , ";
              foreach ($translators_anchor as $key => $translator_anchor) {
                echo "<span>*" . $translator_anchor .  "</span>";
                if($key != count($translators_anchor) - 1 )
                   echo " , ";
              }
              ?>
              </p>
              <!-- publihser -->
              <p class='book-publisher'><span><?php echo $publisher_anchor;?></span>   <span><?php echo $item['pubdate'];?></span></p>
              <!-- book owner infomation  -->
              <p class='book-owner'><span><?php echo $user_anchor;?></span></p>
            </div>
          </div>
          <div class='book-desc'><?php echo $item['description'];?></div>
        </div>
      <?php }?>
    <div class="pagination">
      <ul>
      <?php foreach ($link_array as $key => $value) {echo $value;}?>
      </ul>
    </div>
    
  </div>
</div>
