function userLogin(url , target_url){
  $("#submit").click(function(event){
    event.preventDefault(); 
    var cellphone = $("#cellphone").val();
    var password = $("#password").val();
    var data = {
      'cellphone' : cellphone ,
      'password' : password 
    };

    $.ajax({
      type: 'post',
      url: url ,
      dataType : 'json' ,
      data: data ,
      success: function(data){
        if(data.result == 0){
          $("#msg-box").text(data.msg);
          $("#msg-box").show();
        }else if(data.result == 1){
          $("#msg-box").removeClass("alert-error");
          $("#msg-box").addClass("alert-success");
          $("#msg-box").text(data.msg);
          $("#msg-box").show();
          window.location.href=target_url;
        }else{
          $("#msg-box").text('connect error');
          $("#msg-box").show();
        }
      }, // end of handling succ
      error:function(e){
        alert("connect error");
        console.log(e);
      }
    }); //end of ajax

  }); //end of click action
}



function userRegister(url , target_url){
  $("#submit").click(function(event){
    event.preventDefault(); 
    var cellphone = $("#cellphone").val();
    var password = $("#password").val();
    var confirm = $("#confirm").val();
    var data = {
      'cellphone' : cellphone ,
      'password' : password ,
      'confirm' : confirm
    };

    $.ajax({
      type: 'post',
      url: url ,
      dataType : 'json' ,
      data: data ,
      success: function(data){
        if(data.result == 0){
          $("#msg-box").text(data.msg);
          $("#msg-box").show();
        }else if(data.result == 1){
          $("#msg-box").removeClass("alert-error");
          $("#msg-box").addClass("alert-success");
          $("#msg-box").text(data.msg);
          $("#msg-box").show();
          window.location.href=target_url;
        }else{
          $("#msg-box").text('connect error');
          $("#msg-box").show();
        }
      }, // end of handling succ
      error:function(e){
        alert("connect error");
        console.log(e);
      }
    }); //end of ajax

  }); //end of click action
}


function shareBook(url){
  $("#submit").click(function(event){
    event.preventDefault(); 
    var book_id = $("#book_id").attr('book_id');
    var description = $("#description").val();
    var data = {
      'book_id' : book_id ,
      'description' : description 
    };
    var succ_msg = "这本书已经在漂中";
    var another_one = $("#another_one").html();

    $.ajax({
      type: 'post',
      url: url ,
      dataType : 'json' ,
      data: data ,
      success: function(data){
        if(data.result == 0){
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: data.msg
                });
        }else if(data.result == 1){
          dd.device.notification.alert({
                    title: "闲书漂流",
                    message: succ_msg
                });
        }else{
          dd.device.notification.alert({
                    title: "连接失败",
                    message: succ_msg
                });
        }
      }, // end of handling succ
      error:function(e){
          dd.device.notification.alert({
                    title: "连接失败",
                    message: succ_msg
                });
      }
    }); //end of ajax

  }); //end of click action
}



function updateProfile(url ){
  $("#submit").click(function(event){
    event.preventDefault(); 
    var username = $("#username").val();
    var cellphone = $("#cellphone").val();
    var email = $("#email").val();
    var data = {
      'username' : username ,
      'cellphone' : cellphone ,
      'email' : email 
    };

    $.ajax({
      type: 'post',
      url: url ,
      dataType : 'json' ,
      data: data ,
      success: function(data){
        if(data.result == 0){
          $("#msg-box").text(data.msg);
          $("#msg-box").show();
        }else if(data.result == 1){
          $("#msg-box").removeClass("alert-error");
          $("#msg-box").addClass("alert-success");
          $("#msg-box").text("Profile changed success!");
          $("#msg-box").show();
          window.location.href=target_url;
        }else{
          $("#msg-box").text('connect error');
          $("#msg-box").show();
        }
      }, // end of handling succ
      error:function(e){
        alert("connect error");
        console.log(e);
      }
    }); //end of ajax

  }); //end of click action
}


