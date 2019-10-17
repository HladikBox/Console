
<script>
    $(document).ready(function () {
        function functionBind() {
            $('.can-select').unbind('mouseenter').unbind('mouseleave');
            $(".can-select").hover(function () {
                $(this).addClass("bg-gray");
            }, function () {
                $(this).removeClass("bg-gray");
            });
            $('.btnAddSubMenu').unbind('click');
            $(".btnAddSubMenu").click(function () {
                var parent = $(this).parent();
                $(".can-select").removeClass("bg-gray-active");
                var newobj = $('<li class="can-select bg-gray-active"><a href="#"><span class="text-primary">##</span><i class="fa fa-times pull-right deletemenu"></a></li>');
                newobj.insertBefore(parent);
                functionBind();
                loadMenuItem(newobj);
            });
            $(".can-select").unbind("click");
            $(".can-select").click(function () {
                $(".can-select").removeClass("bg-gray-active");
                $(this).addClass("bg-gray-active");
                loadMenuItem($(this));
            });
            $(".deletemenu").unbind("click");
            $(".deletemenu").click(function(){
                if($(this).parent().attr("main")=="1"){
                    
                    var index=$(this).parent().index();
                    $(this).parent().remove();
                    var td=$($("#submenu_tr td")[index]);
                    td.remove();


                }else{
                    $(this).parent().parent().remove();
                }
            });
        }
        functionBind();
        $("#btnAddMainMenu").click(function () {
            $(".can-select").removeClass("bg-gray-active");
            var td = $('<th  class="can-select bg-gray-active mainmenu" main="1"><a href="#"><span class="text-success">###</span></a><a href="#" class="pull-right deletemenu"><i class="fa fa-times "></i></a></th>');
            td.insertBefore("#lastMainMenu");
            loadMenuItem(td);
            $('<td class="nav-tabs-custom submenu"><ul class="nav nav-tabs nav-stacked"><li ><button class="btn btn-primary btnAddSubMenu"><i class="fa fa-plus"></i> 菜单</button></li></ul></td>').insertBefore("#lastMainMenuTD");

            functionBind();
        });
        $("#mainmenu_module").change(function () {
            $(".can-select.bg-gray-active").attr("module", $(this).val());
        });
        $("#mainmenu_name").change(function () {
            $(".can-select.bg-gray-active").children("a").children("span").text($(this).val());
        });
        $("#mainmenu_onlyadmin").change(function () {
            $(".can-select.bg-gray-active").attr("onlyadmin", $(this).prop("checked") ? "1" : "0");
        });
        $("#submenu_model").change(function () {
            $(".can-select.bg-gray-active").attr("model", $(this).val());
            $(".can-select.bg-gray-active").children("a").children("span").text($(this).children("option:checked").text());
        });
        $("#submenu_onlyadmin").change(function () {
            $(".can-select.bg-gray-active").attr("onlyadmin", $(this).prop("checked") ? "1" : "0");
        });
        $("#btnSubmitMenu").click(function () {
            

            var mainmenu = Array();
            $("th.mainmenu").each(function (index) {
                var submenuTD = $($("td.submenu")[index]).children("ul").children("li");
                var main = { module: $(this).attr("module"), name: $(this).children("a").children("span").text(), onlyadmin: $(this).attr("onlyadmin") };
                var subs = Array();
                for (var i = 0; i < submenuTD.length - 1; i++) {
                    var submenu=$(submenuTD[i]);
                    var sub = { model: submenu.attr("model"), name: submenu.children("a").children("span").text(), onlyadmin: submenu.attr("onlyadmin") };
                    subs.push(sub);
                }
                main.submenus = { submenu: subs };
                mainmenu.push(main);
            });
            
            var menu = { "mainmenus": { "mainmenu": mainmenu } };

            
            var json = {"action":"submitmenu", app_id: "{{$appinfo.id}}","menu":menu};
            $("#btnSubmitMenu").attr("disabled", true);
            getJSON("{{$rootpath}}api/apps", json, function (data) {
                if (data.code == 0) {
                    info("提交成功，请登陆数据中心进行测试");
                } else {
                    error(data.result);
                }
            }, function () {
                $("#btnSubmitMenu").attr("disabled", false);
            });
        });
        function loadMenuItem(obj) {
            var is_main = obj.attr("main") ? "1" : "0";
            if (is_main == "1") {
                $("#divMainMenu").removeClass("hide");
                $("#divSubMenu").addClass("hide");
                $("#mainmenu_module").val(obj.attr("module"));
                $("#mainmenu_name").val(obj.text());
                $("#mainmenu_onlyadmin").prop("checked", obj.attr("onlyadmin") == "1");
            } else {

                $("#divSubMenu").removeClass("hide");
                $("#divMainMenu").addClass("hide");
                $("#submenu_model").val(obj.attr("model"));
                $("#submenu_onlyadmin").prop("checked", obj.attr("onlyadmin") == "1");

            }
        }
        $(".can-select:first").click();
        $(".menu_modelname").each(function () {
            $(this).text(getModelName($(this).text()));
        });
    });
</script>