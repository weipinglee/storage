var priVue = new Vue({
    el : "#priv",
    data : {
        init_status : 'open',//open和close,初始是关闭还是打开
        url : {
            privs_list : '',
            edit : ''
        },
        pre_label : '--',//设置权限名前的前缀
        privs : {},//列表数据
        privRow : {id:0}//编辑页面单个权限数据

    },
    methods : {
        getPrivs : function(){
            var _this=this;
            var priv_data = {};
            $.ajax({
                type : 'GET',
                url : this.url.privs_list,
                dataType : 'json',
                success : function (data) {
                    //console.log(typeof data);
                    priv_data = data;
                    for (var index in priv_data){
                        //此处必须要用$set，否则v-if不奏效
                        // _this.$set(_this.seen, _this.privs[index]['parent_id'], _this.privs[index]['parent_id']<=0);
                        if(_this.init_status==='close'){
                            priv_data[index].show = priv_data[index]['parent_id']<=0;//当前元素是否显示
                            priv_data[index].plus = true;//当前元素是加号还是减号
                        }else{
                            priv_data[index].show = true;//当前元素是否显示
                            priv_data[index].plus = false;//当前元素是加号还是减号
                        }
                        priv_data[index].selected = false;//当前元素是否选中
                        //设置前缀
                        priv_data[index].pre = '';
                        var tempLevel = priv_data[index].level;
                        _this.pre_label = _this.HTMLDecode(_this.pre_label);
                        while(tempLevel>0){
                            priv_data[index].pre += _this.pre_label;
                            tempLevel--;
                        }

                    }

                    _this.privs = priv_data;
                    _this.$forceUpdate();


                },
                error : function () {
                    alert('发生错误');
                }
            });

        },




        showLevel : function(id){
            for (var index in this.privs){
                if(this.privs[index]['parent_id']===id){
                    this.privs[index].show = true;
                }
                if(this.privs[index]['id']===id){
                    this.privs[index].plus = false;
                }
            }

        },
        hideLevel : function(id){
            for (var index in this.privs){
                if(this.privs[index]['parent_id']===id){
                    this.privs[index].show = false;
                    //递归关闭下级权限
                    this.hideLevel(this.privs[index]['id']);
                }
                if(this.privs[index]['id']===id){
                    this.privs[index].plus = true;
                }
            }
        },

        //html反转义
        HTMLDecode : function (text) {
            var temp = document.createElement("div");
            temp.innerHTML = text;
            var output = temp.innerText || temp.textContent;
            temp = null;
            return output;
        },

        //获取单个权限数据,edit页面用
        getOnePriv : function (id) {
            var _this = this;
            //console.log(JSON.stringify(_this.privs));
            if(_this.privs.length){
                for (var index in _this.privs){
                    if(_this.privs[index]['id']==id){
                        _this.privRow = _this.privs[index];
                    }
                   // console.log(_this.privRow.parent_id);

                }

                //重新遍历获取这条数据的父亲选中项
                for(var index in _this.privs){
                    if(_this.privRow.id>0 && _this.privRow.parent_id===_this.privs[index].id){
                        _this.privs[index].selected = true;
                    }
                }
            }else{
                _this.privRow =  {};
            }
          //  console.log(JSON.stringify(_this.privs));


        }
    },
    watch : {
        privs : function(){
            if(this.privRow.id>0){
                this.getOnePriv(this.privRow.id);

            }

        }
    }

});
