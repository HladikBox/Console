var dataapi_link="{{dataapi_link}}";//填写你自己服务器的服务器域名信息


var apiconfig_beforeSend=function(XHR){
	var accesstoken=localStorage.accesstoken;
	if(!(accesstoken)){
		accesstoken=newGuid();
		//alert(accesstoken);
		localStorage.accesstoken=accesstoken;
	}
	XHR.setRequestHeader("accesstoken", accesstoken);
};

var apiconfig_complete=function(XHR, TS){
	//alert("co");
};
var newGuid=function()
{
    var guid = "";
    for (var i = 1; i <= 32; i++){
      var n = Math.floor(Math.random()*16.0).toString(16);
      guid +=   n;
      if((i==8)||(i==12)||(i==16)||(i==20))
        guid += "-";
    }
    return guid;    
};