
var nn_panduo = window.NameSpace || {};
nn_panduo.formacc = function(obj){
	this.event_obj = '';//当前事件对象
	this.ajax_return_data = '';//ajax返回数据
	this.no_redirect = false;
	this.validObj = '';
	if(obj){
		this.form = obj.form || '';//要提交的表单对象
		this.redirect_url = obj.redirect_url||'';//操作成功后自动跳转的地址
		this.no_redirect = $(this.form).attr('no_redirect');//是否开启自动跳转
		// this.status = obj.status||'';//列表中要更改的数据状态
		// this.ajax_url = obj.ajax_url||'';//处理接口地址
	}

}

nn_panduo.formacc.prototype = {
	form_init:function(formID){
		var _this = this;
		var select = '';
		if(formID){
			select = "form#"+formID;
		}
		else
            select =  "form[auto_submit]";
		$(select).each(function(i){
			_this.redirect_url = $(this).attr("redirect_url");
			_this.form = this;
			_this.no_redirect = $(this).attr('no_redirect') ? 1:0;

			_this.validform();
			var con = $(_this.form).find('[confirm=1]');
			if(con){
				var text = con.attr('confirm_text') ? con.attr('confirm_text') : '确认吗?';
				con.on('click',function(){
					layer.confirm(text,function(){
						$(_this.form).submit();
					})
				})
			}

		});
	},

	/**
	 * @deprecated
	 * 自动绑定select选中项
	 */
	bind_select:function(){
        $(this.form).find("select").each(function(){
        	var value = $(this).attr('value');
        	if(value != null && value != ''){
        		var option = $(this).find("option[value='"+value+"']");
	        	var txt = $(option).text();
	        	$(option).attr("selected",'selected');
	        	$(this).siblings("span").text(txt);
        	}
        });
        // $("select[name='type']").find("option[value='{$info['type']}']").attr("selected",'selected');
	},
	//绑定按钮确认弹窗时间
	bind_confirm:function(){
		var f_href;
		$("[confirm]").click(function(){
			var href = $(this).attr('href');
			var title = $(this).val() ? $(this).val() : $(this).text();
			if(href){
				var _this = this;
				(function(){
					if(href != 'javascript:;') f_href = href;
					$(_this).attr('href','javascript:;');
					title = title.indexOf('确认') >= 0 ? title+'?' : '确认'+title+'?';
					layer.confirm(title,{shade:false,title:false},function(flag){
						layer.closeAll();
						window.location.href = f_href;
					},function(){
						$(_this).attr('href',f_href);
					});
				})(href,_this);
			}
		});
	},
	/**
	 * 表单提交
	 * @type {Object}
	 */
	validform:function(){
        var _this = this;
        if(this.form){
			this.validObj = $(this.form).Validform({
		      tiptype : 3,
		      ajaxPost:false,
		      showAllError:false,
		      postonce:true,
		      
			  datatype : {
				  'float' : /^\d+\.?\d*$/i,
				  "zh" : /^[\u4E00-\u9FA5\uf900-\ufa2d]$/,
				  "zh2-5" : /^[\u4E00-\u9FFF\uf900-\ufa2d]{2,5}$/,
				  'qq' : /^[1-9][0-9]{4,16}$/i,
				  'zip' : /^\d{6}$/i,
				  'mobile':/^1[2|3|4|5|6|7|8|9][0-9]\d{8}$/,
				  'date':  /^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/i,
			      'datetime':  /^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29) (?:(?:[0-1][0-9])|(?:2[0-3])):(?:[0-5][0-9]):(?:[0-5][0-9])$/i,
				  'identify' : /^\d{17}(\d|x)$/i,
				  'money' : function(gets){
					  gets = $.trim(gets);
					  if(gets.match(/^[1-9][0-9]{0,7}(\.\d{0,2})?$/)){
						  return true;
					  }
					  if(gets.match(/^0\.[1-9][0-9]?$/)){
						  return true;
					  }
					  if(gets.match(/^0\.0[1-9]$/)){
						  return true;
					  }
					  return false;
				  }

				},
		      beforeSubmit:function(curform){
		        var url = $(curform).attr('action');
		        var data = $(curform).serialize();

				_this.ajax_post(url,data,function(){
					var return_data = _this.ajax_return_data;
					if(!_this.no_redirect){
						if(return_data.info!==''){
							layer.msg(return_data.info);
						}else{
							layer.msg("操作成功!稍后自动跳转");
						}
					   var sec = return_data.time ? return_data.time : 1;
						sec = sec * 1000;
						setTimeout(function(){
							if(_this.redirect_url){
								window.location.href=_this.redirect_url;
							}else{
								window.location.reload();
							}
						},sec);
					}else{
						layer.msg('操作成功！');
					}
				});

		        return false;
		      }
	      });

	    }
	},


	buttonSubmit : function(){
		var _this = this;
		$('[button_submit]').on('click',function(){
			var json = $(this).attr('ajax-data');
			json = JSON.parse(json);
			var url = $(this).attr('ajax-url');
			var confirm = $(this).attr('confirm_submit');
			if(confirm){
				var confirmText = $(this).attr("confirm_text") ? $(this).attr("confirm_text") : '确定吗?';
				layer.confirm(confirmText,
						function(){
							_this.ajax_post(url,json,function() {
								layer.msg('操作成功！');
							})
						},
						function(){
							layer.closeAll();
						}

				);
			}else{
				_this.ajax_post(url,json,function() {
					layer.msg('操作成功！');
				})
			}

		})
	},

	check:function(bool){
		return this.validObj.check(bool);
	},

	ignore:function(selecter){
		this.validObj.ignore(selecter);
	},
	unignore:function(selecter){
		this.validObj.unignore(selecter);
	},


	addRule :function(roles){
		this.validObj.addRule(roles);
	},

	addDatatype : function(name,rule){
		$.Datatype[name] = rule;
	},



	/**
	 * 设置数据状态
	 * @return {[type]} [description]
	 */
	//初始化点击事件
	bind_delete_handle:function(){
		var _this = this;
		$('a[ajax_delete]').each(function(i){
			var url = $(this).attr('ajax_url');
			var confirm = $(this).attr("ajax_confirm");
			var title = $(this).attr('title');
			var id    = $(this).attr('ajax_id');
			$(this).unbind('mouseover').unbind('click').click(function(){
				_this.event_obj = this;

				if(confirm){
					//删除提醒
					var tishi = '确定要这样操作吗？';
					if(title){
						tishi = '确定要'+title+'吗?';
					}
					layer.confirm(tishi,function(){
						layer.closeAll();
						_this.setDelete(url,{id:id});
					});
				}else{
					_this.setDelete(url,{id:id});
				}
			});
		});
	},
	setDelete:function(url,data){
		var _this = this;
		this.ajax_post(url,data,function(){
				//删除
				$(_this.event_obj).parents("tr").remove();
				return;

			//console.log(_this.event_obj);
		});
	},
	//ajax提交
	ajax_post:function(url,ajax_data,suc_callback,err_callback){
		var _this = this;
		layer.load(2,{shade:[0.1,'black']});
		$.ajax({
			type:'post',
			url:url,
			data:ajax_data,
			dataType:'json',
			success:function(data){
				layer.closeAll();
				if(data.success === 1){
					if(data.returnUrl){
						layer.msg(data.info);
						setTimeout(function(){
							window.location.href=data.returnUrl;
						},1000);
					}
					else{
						_this.ajax_return_data = data;
						if(typeof(eval(suc_callback)) === 'function'){
							suc_callback(data);
						}
						_this.ajax_return_data = '';
					}


				}else if(!!data.payment_id)
                {
                    var html = '<form action="'+submit_pay+'" method="post"><input type="hidden" name="payment_id" value="'+data.payment_id+'"/><input type="hidden" name="recharge" value="'+data.recharge+'"/><input type="hidden" name="sign" value="1"/></form><script type="text/javascript">window.document.forms[0].submit();</script>';
                    $('body').html(html);
                }else{

					if(data.returnUrl){

							layer.msg(data.info);
							setTimeout(function(){
								window.location.href=data.returnUrl;
							},1000);
					}
					else{
						if(typeof(eval(err_callback)) === 'function'){
							err_callback();
						}
						layer.msg(data.info);
					}

				}
			},
			error:function(data){
				layer.closeAll();
				layer.msg("服务器错误,请重试");
			}
		});
	}

}


$(function(){

	formacc = new nn_panduo.formacc();
	formacc.bind_delete_handle();
	formacc.bind_confirm();
	formacc.buttonSubmit();
	formacc.form_init();




})









