export class ApiConfig {
	
    public static getApiUrl() {
        return "{{$myapiaddress}}";
    }
	
	public static ParamUrlencoded(json){
		let ret="1=1";
		for (let i in json) {
			ret+="&"+i+"="+encodeURI(json[i]);
		}
		return ret;
	}
	
}