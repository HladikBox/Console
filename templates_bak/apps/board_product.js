
<script>
    $(document).ready(function () {
        //$("#pd_description").wysihtml5();
        $("#pd_code").click(function () {
            window.open("/api/product?app_id={{$appinfo.id}}&action=upload&type=code&product=" + $("#pd_name").val());
        });
        $("#pd_imgs").click(function () {
            window.open("/api/product?app_id={{$appinfo.id}}&action=upload&type=imgs&product=" + $("#pd_name").val());
        });
        $("#pd_docs").click(function () {
            window.open("/api/product?app_id={{$appinfo.id}}&action=upload&type=docs&product=" + $("#pd_name").val());
        });
        $("#btnAddProduct").click(function () {

            $(".itemproduct").removeClass("onselect");
            $(".uploadcontrol").addClass("hide");

            $("#pd_name").attr("disabled", false);
            var isnew = $("#pd_isnew").val("Y");
            var type = $("#pd_type").val("");
            var name = $("#pd_name").val("");
            var summary = $("#pd_summary").val("");
            $(".textareareset").html('<textarea id="pd_description" class="textarea" placeholder="请在此处编写你的应用详情于安装方法" style="width: 100%; height: 350px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>');
            $("#pd_description").val("");
            $("#pd_description").wysihtml5();
            $("#txtConfirmAddProduct").text("");
            $("#dlgAddProduct").modal({ backdrop: false, keyboard: false, show: true });
        });
        $(document).on("click", ".itemproduct", function () {
            $(".itemproduct").removeClass("onselect");
            $(".uploadcontrol").removeClass("hide");
            $(this).addClass("onselect");
            var type = $(this).find(".product_type").val();
            var name = $(this).find(".product_name").val();
            var summary = $(this).find(".product_summary").val();
            var description = $(this).find(".product_description").val();

            $("#pd_name").attr("disabled", true);
            $("#pd_isnew").val("N");
            $("#pd_type").val(type);
            $("#pd_name").val(name);
            $("#pd_summary").val(summary);
            $(".textareareset").html('<textarea id="pd_description" class="textarea" placeholder="请在此处编写你的应用详情于安装方法" style="width: 100%; height: 350px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>');
            $("#pd_description").val(description);
            $("#pd_description").wysihtml5();
            //a?
            //product_editor.text(description);
            //$("#pd_description").wysihtml5();
            //alert(description);
            $("#txtConfirmAddProduct").text("");
            $("#dlgAddProduct").modal({ backdrop: false, keyboard: false, show: true });
        });
        $(".btnDeleteProduct").click(function () {

            var caa = $(this);
            var name = $(this).parent().parent().parent().parent().find(".product_name").val();
            var json = { action: "deleteproduct", app_id: "{{$appinfo.id}}", name };
            getJSON("/api/product", json, function (data) {
                if (data.code == 0) {
                    caa.parent().parent().parent().parent().remove();
                } else {
                    error(data.result);
                }
            });
        });
        $("#btnConfirmAddProduct").click(function () {

            $("#txtConfirmAddProduct").text("");

            var isnew = $("#pd_isnew").val() ;
            var type = $("#pd_type").val();
            var name = $("#pd_name").val();
            var summary = $("#pd_summary").val();
            var description = $("#pd_description").val();

            if (type == "") {
                $("#txtConfirmAddProduct").text("请选择应用类型");
                return;
            }
            if ($.trim(name) == "") {
                $("#txtConfirmAddProduct").text("请输入应用名称");
                return;
            }
            var json = { action: "saveproduct",app_id:"{{$appinfo.id}}", isnew: isnew, type: type, name: name, summary: summary, description: description };
            
            $("#btnConfirmAddProduct").attr("disabled", true);
            $("#btnConfirmAddProduct").text("上传中，请不要关闭页面");
            $("#dlgAddProduct .iclose").attr("disabled", true);

            getJSON("/api/product", json, function (data) {
                if (data.code == 0) {
                    //动态添加一个XX
                    if (isnew == "Y") {
                        
                        var str = $('<div class="col-lg-3 col-xs-6">'
                                    +'<div class="small-box bg-aqua">'
                                    +'<div class="inner">'
                                    +'<h3 class="product_typename_txt"></h3>'
                                    + '<p class="product_name_txt"></p>'
                                    + '<p><small  class="product_summary_txt"></small></p>'
                                    +'</div>'
                                    +'<a href="#" class="small-box-footer itemproduct onselect">'
                                    +'编辑 <i class="fa fa-arrow-circle-right"></i>'
                                    +'<input class="product_type" type="hidden" value="{{$rs.type}}" />'
                                    +'<input class="product_name" type="hidden" value="{{$rs.name}}" />'
                                    +'<input class="product_summary" type="hidden" value="{{$rs.summary}}" />'
                                    +'<input class="product_description" type="hidden" value="{{$rs.description}}" />'
                                    +'</a>'
                                    +'</div>'
                                    + '</div>').appendTo(".listproduct");
                        $(".uploadcontrol").removeClass("hide");
                    }

                    $(".itemproduct.onselect").find(".product_type").val(type);
                    $(".itemproduct.onselect").find(".product_summary").val(summary);
                    $(".itemproduct.onselect").find(".product_description").val(description);

                    $(".itemproduct.onselect").parent().find(".product_typename_txt").text($("#pd_type").find("option:selected").text());
                    $(".itemproduct.onselect").parent().find(".product_name_txt").text(name);
                    $(".itemproduct.onselect").parent().find(".product_summary_txt").text(summary);

                    $("#btnConfirmAddProduct").attr("disabled", false);
                    $("#btnConfirmAddProduct").text("保存");
                    $("#dlgAddProduct .iclose").attr("disabled", false);

                    $("#pd_name").attr("disabled", true);
                    $("#pd_isnew").val("N");


                    //$("#dlgAddProduct").modal("hide");
                } else {
                    $("#btnConfirmAddProduct").attr("disabled", false);
                    $("#btnConfirmAddProduct").text("保存");
                    $("#dlgAddProduct .iclose").attr("disabled", false);
                    $("#txtConfirmAddProduct").text(data.result);
                }
            });

        });
    });
</script>