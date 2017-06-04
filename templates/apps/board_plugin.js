<script type="text/javascript">
    $(document).ready(function(){
    	$(".loadPlugInReadMe").click(function(){

    		var id=$(this).attr("pluginid");
    		var url="{{$rootpath}}appplugins/"+id+"/readme.md";
    		$(this).parent().load(url);
    	});
    	$(".btnPluginInstall").click(function(){

    		var id=$(this).attr("pluginid");
    		var url="{{$rootpath}}appplugins/"+id+"/install.php?app_id={{$appinfo.id}}";
    		getJSON(url,{},function(data){
    			if(data.code==0){
    				$("#plugin_"+id+" .pluginInstallStatus").html('<span class="text-success"><i class="fa fa-check "></i>已安装</span>');
    			}
    		});
    	});



    });
</script>