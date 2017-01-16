<?php
/*
 * Created on 2011-2-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */  
 class ProductMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new ProductMgr();
	}

	private function __construct() {
		
	}
	
	public  function __destruct ()
	{
		
	}

    public function productType(){
        $type=Array();
        $type["website"]="网站";
        $type["mobile"]="手机网站";
        $type["cordova"]="Cordova";
        $type["ionicv1"]="Ionic V1(JS)";
        $type["ionicv2"]="Ionic V2(TS)";
        $type["android"]="安卓原生应用";
        $type["object-c"]="iPhone(Object-C)";
        $type["swift"]="iPhone(Swift)";
        $type["windows"]="Windows桌面程序";
        $type["sql"]="数据库";
        $type["deployment"]="部署";
        $type["other"]="其它";

        return $type;
    }

    public function saveProduct($login,$alias,$isnew,$type,$name,$summary,$description){
        Global $CONFIG;
        $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\";
        $productfile=$folder."product.xml";

        $productlist=$this->getProductList($login,$alias);
        
        $isnew=parameter_filter($isnew);
        $type=parameter_filter($type);
        $name=parameter_filter($name);
        $summary=parameter_filter($summary);
        $description=parameter_filter($description);

        if($type==""){
            return outResult(-1,"应用类型不能为空");
        }
        if($name==""){
            return outResult(-1,"应用名称不能为空");
        }
        if($productlist!=null){
            for($i=0;$i<count($productlist["products"]["product"]);$i++){
                if($productlist["products"]["product"][$i]["name"]==$name){
                        if($isnew=="Y"){
                            return outResult(-1,"应用名称已经被使用");
                        }else{
                            $productlist["products"]["product"][$i]["type"]=$type;
                            $productlist["products"]["product"][$i]["summary"]=$summary;
                            $productlist["products"]["product"][$i]["description"]=$description;
                        }
                    }
            }
        }
            
        if($isnew=="Y"){
            $arr=array();
            $arr["type"]=$type;
            $arr["name"]=$name;
            $arr["summary"]=$summary;
            $arr["description"]=$description;
            $productlist["products"]["product"][]=$arr ;
        }
      $data = array('total_stud' => 500);

      // creating object of SimpleXMLElement
      $xml_data = new SimpleXMLElement('<?xml version="1.0"?><root></root>');
      foreach( $productlist as $key => $value ) {
          if ($key=="products") {
            $products=$productlist["products"]["product"];
            $productsnode = $xml_data->addChild("products");
            foreach ($products as $product) {
              $productnode = $productsnode ->addChild("product");
              foreach ($product as $fkey => $fvalue) {
                $productnode->addChild($fkey,htmlspecialchars($fvalue));
              }
            }
          }
      }
      $result = $xml_data->asXML($productfile);
      foreach( $productlist["products"]["product"] as  $value ) {
        mkdir($folder."product\\".iconv('utf-8', 'gbk', $value["name"])."\\"."code", 0777, true);
        mkdir($folder."product\\".iconv('utf-8', 'gbk', $value["name"])."\\"."imgs", 0777, true);
        mkdir($folder."product\\".iconv('utf-8', 'gbk', $value["name"])."\\"."docs", 0777, true);
      }

      return outResult(0,"保存成功","");
    }
    
    public function deleteProduct($login,$alias,$name){
        Global $CONFIG;
        $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\";
        $productfile=$folder."product.xml";

        $productlist=$this->getProductList($login,$alias);
        
        $name=parameter_filter($name);

        $ret=array();

        if($productlist!=null){
            for($i=0;$i<count($productlist["products"]["product"]);$i++){
                if($productlist["products"]["product"][$i]["name"]!=$name){
                       $ret[]=$productlist["products"]["product"][$i];
                    }
            }
        }
        $productlist["products"]["product"]=$ret;
      $data = array('total_stud' => 500);

      // creating object of SimpleXMLElement
      $xml_data = new SimpleXMLElement('<?xml version="1.0"?><root></root>');
      foreach( $productlist as $key => $value ) {
          if ($key=="products") {
            $products=$productlist["products"]["product"];
            $productsnode = $xml_data->addChild("products");
            foreach ($products as $product) {
              $productnode = $productsnode ->addChild("product");
              foreach ($product as $fkey => $fvalue) {
                $productnode->addChild($fkey,htmlspecialchars($fvalue));
              }
            }
          }
      }
      $result = $xml_data->asXML($productfile);
      foreach( $productlist["products"]["product"] as  $value ) {
        mkdir($folder."product\\".iconv('utf-8', 'gbk', $value["name"])."\\"."code", 0777, true);
        mkdir($folder."product\\".iconv('utf-8', 'gbk', $value["name"])."\\"."imgs", 0777, true);
        mkdir($folder."product\\".iconv('utf-8', 'gbk', $value["name"])."\\"."docs", 0777, true);
      }

      return outResult(0,"保存成功","");
    }
  function addChild(&$node,$key,$value){
            if(trim($value)==""){
                $node->addChild($key);
            }else{
                $node->addChild($key,htmlspecialchars($value));
            }
    }

    public function getProductList($login,$alias){
        Global $CONFIG;
        $login=parameter_filter($login);
        $alias=parameter_filter($alias);
        $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\";
        if (!is_dir($folder."product\\")) mkdir($folder."product\\");
        $productfile=$folder."product.xml";
        $productfolder=$folder."product\\";

        $fp = fopen($productfile,"r");
        $str = fread($fp,filesize($productfile));

        $xmlstring = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOBLANKS); 
        $productlist = json_decode(json_encode($xmlstring),true); 
        
        if($productlist["products"]["product"][0]==""&&$productlist["products"]["product"]["name"]!=""){
          $temp=$productlist["products"]["product"];
          $productlist["products"]["product"]=array();
          $productlist["products"]["product"][]=$temp;
        }
        $producttype=$this->productType();
        if($productlist!=null){
            for($i=0;$i<count($productlist["products"]["product"]);$i++){
                $productlist["products"]["product"][$i]["typename"]=$producttype[$productlist["products"]["product"][$i]["type"]];
            }
        }
        $productlist=setArrayNoNull($productlist);
        if($productlist["products"]==""){
            return null;
        }
        return $productlist;
    }


 }
 
 $productMgr=ProductMgr::getInstance();
 $productMgr->dbmgr=$dbmgr;
 
 
 
 
?>