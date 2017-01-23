<?php
/*
 * Created on 2010-5-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 include_once ROOT.'/classes/datamgr/product.cls.php';
 class MarketMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public $productMgr=null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new MarketMgr();
	}

	private function __construct() {
		
		//$id=parameter_filter($user["id"]);
        //$query = $this->dbmgr->query($sql);
        //$result = $this->dbmgr->fetch_array_all($query);
        //return $result;

	}
	
	public  function __destruct ()
	{
		
	}

    public function getMarketApp($id){
        $id=$id+0;
        $sql="select a.*,b.name app_type_name from tb_market_app a 
        inner join tb_app_type b on a.app_type=b.id
         where a.status='A' and a.id=$id ";
		$query=$this->dbmgr->query($sql);
        $result = $this->dbmgr->fetch_array($query);
        return $result;
    }

    public function getOnlineAppList(){
        $sql="select *,ifnull(buycount,0) buycount from tb_market_app where status='A' order by buycount desc ";
		$query=$this->dbmgr->query($sql);
        $result = $this->dbmgr->fetch_array_all($query);
        return $result;
    }

	public function getSubmitCode($id){
		$sql="select * from tb_market_app where status='W' and id=$id";
		$query=$this->dbmgr->query($sql);
        $result = $this->dbmgr->fetch_array($query);
        $app_id=$result["app_id"]+0;
        if($app_id==0){
        	echo "nodata";
        	exit;
        }
        return $folder=ROOT."\\submit_apps\\$app_id";
	}
	public function discard(){
		$sapp=$this->getSubmittedApp();
		if($sapp["status"]=="F"||$sapp["status"]=="P"){
			$id=$sapp["id"]+0;
			$sql="update tb_market_app set status='D' where id=$id";
			$query=$this->dbmgr->query($sql);
			return outResult(0,"撤回成功",$id);
		}
		else{
			return outResult(-1,"现在的状态不能撤回");
		}

	}
    public function getSubmittedApp(){
        
        Global $UID;
        $sql="select a.* from tb_market_app a inner join tb_app b on a.app_id=b.id where b.user_id=$UID and a.status in ('P','W','S','F') ";
        $query=$this->dbmgr->query($sql);
        $result = $this->dbmgr->fetch_array($query);
        return $result;
    }
	public function submit($login,$alias,$app_id,$remarks){
		Global $CONFIG,$UID,$productMgr;
		$login=parameter_filter($login);
		$alias=parameter_filter($alias);
		$app_id=parameter_filter($app_id);
		$remarks=parameter_filter($remarks);

		$sapp=$this->getSubmittedApp();
		if($sapp["status"]=="W"||$sapp["status"]=="P"||$sapp["status"]=="S"){
			return outResult(-1,"你的已经有其它应用正在审核过程中，请先处理");
		}
		if($this->dbmgr->checkHave("tb_market_app","app_id=$app_id and status<>'D' and status<>'F'")){
			return outResult(-1,"不能重复提交该应用");
		}
		$this->dbmgr->begin_trans();
		if($sapp["status"]=="F"){
			$sql="update tb_market_app set app_id=$app_id,status='P',created_time=now(),remarks='$remarks' 
			where id= ".$sapp["id"];
		}else{
			$id=$this->dbmgr->getNewId("tb_market_app");
			$sql="insert into tb_market_app (id,app_id,status,created_time,remarks) 
			values ($id,$app_id,'P',now(),'$remarks')";
		}


		$this->dbmgr->query($sql);

		$srcpath=$CONFIG['workspace']['path']."\\$login\\$alias\\";
		$copypath=ROOT."\\workspace_copy\\";
		$despath=ROOT."\\submit_apps\\$app_id\\";
		if(!file_exists($despath)){
	        mkdir($despath,true);
	    }else{
	        delDir($despath);
	    }
	    recurse_copy($srcpath."api",$despath."api");
	    recurse_copy($srcpath."datamgr",$despath."datamgr");
	    recurse_copy($srcpath."js",$despath."js");
	    recurse_copy($srcpath."model",$despath."model");
	    recurse_copy($srcpath."modelmgr",$despath."modelmgr");
		copy($srcpath."api.xml",$despath."api.xml");
		copy($srcpath."menu.xml",$despath."menu.xml");
		copy($srcpath."product.xml",$despath."product.xml");
		copy($srcpath."product.xml",$despath."product.xml");
		copy($copypath."config.inc.php",$despath."config.inc.php");

		$productlist=$productMgr->getProductList($login,$alias);
		foreach ($productlist["products"]["product"] as $key => $value) {
			mkdir($despath."product\\".encode( $value["name"]),0777,true);
			recurse_copy($srcpath."product\\".encode( $value["name"])."\\code",$despath."product\\".encode( $value["name"])."\\code");
			recurse_copy($srcpath."product\\".encode( $value["name"])."\\code",$despath."product\\".encode( $value["name"])."\\imgs");
			recurse_copy($srcpath."product\\".encode( $value["name"])."\\code",$despath."product\\".encode( $value["name"])."\\docs");
		}

		$this->dbmgr->commit_trans();
		return outResult(0,"提交成功",$id);

	}

    public function setPriceAndOnline($price,$score){
        Global $appMgr,$UID;
        $price=$price+0;
        if($price<0){
           $price=0;
        }
        $price=intval($price);

        $score=$score+0;
        if($score<0){
           $score=0;
        }
        $score=$score>intval($score)?intval($score)+1:intval($score);

        $sapp=$this->getSubmittedApp();

        $appinfo=$appMgr->getAppInfo($UID,$sapp["app_id"]);
        $app_name=parameter_filter($appinfo["name"]);
        $app_type=parameter_filter($appinfo["type"]);
        $app_description=parameter_filter($appinfo["description"]);


		if($sapp["status"]!="S"){
			return outResult(-1,"没有可设置价格的应用");
		}
        $id=$sapp["id"];
         $sql="update tb_market_app set price=$price,status='A',score=$score,app_name='$app_name',app_type='$app_type',app_description='$app_description' where id=$id and status='S' ";
        $this->dbmgr->query($sql);
        
		return outResult(0,"提交成功",$id);
    }

    public function getSubmitAppProductListDetail($app_id){
        Global $CONFIG,$productMgr;
        $app_id=$app_id+0;
        $productlist=$this->getSubmitAppProductList($app_id);
        $folder=ROOT."\\submit_apps\\$app_id\\product\\";
        //dirsize
        for($i=0;$i<count($productlist["products"]["product"]);$i++){
          $value=$productlist["products"]["product"][$i];
          $productlist["products"]["product"][$i]["codesize"]=dirsize($folder.encode($value["name"])."\\code");
          $imgslist=$productMgr->getFileList($folder.encode( $value["name"])."\\imgs",array("jpg","png","gif","ico"));
          $productlist["products"]["product"][$i]["imgscount"]=count($imgslist);
          $productlist["products"]["product"][$i]["imgsfiles"]=$imgslist;

          $docslist=$productMgr->getFileList($folder.encode( $value["name"])."\\docs",array("doc","docx","ppt","pptx","pdf","xls","xlsx","txt"));
          $productlist["products"]["product"][$i]["docscount"]=count($docslist);
          $productlist["products"]["product"][$i]["docsfiles"]=$docslist;
        }
        return $productlist;
      }

      
    public function getSubmitAppProductList($app_id){
        Global $CONFIG,$productMgr;
        $app_id=$app_id+0;
        $folder=ROOT."\\submit_apps\\$app_id\\";
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
        $producttype=$productMgr->productType();
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
 
 $marketMgr=MarketMgr::getInstance();
 $marketMgr->dbmgr=$dbmgr;




?>
