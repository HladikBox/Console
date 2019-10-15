<script type="text/javascript">
    $(document).ready(function(){
    	$(".loadComponentReadMe").click(function(){

    		var id=$(this).attr("pluginid");
    		var url="{{$rootpath}}appcomponents/"+id+"/readme.md";
    		$(this).parent().load(url);
    	});
    	$(".btnComponentInstall").click(function(){

    		var id=$(this).attr("pluginid");
    		var url="{{$rootpath}}appcomponents/"+id+"/install.php?app_id={{$appinfo.id}}";
    		getJSON(url,{},function(data){
    			info("添加成功，请手动刷新页面");
    		});
    	});



    });
</script>