<div class="space-nav">
  <a href="<?php echo site_url("space/items");?>" <?php if(isActive("items")){ echo 'class="active"';}?> >我的在漂</li>
  <a href="<?php echo site_url("space/shared");?>" <?php if(isActive("shared")){ echo 'class="active"';}?> >我的放漂</li>
  <a href="<?php echo site_url("space/borrow");?>" <?php if(isActive("borrow")){ echo 'class="active"';}?> >我的求漂</li>
</div>
