
//把查询字符串转为JSON对象
var searchStr = decodeURI(window.location.search);
var toUrl = window.location.href;
toUrl = toUrl.split('?')[0];

searchStr = searchStr.substring(1);
var searchJSON = {};
var searchArr = searchStr.split('&');

searchArr.forEach(function(item){
    // console.log(item);
    searchJSON[item.split('=')[0]] = item.split('=')[1];
});
console.log(JSON.stringify(searchJSON));


var searchVue = new Vue({
    el:"#search",
    data:{
        fills : {}//html文件中的js进行赋值

    },
    methods:{

    }
});


