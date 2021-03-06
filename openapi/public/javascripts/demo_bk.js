/**
 * Created by liqiao on 8/10/15.
 */
var getMethod = function(method, ns) {
  var arr = method.split('.');
  var namespace = ns || dd;
  for (var i = 0, k = arr.length; i < k; i++) {
    if (i === k - 1) {
      return namespace[arr[i]];
    }
    if (typeof namespace[arr[i]] == 'undefined') {
      namespace[arr[i]] = {};
    }
    namespace = namespace[arr[i]];
  }
};

var sleep = function(numberMillis) {
  var now = new Date();
  var exitTime = now.getTime() + numberMillis;
  while (true) {
    now = new Date();
    if (now.getTime() > exitTime)
      return;
  }
}

logger.i('Here we go...');

logger.i(location.href);
logger.i(_config.corpId);

/**
 * _config comes from server-side template. see views/index.jade
 */
dd.config({
  //agentId: 35109252,
  agentId: _config.agentId,
  corpId: _config.corpId,
  timeStamp: _config.timeStamp,
  nonceStr: _config.nonceStr,
  signature: _config.signature,
  jsApiList: [
    'runtime.info',
    'device.notification.prompt',
    'biz.chat.pickConversation',
    'device.notification.confirm',
    'device.notification.alert',
    'device.notification.prompt',
    'device.geolocation.get',
    'biz.chat.open',
    'biz.util.open',
    'biz.user.get',
    'biz.contact.choose',
    'biz.telephone.call',
    'biz.util.uploadImage',
    'biz.util.qrcode',
    'biz.util.scan',
    'biz.ding.post']
});

