<div class="row">
  <div class="span9">
    <h3>User List</h3>
        <form action="" method="GET" class="form-inline">
        <input type="text" class="form-control" name="keyword" value="<?php echo $search_data['keyword'];?>">
       
        <button class="btn btn-success" type="submit" >Search</button>
        <a class="btn btn-primary" href="<?php echo site_url('admin/' . $self );?>"/>Reset</a>
        <a class="btn btn-primary" href="<?php echo site_url('admin');?>"/>Return</a>
        <br><br>
        <input type="text" id="datetimepicker1" class="form-control" name="start_time" placeholder="Create_time start" value="<?php echo $search_data['start_time'];?>">
          <input type="text" id="datetimepicker2" class="form-control" name="end_time" placeholder="Create_time end" value="<?php echo $search_data['end_time'];?>">
      </form>
    <table class="table table-bordered">
      <tr>
        <th>ID</th>
        <th>username</th>
        <th>cellphone</th>
        <th>email</th>
        <th>create time</th>
      </tr>
      <?php
      foreach ($users as $key => $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['cellphone'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['create_time'] . "</td>";
        echo "</tr>";
      }
      ?>
    </table>

    <div class="pagination">
      <ul>
      <?php foreach ($link_array as $key => $value) {echo $value;}?>
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript" src="/public/js/jquery.datetimepicker.js"></script>
<link rel="stylesheet" href="/public/css/jquery.datetimepicker.css">
<script>
  $('#datetimepicker1').datetimepicker();
  $('#datetimepicker2').datetimepicker();

  $(document).ready(function(){
    $("#resetTime").click(function(){
      $('#datetimepicker1').val("");
      $('#datetimepicker2').val("");
    });
  });
</script>
