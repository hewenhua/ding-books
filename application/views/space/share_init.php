<div class="row">
 <div class="span3">
  	
  </div>
  <div class="span3">
    <div class="alert alert-error <?php if($msg != 2) echo 'hide';?>" id="msg-box"><?php if($msg == 2) echo 'Can not find the ISBN';?></div>
	<form class="form" method='get' action="">
	    <label>请输入ISBN号</label>
	    <span class="help-block">通常在书的背面</span>
	    <input type="text" id="isbn" name="isbn" placeholder="ISBN" value="<?php if(isset($isbn)){echo $isbn;}?>">
	    <button class="btn btn-primary btn-block" id="submit" name="submit" type="submit" value="1">确定</button>
	</form>
  </div>
  <div class="span3">
  	
  </div>
</div>

<script type="text/javascript">
  
</script>
