var priVue = new Vue({
    el : "#priv",
    data : {
        init_status : 'open',//open和close,初始是关闭还是打开
        url : {
            privs_list : ''
        },
        pre_label : '--',//设置权限名前的前缀
        privs : {}

    },
    methods : {
        getPrivs : function(){
            var _this=this;
            $.ajax({
                type : 'GET',
                url : this.url.privs_list,
                dataType : 'json',
                success : function (data) {
                    //console.log(typeof data);
                    var priv_data = data;
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

                        //设置前缀
                        priv_data[index].pre = '';
                        while(priv_data[index].level>0){
                            priv_data[index].pre += _this.pre_label;
                            priv_data[index].level--;
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
        }
    }

});