function updateItemDescription(url){
  $("#submit").click(function(event){
    event.preventDefault(); 
    var item_id = $("#item_id").attr("item_id");
    var description = $("#description").val();
    var data = {
      'item_id' : item_id ,
      'description' : description
    };

    $.ajax({
      type: 'post',
      url: url ,
      dataType : 'json' ,
      data: data ,
      success: function(data){
        if(data.result == 0){
          $("#msg-box").text(data.msg);
          $("#msg-box").show();
        }else if(data.result == 1){
          $("#msg-box").removeClass("alert-error");
          $("#msg-box").addClass("alert-success");
          $("#msg-box").text("description changed success!");
          $("#msg-box").show();
          window.location.href=target_url;
        }else{
          $("#msg-box").text('connect error');
          $("#msg-box").show();
        }
      }, // end of handling succ
      error:function(e){
        alert("connect error");
        console.log(e);
      }
    }); //end of ajax

  }); //end of click action
}


function updateItemStatus(url){
  $(".item_status").click(function(event){
    var item_id = $(this).attr("item_id");
    var status = 0;
    if($(this).hasClass("share")){
      status = 1;
    }else if($(this).hasClass("unshare")){
      status = 2;
    }else{
      status = 3;
    }
    
    var data = {
      'item_id' : item_id ,
      'status' : status
    };

    $.ajax({
      type: 'post',
      url: url ,
      dataType : 'json' ,
      data: data ,
      success: function(data){
        if(data.result == 0){
          dd.device.notification.alert({
                    title: "闲书漂流",
                    message: data.msg
                });
        }else if(data.result == 1){
          if(status == 1)
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "放漂成功！"
                });
          else if(status == 2)
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "收漂成功！"
                });
          else if(status == 3)
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "删除成功！"
                });
          else
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "操作错误！"
                });

          setTimeout("self.location.reload();",1000);
        }else{
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "连接失败！"
                });
        }
      }, // end of handling succ
      error:function(e){
        dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "连接失败！"
                });
      }
    }); //end of ajax

  }); //end of click action
}

function requestBorrow(url){
  $("#request").click(function(event){
    var item_id = $("#item_id").attr("item_id");
    
    var data = {
      'item_id' : item_id
    };

    $.ajax({
      type: 'post',
      url: url ,
      dataType : 'json' ,
      data: data ,
      success: function(data){
        if(data.result == 0){
          dd.device.notification.alert({
                    title: "闲书漂流",
                    message: data.msg
                }); 
          //$("#msg-box").text(data.msg);
          //$("#msg-box").show();
        }else if(data.result == 1){

          dd.device.notification.alert({
                    title: "闲书漂流",
                    message: '求漂成功，等待对方回复'
          });
          //$("#msg-box").removeClass("alert-error");
          //$("#msg-box").addClass("alert-success");
          //$("#msg-box").text("Request has been sent to onwer .");
          //$("#msg-box").show();
        }else{
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "连接失败！"
                });
        }
      }, // end of handling succ
      error:function(e){
        dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "连接失败！"
                });
      }
    }); //end of ajax

  }); //end of click action
}


function updateTrade(url){
  $(".trade_op").click(function(event){
    var trade_id = $(this).attr("trade_id");
    var trade_op = $(this).attr("trade_op");
    
    var data = {
      'trade_id' : trade_id ,
      'trade_op' : trade_op
    };

    $.ajax({
      type: 'post',
      url: url ,
      dataType : 'json' ,
      data: data ,
      success: function(data){
        if(data.result == 0){
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: data.msg
                });
        }else if(data.result == 1){
          if(trade_op == 'accept')
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "你同意了求漂！"
                });
          else if(trade_op == 'deny')
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "你拒绝了求漂！"
                });
          else if(trade_op == 'cancel')
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "你取消了放漂！"
                });
          else if(trade_op == 'return')
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "确认书已归还！"
                });
          else
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "操作错误！"
                });
          setTimeout("self.location.reload();",1000);
        }else{
            dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "连接失败！"
                });
        }
      }, // end of handling succ
      error:function(e){
        dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "连接失败！"
                });
      }
    }); //end of ajax

  }); //end of click action
}
