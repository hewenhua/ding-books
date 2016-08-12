    </div>
    <script src="//g.alicdn.com/ilw/ding/0.6.2/scripts/dingtalk.js"></script>
    <script type="text/javascript" src="/openapi/public/javascripts/logger.js"></script>
    <script type="text/javascript" src="/openapi/public/javascripts/demo_bk.js"></script>
    <script type="text/javascript">
	   $('#J_More').on('click', function(e){
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
                dd.device.notification.toast({
                            "global": true, "text": "已到最后一页", "duration": 2, "delay": 0
                        });
              } else {
                $('#bookList').append(data);
                $elem.attr('data-next-page', pageNum + 1 );
              }
            },
            error: function(xhr, type){
              //alert('Ajax error!');
            }
          });
	   });
    </script>
    <?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1259945166).'" width="0" height="0"/>';?>
  </body>
</html>
