<script type="text/javascript">
    $(document).ready(function(){
		//alert(1);
		//$("#table_modellist").DataTable();
		//alert(2);
		
		$(".sortit").click(function(){
			var tdno=$(this).attr("tdno");
			var modelrow=$(".modelrow");
			for(var i=0;i<modelrow.length-1;i++){
				for(var j=i+1;j<modelrow.length-1-i;j++){
					var x=$($(modelrow[i]).find("td")[tdno]).text();
					var y=$($(modelrow[j]).find("td")[tdno]).text();
					if(x>y){
						$(modelrow[i]).insertAfter($(modelrow[j]));
					}
				}
			}
		});
		
        $("#btnExecuteMysql").click(function(){
            if($(".db_op").length==0){
                info("清先添加模型");
                return;
            }
            $(".db_op").removeClass("hide");
            $(".db_op_th").removeClass("hide");
            $(".db_op").removeClass("ready");
            $(".db_op").addClass("ready");
            $(".db_op").html('<i class="fa fa-spinner fa-spin"></i>');
            $("#btnExecuteMysql").attr("disabled",true);
            batchExecute();
        });
        if ($("#table_modellist tr").length <= 2) {
            $("#txtConfirmRecommModelList").text("");
            $("#dlgRecommModelList").modal("show");
        }
        $("#recomm_modellist_select_all").change(function(){
            var checked=$("#recomm_modellist_select_all").prop("checked");
            $("input[name='recomm_modellist']").prop("checked",checked);
        });
        $("#btnConfirmRecommModelList").click(function(){
            var models=Array();
            $("input[name='recomm_modellist']:checked").each(function(){
                models.push($(this).val());
            });
            if(models.length==0){
                $("#txtConfirmRecommModelList").text("请至少选择一个模型");
            }

            var json = {"action":"applycommmodel", app_id: "{{$appinfo.id}}","models":models};
            $("#btnConfirmRecommModelList").attr("disabled", true);
            getJSON("{{$rootpath}}api/model", json, function (data) {
                if (data.code == 0) {
                    location.reload();
                } else {
                    $("#txtConfirmRecommModelList").text(data.result);
                }
            }, function () {
                $("#btnConfirmRecommModelList").attr("disabled", false);
            });
        });
        $("#btnCreateModel").click(function(){
            $("#txtConfirmCreateModel").text("");
            $("#dlgCreateModel").modal("show");

        });
        $("#btnConfirmCreateModel").click(function(){
            var modelname=$("#cm_modelname").val().toLowerCase();
            var name=$("#cm_name").val();
            var tablename=$("#cm_tablename").val();
            //"^[A-Za-z0-9]+$"
            if($.trim(modelname)==""){
                $("#txtConfirmCreateModel").text("唯一标示不能为空");
                return;
            }
            var pattern =  /^[A-Za-z0-9]+$/;
            if(!pattern.test(modelname)){
                $("#txtConfirmCreateModel").text("唯一标示仅能使用数字加英文");
                return;
            }
            if(getModelIn(modelname)){
                $("#txtConfirmCreateModel").text("唯一标示名已经存在");
                return;
            }
            if($.trim(name)==""){
                $("#txtConfirmCreateModel").text("模型名称不能为空");
                return;
            }
            if($.trim(tablename)==""){
                $("#txtConfirmCreateModel").text("数据表名不能为空");
                return;
            }
            var method=$("input[name='optCreateModel']:checked").val();
            var srcmodel="";
            if(method=="C"){
                srcmodel=$("#ddlRecommModelList").val();
            }else if(method=="E"){
                srcmodel=$("#ddlExistModelList").val();
            }

            var json = {"action":"createmodel", app_id: "{{$appinfo.id}}","modelname":modelname,"name":name,"method":method,"srcmodel":srcmodel,"tablename":tablename};
            $("#btnConfirmCreateModel").attr("disabled", true);
            getJSON("{{$rootpath}}api/model", json, function (data) {
                if (data.code == 0) {
                    window.location.href="/apps/model?app_id={{$appinfo.id}}&model="+modelname;
                } else {
                    $("#txtConfirmCreateModel").text(data.result);
                }
            }, function () {
                $("#btnConfirmCreateModel").attr("disabled", false);
            });
        });
    });
    function batchExecute(){
        if($(".db_op.ready").length==0){
            $("#btnExecuteMysql").attr("disabled",false);
            return;
        }
        var dp=$(".db_op.ready:first");
        var modelname=dp.attr("modelname");
            var json={action:"executesql",app_id:"{{$appinfo.id}}",modelname:modelname};
            getJSON("{{$rootpath}}api/model", json, function (data) {
                //alert(1);
                dp.removeClass("ready");
                setTimeout("batchExecute()", 1000 );
                    if(data.code==0){
                      dp.html('<span class="text-green">执行成功</span>');
                    }else if(data.code==1){
                      var ret="";
                      for(var i=0;i<data.return.length;i++){
                        ret+="<p>"+data.return[i]+"</p>";
                      }
                      dp.html('<span class="text-yellow">'+data.result+ret+'</span>');
                    }else{

                      var ret="";
                      for(var i=0;i<data.return.length;i++){
                        ret+="<p>"+data.return[i]+"</p>";
                      }
                      dp.html('<span class="text-red">'+data.result+ret+'</span>');
                    }
                });
        
    }
    function getModelName(modelname) {
        return $("#model_" + modelname + " .modelname").text();
    }
    function getModelIn(modelname) {
        return $("#model_" + modelname + " .modelname").length>0;
    }
</script>