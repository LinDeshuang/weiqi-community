//围棋等级
var weiqi_level = {
		'1':[
			'初段','二段','三段','四段','五段','六段','七段','八段','九段'
		],
		'2':[
			'32级','31级','30级','29级','28级','27级','26级','25级','24级','23级','22级','21级','20级','19级','18级','17级','16级','15级','14级','13级','12级','11级','10级','9级','8级','7级','6级','5级','4级','3级','2级','1级','初段','二段','三段','四段','五段','六段','七段'
		],
		'3':[
			''
		]
	};

/**弹出顶部提示框方法
*msg string 提示文本
*type string 提示框样式
*/
function topAlert(msg,type)
{
	$('#top_alert').find('strong').html(msg);
	$('#top_alert').fadeIn();
	$('#top_alert').attr('class','alert alert-'+type+' alert-dismissable');
	setTimeout(function(){$('#top_alert').fadeOut();},2000);
}
/**ajax 表单
*redirect_url 跳转地址
*msg string 提示文本
*/
function ajaxForm(redirect_url,msg){
		$('#ajaxForm').submit(function(){
		$.ajax({
			url:$(this).attr('action'),
			data:$(this).serialize(),
			method:'post',
			dataType:'json',
			success:function(data){
				if(data.errcode == 0){
					topAlert(msg,'success');
					if(redirect_url == ''){
						  setTimeout(function(){window.history.back();},1000);
					}else if(redirect_url == '#'){
							setTimeout(function(){window.location.relaod()},1000);
					}else{
							setTimeout(function(){window.location.href = redirect_url;},1000);
					}
				}else if(data.errcode == '40200'){
					topAlert(data.errmsg.msg,'warning');
					$('input[name='+data.errmsg.name+']').focus();
				}else if(data.errcode == '40100'){
					topAlert(data.errmsg,'warning');
				}else{
					topAlert('未知错误','warning');
				}
			}
		});
		return false;
	});
}
	//时间格式转换
	Date.prototype.format = function(format) {
    var date = {
       "M+": this.getMonth() + 1,
       "d+": this.getDate(),
       "h+": this.getHours(),
       "m+": this.getMinutes(),
       "s+": this.getSeconds(),
       "q+": Math.floor((this.getMonth() + 3) / 3),
       "S+": this.getMilliseconds()
	    };
	    if (/(y+)/i.test(format)) {
	       format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
	    }
	    for (var k in date) {
	       if (new RegExp("(" + k + ")").test(format)) {
	           format = format.replace(RegExp.$1, RegExp.$1.length == 1
	              ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
	       }
	    }
	    return format;
	}
