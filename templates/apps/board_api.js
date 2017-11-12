<script src="/plugins/ace-builds/src/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/plugins/ace-builds/src/ext-language_tools.js"></script>




<script type="text/javascript">
    
    phpeditor=null;





    $(document).ready(function () {
        $("#btnSaveApiList").click(function () {
            var apis = Array();
            $(".api_item").each(function () {
                var active = $(this).find(".api_active").prop("checked")?"1":"0";
                var api = {
                    active: active,
                    "type": $.trim($(this).find(".api_type").val().toLowerCase()),
                    "model": $.trim($(this).find(".api_model").text().toLowerCase()),
                    "func": $.trim($(this).find(".api_func").text().toLowerCase()),
                    "input": $(this).find(".api_input").val(),
                    "output": $(this).find(".api_output").val()
                };
                api.description = $(this).find(".api_description").text();
                apis.push(api);
            });
            var json = { "action": "save", app_id: "{{$appinfo.id}}", apis: apis };
            $("#btnSaveApiList").attr("disabled", true);
            getJSON("{{$rootpath}}api/api", json, function (data) {
                if (data.code == 0) {

                    info("保存成功");
                    
                } else {
                    warning(data.result);
                }
            }, function () {
                $("#btnSaveApiList").attr("disabled", false);
            });
        });
        $("#api_checkbox_all").change(function () {
            $(".api_active").prop("checked", $("#api_checkbox_all").prop("checked"));
        });
        $("#btnBatchApiTest").click(function(){
            if($(".api_item .api_active:checked").length==0){
                info("请先勾选你需要测试的API");
                return;
            }
            $(".api_test").removeClass("hide");
            $(".api_test_th").removeClass("hide");
            $(".api_test").removeClass("ready");
            $(".api_item .api_active:checked").parent().parent().find(".api_test").addClass("ready");
            $(".api_item .api_active:checked").parent().parent().find(".api_test").html('<i class="fa fa-spinner fa-spin"></i>');
            $("#btnBatchApiTest").attr("disabled",true);
            batchApiTest();
        });



        $("#btnApiCodeSave").click(function(){
            var content=phpeditor.getValue();
            content=content.substring(5,content.length-2);
            var json={
                action:"setapicontent",
                app_id:"{{$appinfo.id}}",
                model:$("#dlgApiCoding_model").val(),
                func:$("#dlgApiCoding_func").val(),
                content:content
            };
            $("#btnApiCodeSave").attr("disabled", true);
            getJSON("{{$rootpath}}api/api", json, function (data) {
                //alert(JSON.stringify(data));
                if(data.code=="0"){

                    $("#dlgApiCoding").modal("hide");

                }else{

                    error("代码保存失败，请检查并重写");

                }

            },function(){
                $("#btnApiCodeSave").attr("disabled", false);
            });



        });
    });
    function openApiEditor(model,func){
        var apiID="#api_"+model+"_"+func;
        var api=$(apiID);
        var isnew=(model==""||func=="");
        $("#txtApiConfirm").text("");
        $("#dlg_api_description").prop("disabled", false);
        if(isnew){

            $("#dlg_api_model").prop("disabled",false);
            $("#dlg_api_func").prop("disabled",false);
            $("#dlg_api_model").val("");
            $("#dlg_api_func").val("");
            $("#dlg_api_input").val("");
            $("#dlg_api_output").val("");
            $("#dlg_api_description").val("");
        }else{
            $("#dlg_api_model").prop("disabled",true);
            $("#dlg_api_func").prop("disabled", true);
            if(api.find(".api_type").val()=="model"){
                $("#dlg_api_description").prop("disabled",true);
            }

            $("#dlg_api_model").val(model);
            $("#dlg_api_func").val(func);
            $("#dlg_api_input").val(api.find(".api_input").val());
            $("#dlg_api_output").val(api.find(".api_output").val());
            $("#dlg_api_description").val(api.find(".api_description").text());
        }

        $("#btnApiConfirm").unbind("click");
        $("#btnApiConfirm").click(function(){
            if(isnew){
                model=$.trim($("#dlg_api_model").val().toLowerCase());
                func=$.trim($("#dlg_api_func").val().toLowerCase());


                var pattern =  /^[A-Za-z]+$/;
                if($.trim(model)==""||!pattern.test(model)){
                    $("#txtApiConfirm").text("模块仅能使用英文且不能为空");
                    return;
                }
                if($.trim(func)==""||!pattern.test(func)){
                    $("#txtApiConfirm").text("方法仅能使用英文且不能为空");
                    return;
                }
                if(func=="list"||func=="get"||func=="update"||func=="delete"){
                    $("#txtApiConfirm").text("方法不能使用list,get,update,delete，已经被数据模型预先使用");
                    return;
                }
            }
            var input=$.trim($("#dlg_api_input").val());
            var output=$.trim($("#dlg_api_output").val());
            if(input!=""){
                try{
                    JSON.parse(input);
                }catch(e){
                    $("#txtApiConfirm").text("测试传入参数可以为空，否则必须为JSON格式字符串");
                    return;
                }
            }
            if(output!=""){
                try{
                    JSON.parse(output);
                }catch(e){
                    $("#txtApiConfirm").text("测试测试结果可以为空，否则必须为JSON格式字符串");
                    return;
                }
            }
            var description=$.trim($("#dlg_api_description").val());
            if (isnew) {

                var newrow = $('<tr id="api_' + model + '_' + func + '"  class="api_item">' +
                '<td><input type="checkbox" class="api_active" ></td>' +
                '<td><a target="_blank" class="api_testurl" href="{{$Config.workspace.domain}}/{{$User.login}}/{{$appinfo.alias}}/api/' + model + '/' + func + '">/api/' + model + '/' + func + '</a></td>' +
                '<td>自定义</td>' +
                '<td class="api_model">' + model + '</td>' +
                '<td class="api_func">' + func + '</td>' +
                '<td class="api_description">' + description + '</td>' +
                '<td>' +
                '<a href="javascript:openApiEditor(\'' + model + '\',\'' + func + '\');" ><i class="fa fa-cog"></i></a>' +
                ' <a class="fa fa-pencil-square-o" href="javascript:writeApi(\'' + model + '\',\'' + func + '\');" target="_blank" ></a>' +
                '<input type="hidden" value="" class="api_input" />' +
                '<input type="hidden" value="" class="api_output" />' +
                '<input type="hidden" value="self" class="api_type" />' +
                '</td>' +
                '<td class="api_test hide">测试结果</td>' +
                '</tr>');
                newrow.find(".api_input").val(input);
                newrow.find(".api_output").val(output);

                $("#table_apilist").append(newrow);
            } else {
                api.find(".api_input").val(input);
                api.find(".api_output").val(output);
                api.find(".api_description").text(description);
            }
            $("#dlgApiEditor").modal("hide");
        });

        $("#dlgApiEditor").modal("show");

    }
    function batchApiTest(){
        if($(".api_test.ready").length==0){
            $("#btnBatchApiTest").attr("disabled",false);
            return;
        }
        var dp=$(".api_test.ready:first");
        var testurl=dp.parent().find(".api_testurl").attr("href");
        var type=dp.parent().find(".api_type").val();
        var func=dp.parent().find(".api_func").text();
        var input=null;
        try{
            input=JSON.parse(dp.parent().find(".api_input").val());
        }catch(e){

        }
        var output=null;
        try{
            output=JSON.parse(dp.parent().find(".api_output").val());
        }catch(e){

        }
        var json=null;
        if(input!=null&&input!=""){
            json=input;
        }else{
            if(type=="model"){
                try{
                    json=JSON.parse(dp.parent().find(".api_modelinput").val());
                }catch(e){

                }
            }
        }
        
        //var modelname=dp.attr("modelname");
            getJSON(testurl, json, function (data) {
                //alert(1);
                
                    if(data.code!=null&&data.code!="0"){
                        dp.html('<span class="text-red">'+data.result+data.return+'</span>');
                    }else{

                        if(output!=null){
                            if($.isArray(output)){
                                if($.isArray(data)==false){
                                    dp.html('<span class="text-red">测试输出结果为数组，返回结果不是</span>');
                                }else{
                                    if(output.length!=data.length){
                                        dp.html('<span class="text-red">测试输出结果的行数与返回结果的行数不匹配</span>');
                                    }else{

                                        var keynotfind=Array();
                                        var valuediff=Array();
                                        for(var row=0;row<output.length;row++){
                                            $.each(output[row], function(i) {
                                                //alert(output[i]);
                                                //alert(i);
                                                //keynotfind.
                                                 if(data[row][i]==undefined){
                                                    keynotfind.push(i);
                                                    return;
                                                 }else{
                                                    if(output[row][i]!="*"){
                                                        if(output[row][i]!=data[row][i]){
                                                            valuediff.push(i);
                                                        }
                                                    }
                                                 }

                                            });
                                        }
                                        if(keynotfind.length>0||valuediff.length>0){
                                            dp.html('<span class="text-yellow">测试输出结果不一致，请检查</span>');
                                        }else{
                                            dp.html('<span class="text-green">执行成功</span>');    
                                        }
                                    }
                                }
                            }else{
                                if($.isArray(data)){
                                    dp.html('<span class="text-red">测试输出结果不是数组，返回结果是数组</span>');
                                }else{
                                    var keynotfind=Array();
                                    var valuediff=Array();
                                    $.each(output, function(i) {
                                        //alert(output[i]);
                                        //alert(i);
                                        //keynotfind.
                                         if(data[i]==undefined){
                                            keynotfind.push(i);
                                            return;
                                         }else{
                                            if(output[i]!="*"){
                                                if(output[i]!=data[i]){
                                                    valuediff.push(i);
                                                }
                                            }
                                         }
                                    });
                                    var str="";
                                    if(keynotfind.length>0){
                                        str+="<p class='text-red'>找不到以下key:"+keynotfind.join(",")+"</p>";        
                                    }
                                    if(valuediff.length>0){
                                        str+="<p class='text-red'>值不对:"+valuediff.join(",")+"</p>";        
                                    }
                                    if(str!=""){
                                        dp.html(str);
                                    }else{
                                        dp.html('<span class="text-green">执行成功</span>');
                                    }
                                }
                            }
                        }else{

                        if(func=="list"){
                            if(data.length>0){
                                dp.html('<span class="text-green">获取列表数据成功</span>');
                            }else{
                                dp.html('<span class="text-yellow">列表没有数据，请尝试添加</span>');
                            }
                        }else if(func=="get"){
                            if(data.id!=null){
                                dp.html('<span class="text-green">获取id为1的数据成功</span>');
                            }else{
                                dp.html('<span class="text-yellow">没有找到id为1的数据，请尝试添加</span>');
                            }
                        }else if(func=="update"){
                            if(data.code==0){
                                dp.html('<span class="text-green">更新成功</span>');
                            }else{
                                dp.html('<span class="text-yellow">更新失败，原因:'+data.return+'</span>');
                            }
                        }else if(func=="delete"){
                            if(data.code==0){
                                dp.html('<span class="text-green">删除成功</span>');
                            }else{
                                dp.html('<span class="text-yellow">删除失败:'+data.result+'</span>');
                            }
                        }else{
                            dp.html('<span class="text-green">执行成功</span>');
                        }
                    }
                }
                

                dp.removeClass("ready");
                setTimeout("batchApiTest()", 1000 );
                },null,function(data){
                    dp.html('<span class="text-red">返回的数据非json格式，'+data+'</span>');
                });
        
    }
    function writeApi(model,func){
        $("#dlgApiCoding_model").val(model);
        $("#dlgApiCoding_func").val(func);
        var json={
            action:"getapicontent",
            app_id:"{{$appinfo.id}}",
            model:model,
            func:func
        };
        getJSON("{{$rootpath}}api/api", json, function (data) {
            $("#dlgApiCoding").modal("show");
            $("#dlg_api_content").val("<?php \r"+data+"\r?>");
            if(phpeditor==null){
                phpeditor = ace.edit("dlg_api_content");
                phpeditor.setTheme("ace/theme/monokai");
                phpeditor.session.setMode("ace/mode/php");
            }else{
                phpeditor.setValue("<?php \r" + data + "\r?>", -1);
            }
            
        });

    }


    $("#apiCodingDescription b nousenow because of php editor").dblclick(function(){
        var linecode="\n"+$(this).text()+"\n";

        var el = $("#dlg_api_content").get(0);
        var pos = 0;
        if ('selectionStart' in el) {
            pos = el.selectionStart;
        } else if ('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }

        var content=$("#dlg_api_content").val();
        content=$.trim(content.substr(0,pos)+linecode+content.substr(pos,content.length-pos));

        $("#dlg_api_content").val(content);

    });
</script>