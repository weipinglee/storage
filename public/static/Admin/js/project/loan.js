var loanVue = new Vue({
    el:"#app",
    data:{
        persons : [
            {'name':'','id':'','shenfenzheng':'','mobile':''}
        ],
        pname : '',//搜索名称
        selectPerson : {id:'',mobile:'',shenfenzheng:'',name:''},
        inputStart : true, //用来控制中文的输入

        loan:{id:'',begin_date:'',end_date:'',rate:'',
            amount:'',period:'',exp_income:'',
            rec_rate:'',exp_final_income:'',
            status:'',rec_person:'',real_income:'',real_final_income:''},
        personListUrl : '',
        getExpincomeUrl : '',
        getFinalIncomeUrl : '',
        rec_pname : '',
        recPersons : [
            {'name':'','id':'','shenfenzheng':'','mobile':''}
        ],
        selectRecPerson : {id:'',mobile:'',shenfenzheng:'',name:''}


    },
    methods: {
        inputFocus:function(){
            $('#person_list').css('display','block');
        },
        inputRecFocus:function(){
            $('#rec_list').css('display','block');
        },
        getPerson: function (id) {
            //console.log(vue.inputStart);
            if(this.inputStart){
                var _this=this;
                $.ajax({
                    type: "GET",
                    url: this.personListUrl,
                    data: {
                        name: this.pname
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        // console.log(JSON.stringify(data));
                        _this.persons = [];
                        for (var index in data){
                            _this.persons.push(
                                {
                                    name:data[index].name,
                                    id:data[index].id,
                                    shenfenzheng:data[index].shenfenzheng,
                                    mobile:data[index].mobile
                                }
                            );
                        }


                    },
                    error: function () {
                        alert("错误");
                    }

                });
            }

        },

        getRecPerson: function (id) {
            //console.log(vue.inputStart);
            if(this.inputStart){
                var _this=this;
                $.ajax({
                    type: "GET",
                    url: this.personListUrl,
                    data: {
                        name: this.rec_pname
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        // console.log(JSON.stringify(data));
                        _this.recPersons = [];
                        for (var index in data){
                            _this.recPersons.push(
                                {
                                    name:data[index].name,
                                    id:data[index].id,
                                    shenfenzheng:data[index].shenfenzheng,
                                    mobile:data[index].mobile
                                }
                            );
                        }


                    },
                    error: function () {
                        alert("错误");
                    }

                });
            }

        },
        getPersonStart:function(){
            this.inputStart = false;
        },

        getPersonEnd : function(){
            this.inputStart = true;
        },

        selectFirstOne : function(){

        },

        toselectPerson : function(event){
            this.selectPerson = {
                id:    event.target.getAttribute('pid'),
                mobile:event.target.getAttribute('mobile'),
                shenfenzheng:event.target.getAttribute('shenfenzheng'),
                name:event.target.innerHTML
            };
            this.pname = event.target.innerHTML;
            $('#person_list').css('display','none');


        },

        toselectRecPerson : function(event){
            this.selectRecPerson = {
                id:    event.target.getAttribute('pid'),
                mobile:event.target.getAttribute('mobile'),
                shenfenzheng:event.target.getAttribute('shenfenzheng'),
                name:event.target.innerHTML
            };
            this.rec_pname = event.target.innerHTML;
            $('#rec_list').css('display','none');


        },
        /**
         * 计算收益
         */
        computeIncome : function(){
            // this.exp_income = 345;
            this.loan.begin_date = $('input[name=begin_date]').val();
            this.loan.end_date = $('input[name=end_date]').val();
            var _this = this;
            $.ajax({
                type:'GET',
                url :this.getExpincomeUrl,
                data : this.loan,
                dataType : 'json',
                success : function(data){
                    //console.log(JSON.stringify(data));
                    // console.log(data.exp_income);
                    _this.loan.exp_income = data.income;
                    _this.loan.exp_final_income = data.final_income;
                    if(data.info){
                        layer.msg(data.info);
                    }
                }
            })

        },
        /**
         * 计算收益
         */
        computeFinalIncome : function(){
            this.loan.real_end_date = $('input[name=real_end_date]').val();
            var _this = this;
            if(this.loan.real_end_date===''){
                return false;
            }
            $.ajax({
                type:'GET',
                url :this.getFinalIncomeUrl,
                data : this.loan,
                dataType : 'json',
                success : function(data){
                    //console.log(JSON.stringify(data));
                    // console.log(data.exp_income);
                    _this.loan.real_income = data.income;
                    _this.loan.real_final_income = data.final_income;
                    if(data.info){
                        layer.msg(data.info);
                    }
                }
            })

        },
        formSubmit : function(status){
            if(status===0){
                this.loan.status=0;
            }else{
                this.loan.status=1;
            }
            //
            //this.loan.status设置input的值在vue流程的后面，在下一句form.submit()执行时不会改变，所以用jquery设置他的值
            $('input[name=status]').val(this.loan.status);
            $('form').submit();
        }


    }
});
