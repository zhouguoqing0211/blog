//封装ajax方法
function ajax(type, url, postdata = '', callback) {
    var ajax = new XMLHttpRequest();
    ajax.open(type, url);
    //用于标识当前为ajax请求
    ajax.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    if ('post' == type) {
        //post请求
        ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    }
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200)
        {
            var res = JSON.parse(ajax.responseText);
            callback(res);
        }
    };
    ajax.send(postdata);
}