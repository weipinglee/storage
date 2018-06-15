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
        privRow : {id:0},//编辑页面单个权限数据

        roleSelectedPriv :[] //角色编辑页面选中的权限

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


        },


        //选中一个权限，为角色分配权限页面
        //@parameter:id,选中的值
        //@parameter：derection : 方向，向上或向下
        checkOne : function(id,direction){
            var _this = this;
            this.$nextTick(function(){
                console.log(JSON.stringify(this.roleSelectedPriv));

                if(this.roleSelectedPriv.indexOf(id)>=0){//该元素选中，把他的所有子元素也选中

                    for (var index in _this.privs){

                        //选中所有子元素
                        if(_this.privs[index]['parent_id']===id && (direction===undefined || direction==='down')){
                            if(this.roleSelectedPriv.indexOf(_this.privs[index].id)===-1){
                                this.roleSelectedPriv.push(_this.privs[index].id) ;
                            }

                            this.checkOne(_this.privs[index].id,'down');
                        }

                        //选中所有父级元素
                        if(_this.privs[index]['id']===id && (direction===undefined || direction==='up')){
                            var parentIndex = this.roleSelectedPriv.indexOf(_this.privs[index]['parent_id']);
                            if(parentIndex===-1 && _this.privs[index]['parent_id']>0){
                                this.roleSelectedPriv.push(_this.privs[index]['parent_id']);
                            }
                            if(_this.privs[index]['parent_id']>0){
                                this.checkOne(_this.privs[index]['parent_id'],'up');
                            }

                        }
                        // console.log(_this.privRow.parent_id);

                    }
                }else{//该元素取消选中,他的所有父级元素取消选中,所有下级元素取消选中
                    for (var index in _this.privs){

                        //取消选中所有父级元素
                        // if(_this.privs[index]['id']===id && (direction===undefined || direction==='up')){
                        //     var parentIndex = this.roleSelectedPriv.indexOf(_this.privs[index]['parent_id']);
                        //     if(parentIndex>=0){
                        //         this.roleSelectedPriv.splice(parentIndex,1);
                        //     }
                        //     this.checkOne(_this.privs[index]['parent_id'],'up');
                        // }

                        //取消选中所有下级元素
                        if(_this.privs[index]['parent_id']===id && (direction===undefined || direction==='down')){
                            var childIndex = this.roleSelectedPriv.indexOf(_this.privs[index].id);
                            if(childIndex >= 0){
                                this.roleSelectedPriv.splice(childIndex,1) ;
                            }
                            this.checkOne(_this.privs[index].id,'down');
                        }
                        // console.log(_this.privRow.parent_id);

                    }
                }
               // console.log(_this.privRow.parent_id);
            })


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
