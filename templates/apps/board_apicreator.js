<script src="/plugins/ace-builds/src/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/plugins/ace-builds/src/ext-language_tools.js"></script>



<script type="text/javascript">
    
    apieditor=null;

    $(document).ready(function () {

        $("#btnGroupBottomApiCreator").hide($(".apicreator_item").length<20);



        $(".btnSaveApiList").click(function () {
            var apis = Array();
            $("#table_apicreatorlist .api_item").each(function () {
                var active = $(this).find(".api_active").prop("checked")?"1":"0";
                if(active=="1"){
                    var api = {
                        "model": $.trim($(this).find(".api_model").text().toLowerCase()),
                        "func": $.trim($(this).find(".api_func").text().toLowerCase())
                    };
                    apis.push(api);
                }
            });
            var json = { "action": "save", app_id: "{{$appinfo.id}}", apis: apis };
            $(".btnSaveApiList").attr("disabled", true);
            getJSON("{{$rootpath}}api/api", json, function (data) {
                if (data.code == 0) {

                    info("保存成功");
                    
                } else {
                    warning(data.result);
                }
            }, function () {
                $(".btnSaveApiList").attr("disabled", false);
            });
        });
        $("#apicreator_checkbox_all").change(function () {
            $("#apicreator_checkbox_all .api_active").prop("checked", $("#apicreator_checkbox_all").prop("checked"));
        });

        $(".btnApiCreatorCodeSave").click(function(){
            saveCode();
        });
        $("#btnApiCreatorCodeReset").click(function(){
            var model=$("#dlg_apicreator_model").val();
            var func=$("#dlg_apicreator_func").val();
            codeReset(model,func);
        });

        $("#btnApiCreatorCodeCopy").click(function(){
            var model=$("#ddlQuickCopy").find("option:selected").attr("model");
            var func=$("#ddlQuickCopy").find("option:selected").attr("func");
            var id="func_"+func;
            var codestr=$("#"+id).val();
            codestr=codestr.replace("{MODEL}",model);
            apieditor.setValue("<?php \r" +codestr + "\r?>", -1);
        });


    });
    function saveCode(){
        var content=apieditor.getValue();
        content=content.substring(5,content.length-2);
        var json={
            action:"setapicontent",
            app_id:"{{$appinfo.id}}",
            model:$("#dlg_apicreator_model").val(),
            func:$("#dlg_apicreator_func").val(),
            content:content
        };
        $(".btnApiCreatorCodeSave").attr("disabled", true);
        getJSON("{{$rootpath}}api/api", json, function (data) {
            //alert(JSON.stringify(data));
            if(data.code=="0"){

                //$("#dlgApiCoding").modal("hide");

            }else{
                error("代码保存失败，请检查并重写");
            }

        },function(){
            $(".btnApiCreatorCodeSave").attr("disabled", false);
        });
    }
    function codeReset(model,func){
        if(model==""&&func==""){
            apieditor.setValue("<?php \r" +"" + "\r?>", -1);
        }else{
            getJSON("{{$rootpath}}api/api", 
                {
                    action:"getapicontent",
                    app_id:"{{$appinfo.id}}",
                    model:model,
                    func:func
                }, function (data) {
                    apieditor.setValue("<?php \r" + data + "\r?>", -1);
                });
        }

    }
    function openApiCreatorEditor(model,func){
        var apiID="#apicreator_"+model+"_"+func;
        var api=$(apiID);
        var isnew=(model==""||func=="");
        $("#txtApiCreatorConfirm").text("");
        $("#dlg_api_description").prop("disabled", false);


        $("#dlg_apicreator_content").val("<?php \r"+""+"\r?>");
        if(apieditor==null){
            apieditor = ace.edit("dlg_apicreator_content");
            apieditor.setOptions({
                enableBasicAutocompletion: true,
                enableSnippets: true,
                enableLiveAutocompletion: true
            });

            apieditor.setTheme("ace/theme/monokai");
            apieditor.session.setMode("ace/mode/php");
        }


        $("#dlgApiCreatorEditor").modal("show");
        if(isnew){
            $(".btnApiCreatorCodeSave").hide();
            $("#dlg_apicreator_model").prop("disabled",false);
            $("#dlg_apicreator_func").prop("disabled",false);
            $("#dlg_apicreator_model").val("");
            $("#dlg_apicreator_func").val("");
            $("#dlg_apicreator_description").val("");
        }else{
            $(".btnApiCreatorCodeSave").show();
            $("#dlg_apicreator_model").prop("disabled",true);
            $("#dlg_apicreator_func").prop("disabled", true);
            $("#dlg_apicreator_model").val(model);
            $("#dlg_apicreator_func").val(func);
            $("#dlg_apicreator_description").val(api.find(".api_description").text());
            //apieditor.setValue("<?php \r" + $("#dlg_api_code").text() + "\r?>", -1);
        }
        codeReset(model,func);

        $("#btnApiCreatorConfirm").unbind("click");
        $("#btnApiCreatorConfirm").click(function(){
            if(isnew){
                model=$.trim($("#dlg_apicreator_model").val().toLowerCase());
                func=$.trim($("#dlg_apicreator_func").val().toLowerCase());

                var pattern =  /^[A-Za-z0-9]+$/;
                if($.trim(model)==""||!pattern.test(model)){
                    $("#txtApiCreatorConfirm").text("模块仅能使用英文且不能为空");
                    return;
                }
                if($.trim(func)==""||!pattern.test(func)){
                    $("#txtApiCreatorConfirm").text("方法仅能使用英文且不能为空");
                    return;
                }
            }
            var description=$.trim($("#dlg_apicreator_description").val());
            if(description.length<5){
                $("#txtApiCreatorConfirm").text("请填写不少于5个字的接口用途描述");
                return;
            }

            getJSON("{{$rootpath}}api/api", 
                {
                    action:"saveapi",
                    app_id:"{{$appinfo.id}}",
                    model:model,
                    func:func,
                    description:description
                }, function (data) {
                    
                    if(data.code=="0"){
                       
                        if (isnew) {

                            var newrow = $('<tr id="apicreator_' + model + '_' + func + '"  class="api_item">' +
                            '<td><input type="checkbox" class="api_active" ></td>' +
                            '<td><a target="_blank" class="api_testurl" href="{{$Config.workspace.domain}}/{{$User.login}}/{{$appinfo.alias}}/api/' + model + '/' + func + '">/api/' + model + '/' + func + '</a></td>' +
                            '<td class="api_model">' + model + '</td>' +
                            '<td class="api_func">' + func + '</td>' +
                            '<td class="api_description">' + description + '</td>' +
                            '<td>' +
                            '<a href="javascript:openApiCreatorEditor(\'' + model + '\',\'' + func + '\');" ><i class="fa fa-pencil-square-o"></i></a>' +
                            '</td>' +
                            '</tr>');

                            $("#table_apicreatorlist").append(newrow);
                        } else {
                            api.find(".api_description").text(description);
                        }
                        saveCode();
                        $("#dlgApiCreatorEditor").modal("hide");

                    }else{

                        alert(data);

                    }

                });
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
            $("#dlg_apicreator_content").val("<?php \r"+data+"\r?>");
            if(apieditor==null){
                apieditor = ace.edit("dlg_apicreator_content");
                apieditor.setOptions({
                    enableBasicAutocompletion: true,
                    enableSnippets: true,
                    enableLiveAutocompletion: true
                });

                apieditor.setTheme("ace/theme/monokai");
                apieditor.session.setMode("ace/mode/php");
            }else{
                apieditor.setValue("<?php \r" + data + "\r?>", -1);
            }
            
        });

    }


    $("#apiCodingDescription b nousenow because of php editor").dblclick(function(){
        var linecode="\n"+$(this).text()+"\n";

        var el = $("#dlg_apicreator_content").get(0);
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

        var content=$("#dlg_apicreator_content").val();
        content=$.trim(content.substr(0,pos)+linecode+content.substr(pos,content.length-pos));

        $("#dlg_apicreator_content").val(content);

    });
</script>