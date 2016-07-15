      <div style='display:none' class="footer">
        <p>&copy; baofeng.hwh 2016</p>
      </div>
    </div>
    <script src="//g.alicdn.com/ilw/ding/0.6.2/scripts/dingtalk.js"></script>
    <script type="text/javascript" src="/openapi/public/javascripts/logger.js"></script>
    <script type="text/javascript" src="/openapi/public/javascripts/demo.js"></script>
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
              // Supposing this JSON payload was received:
              //   {"project": {"id": 42, "html": "<div>..." }}
              // append the HTML to context object.
              alert(typeof data);
              if(!data){
                alert && alert('已加载到最后一页');
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
  </body>
</html>
