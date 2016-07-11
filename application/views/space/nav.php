<ul class="nav nav-tabs">
  <li <?php if(isActive("share")){ echo 'class="active"';}?> ><a href="<?php echo site_url("space/share");?> ">分享书籍</a></li>
  <li <?php if(isActive("items")){ echo 'class="active"';}?> ><a href="<?php echo site_url("space/items");?> ">我的图书</a></li>
  <li <?php if(isActive("shared")){ echo 'class="active"';}?> ><a href="<?php echo site_url("space/shared");?> ">借出的书</a></li>
  <li <?php if(isActive("borrow")){ echo 'class="active"';}?> ><a href="<?php echo site_url("space/borrow");?> ">借入的书</a></li>

</ul>