dd.userid=0;
dd.ready(function() {
  logger.i('dd.ready rocks!');

  dd.runtime.info({
    onSuccess: function(info) {
      logger.i('runtime info: ' + JSON.stringify(info));
    },
    onFail: function(err) {
      logger.e('fail: ' + JSON.stringify(err));
    }
  });
  dd.device.geolocation.get({
    onSuccess: function(location) {
      dd.address = location.address;
      dd.latitude = location.latitude;
      dd.longitude = location.longitude;
    },
    onFail: function(err) {
      logger.e('fail: ' + JSON.stringify(err));
    }
  });

  dd.runtime.permission.requestAuthCode({
    corpId: _config.corpId, //企业id
    onSuccess: function (info) {
      logger.i('authcode: ' + info.code);
      $.ajax({
        url: '/user/getUserInfo',
        type:"GET",
        data: {"event":"get_userinfo","code":info.code,"corpId":_config.corpId},
        dataType:'json',
        timeout: 900,
        success: function (data, status, xhr) {
          logger.i('data_sendMsg: ' + data);
          var info = JSON.parse(data);
          if (info.errcode === 0) {
            dd.userid = info.userid;
            dd.username = info.name;
            dd.department = info.department;
            dd.jobnumber = info.jobnumber;
            dd.dingid = info.dingId;

            $.ajax({
              url: '/api/userUpdate',
              type:"POST",
              data: {"userId":dd.userid,"userName":dd.username,"corpId":_config.corpId,"jobnumber":dd.jobnumber,"dingid":dd.dingid,"department":dd.department,"latitude":dd.latitude,"longitude":dd.longitude},
              dataType:'json',
              timeout: 900,
              success: function (data, status, xhr) {
                var res = JSON.parse(data);
                logger.i('user data: ' + res);
                if(res.first_login === 1){
                  dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "每日登录，奖励1漂流币~"
                  });
                }
                if(res.first_register === 1){
                  dd.device.notification.alert({
                    title: "闲书漂流",
                    message: "首次登录，奖励30漂流币~"
                  });
                }
                if (res.user_score > 0 && res.first_login === 0){
                  document.getElementById("user-score").innerHTML = res.user_score;
                }
                if (res.process_login=== true){
                  //window.location.href = "/";
                }
              },
              error: function (xhr, errorType, error) {
                logger.e(errorType + ', ' + error);
              }
            });
          }
          else {
            logger.e('auth error: ' + data);
          }
        },
        error: function (xhr, errorType, error) {
          logger.e(errorType + ', ' + error);
        }
      });
    },
    onFail: function (err) {
      logger.e('requestAuthCode fail: ' + JSON.stringify(err));
    }
  });

  $('.chooseonebtn').on('click', function() {

    dd.biz.chat.pickConversation({
      corpId: _config.corpId, //企业id
      isConfirm:'false', //是否弹出确认窗口，默认为true
      onSuccess: function (data) {
        var chatinfo = data;
        if(chatinfo){
          console.log(chatinfo.cid);
          dd.device.notification.prompt({
            message: "发送消息",
            title: chatinfo.title,
            buttonLabels: ['发送', '取消'],
            onSuccess : function(result) {
              var text = result.value;
              if(text==''){
                return false;
              }

              $.ajax({
                url: '/openapi/sendMsg.php',
                type:"POST",
                data: {"event":"send_to_conversation","cid":chatinfo.cid,"sender":dd.userid,"content":text,"corpId":_config.corpId},
                dataType:'json',
                timeout: 900,
                success: function (data, status, xhr) {
                  var info = data;
                  logger.i('sendMsg: ' + JSON.stringify(data));
                  if(info.errcode==0){
                    logger.i('sendMsg: 发送成功');
                    /**
                     * 跳转到对话界面
                     */
                    dd.biz.chat.open({
                      cid:chatinfo.cid,
                      onSuccess : function(result) {
                      },
                      onFail : function(err) {}
                    });
                  }else{
                    logger.e('sendMsg: 发送失败'+info.errmsg);
                  }
                },
                error: function (xhr, errorType, error) {
                  logger.e(errorType + ', ' + error);
                }
              });
            },
            onFail : function(err) {}
          });
        }
      },
      onFail: function (err) {
      }
    });
  });

  $('#J_Share_Button').on('click', function() {
    var $this = $(this);
    var method = $this.data('method');
    var action = $this.data('action');
    var param = $this.data('param') || {};
    if (typeof param === 'string') {
      param = JSON.parse(param);
      logger.i('scan: ' + param);
    }
    if (param.corpId) {
      param.corpId = _config.corpId;
      if (param.id) {
        param.id = _config.users[0];
      }
      if (param.users) {
        param.users = _config.users;
      }
    }
    if (param.params && param.params.corpId) {
      param.params.corpId = _config.corpId;
      if (param.params.id) {
        param.params.id = _config.users[0];
      }
      if (param.params.users) {
        param.params.users = _config.users;
      }
    }
    dd.device.notification.toast({
      "global": true, "text": "扫描书背后的条形码，分享可得漂流币", "duration": 2, "delay": 0
    })
    param.onSuccess = function(result) {

      if (action === 'alert') {
        dd.device.geolocation.get({
          onSuccess: function(location) {
            dd.address = location.address;
            dd.latitude = location.latitude;
            dd.longitude = location.longitude;
            dd.device.notification.alert({
              title: method,
              message: JSON.stringify(param, null, 4) + '\n' + '响应：' + JSON.stringify(result, null, 4)
            });
          },
          onFail: function(err) {
            logger.e('fail: ' + JSON.stringify(err));
          }
        });

      } else if(action === 'share'){
        info = JSON.stringify(result);
        $.ajax({
          url: '/api/spaceShare',
          type:"POST",
          data: {"event":"space_share","userId":dd.userid,"corpId":_config.corpId,"info":info,"address":dd.address,"latitude":dd.latitude,"longitude":dd.longitude},
          dataType:'json',
          timeout: 900,
          success: function (data, status, xhr) {
            logger.i('data: ' + data);
            var info = JSON.parse(data);
            if(info.errcode === 0) {
              logger.i('book_id: ' + info.book_id);
              logger.i('item_id: ' + info.item_id);
              var score_info = "";
              if(info.action_type == "share"){
                if(info.score > 0){
                  score_info += "奖励你"+info.score+"漂流币~";
                }
                dd.device.notification.toast({
                  "global": true, "text": "放漂成功！" + score_info, "duration": 2, "delay": 0
                })
                window.location.href = "/share/detail/" + info.item_id + "?display=1";//"/space/items?order_time=0";
              }
            }

          },
          error: function (xhr, errorType, error) {
            logger.e(errorType + ', ' + error);
          }
        });
      }
    };
    param.onFail = function(result) {
      logger.i('scan: ' + result);
      console.log(method, '调用失败，fail', result)
    };
    getMethod(method)(param);
  });

  $('#J_Shake_Close').on('click',function(){
    $('#J_Shake_tip').hide();
  });

  $('.J_shake').on('click', function() {
    //alert(1);
    $('#J_Shake_tip').show();
    dd.device.accelerometer.watchShake({
      sensitivity: 12, //振动幅度，加速度变化超过这个值后触发shake
      frequency: 100, //采样间隔(毫秒)，指每隔多长时间对加速度进行一次采样， 然后对比前后变化，判断是否触发shake
      callbackDelay: 150,
      onSuccess: function(result) {
        //alert(2);
        dd.device.notification.vibrate({
          duration: 300,
          onSuccess: function() {
          },
          onFail: function() {
          }
        });
        $.ajax({
          url: '/api/spaceShake',
          type:"POST",
          data: {"event":"space_shake","userId":dd.userid,"corpId":_config.corpId},
          dataType:'json',
          timeout: 900,
          success: function (data, status, xhr) {
            // alert(3);
            logger.i('data: ' + data);
            var info = JSON.parse(data);
            if(info.errcode === 0) {
              logger.i('book_id: ' + info.book_id);
              logger.i('item_id: ' + info.item_id);
              if(info.msg != ""){
                dd.device.notification.alert({
                  title: "温馨提示",
                  message: info.msg
                });
              }
              if(info.item_id > 0){
                window.location.href = "/share/detail/" + info.item_id + "?display=1&shake=1";
              }
            }

          },
          error: function (xhr, errorType, error) {
            logger.e(errorType + ', ' + error);
          }
        });
        dd.device.notification.vibrate({
          duration: 300,
          onSuccess: function() {
            dd.device.accelerometer.clearShake({
            });
          },
          onFail: function() {
          }
        });

      },
      onFail: function(result) {
        console.log('error', result)
      }
    });
  });

 $('.J_profile_btn').on('click', function() {
    var $this = $(this);
    var method = $this.data('method');
    var action = $this.data('action');
    var param = $this.data('param') || {};

    dd.biz.util.open({name:'profile',
    params: eval('(' + param + ')'),
    onSuccess : function() {
        /**/
    },
    onFail : function(err) {alert(JSON.stringify(err));}});

  });



  $('.J_method_btn').on('click', function() {
    var $this = $(this);
    var method = $this.data('method');
    var action = $this.data('action');
    var param = $this.data('param') || {};

    if (typeof param === 'string') {
      param = JSON.parse(param);
    }
    if (param.corpId) {
      param.corpId = _config.corpId;
      if (param.id) {
        param.id = _config.users[0];
      }
      if (param.users) {
        param.users = _config.users;
      }
    }
    if (param.params && param.params.corpId) {
      param.params.corpId = _config.corpId;
      if (param.params.id) {
        param.params.id = _config.users[0];
      }
      if (param.params.users) {
        param.params.users = _config.users;
      }
    }

    param.onSuccess = function(result) {
      console.log(method, '调用成功，success', result)
      if (action === 'alert') {
        dd.device.notification.alert({
          title: method,
          message: '传参：' + JSON.stringify(param, null, 4) + '\n' + '响应：' + JSON.stringify(result, null, 4)
        });
      } else if(action === 'borrow'){
        info = JSON.stringify(result);
        $.ajax({
          url: '/api/spaceBorrow',
          type:"POST",
          data: {"event":"space_share","userId":dd.userid,"corpId":_config.corpId,"info":info,"address":dd.address,"latitude":dd.latitude,"longitude":dd.longitude},
          dataType:'json',
          timeout: 900,
          success: function (data, status, xhr) {
            logger.i('data: ' + data);
            var info = JSON.parse(data);
            if(info.errcode === 0) {
              logger.i('book_id: ' + info.book_id);
              var score_info = "";
              if(info.score > 0){
                score_info += "奖励你"+info.score+"漂流币~";
              }
              dd.device.notification.toast({
                "global": true, "text": "借阅成功！" + score_info, "duration": 2, "delay": 0
              })
              //window.location.href = "/share/detail/" + info.item_id + "?display=1";//"/space/items?order_time=0";
            }

          },
          error: function (xhr, errorType, error) {
            logger.e(errorType + ', ' + error);
          }
        });
      }
    };
    param.onFail = function(result) {
      dd.device.notification.alert({
        title: method,
        message: JSON.stringify(param, null, 4) + '\n' + '响应：' + JSON.stringify(result, null, 4)
      });
      console.log(method, '调用失败，fail', result)
    };
    getMethod(method)(param);
  });
});

dd.error(function(err) {
  logger.e('dd error: ' + JSON.stringify(err));
});
