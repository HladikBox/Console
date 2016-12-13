function getJSON(url, json, success, uiback) {
    $.post(url, json, function (data) {
        try {
            data = JSON.parse(data);
            success(data);
            uiback();
        } catch (e) {
            alert("系统错误，请联系管理员");
            uiback();
        }
    });
}