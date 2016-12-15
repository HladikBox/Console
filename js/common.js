function getJSON(url, json, success, uiback,errorfunction) {
    $.post(url, json, function (data) {
        //alert(data);
        try {
            data = JSON.parse(data);
            if(success!=null)
            success(data);
            if(uiback!=null)
            uiback();
        } catch (e) {
            alert(e.message+data);
            if(errorfunction!=null){
                errorfunction();
            }else{
                alert("系统错误，请联系管理员");    
            }
            if(uiback!=null)
            uiback();
        }
    });
}

function info(context,okfunction){

    privatealert("通知",context,"info",okfunction);
}

function warning(context,okfunction){
    privatealert("警告",context,"warning",okfunction);
}

function error(context,okfunction){
    
    privatealert("错误",context,"danger",okfunction);
}

function privatealert(title,context,className,okfunction){
    $("#dlgAlert .modal-header").removeClass("bg-info").removeClass("bg-warning").removeClass("bg-danger");
    $("#dlgAlert .modal-header").addClass("bg-"+className);
    $("#dlgBtnAlertOK").removeClass("btn-info").removeClass("btn-warning").removeClass("btn-danger");
    $("#dlgBtnAlertOK").addClass("btn-"+className);
    $("#dlgAlert .modal-title").html(title);
    $("#dlgAlert .modal-body").html(context);

    $("#dlgBtnAlertOK").unbind("click");
    $("#dlgBtnAlertOK").click(function(){
        if(okfunction!=null){
            okfunction();   
        }
        $("#dlgAlert").modal("hide");
    });

    $("#dlgAlert").modal("show");
}