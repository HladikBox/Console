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
	public function submit($login,$alias,$app_id,$remarks){
		Global $CONFIG,$UID,$productMgr;
		$login=parameter_filter($login);
		$alias=parameter_filter($alias);
		$app_id=parameter_filter($app_id);
		$remarks=parameter_filter($remarks);

		if($this->dbmgr->checkHave("(select a.app_id,b.user_id,a.status from tb_market_app a inner join tb_app b on a.app_id=b.id) a","user_id=$UID and status in ('P','W')")){
			return outResult(-1,"你的已经有其它应用正在审核过程中，请先处理");
		}
		$this->dbmgr->begin_trans();
		$id=$this->dbmgr->getNewId("tb_market_app");
		$sql="insert into tb_market_app (id,app_id,status,created_time,remarks) 
		values ($id,$app_id,'P',now(),'$remarks')";
		$this->dbmgr->query($sql);

		$srcpath=$CONFIG['workspace']['path']."\\$login\\$alias\\";
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

		$productlist=$productMgr->getProductList($login,$alias);
		foreach ($productlist["products"]["product"] as $key => $value) {
			mkdir($despath."product\\".iconv("utf-8", "gbk", $value["name"]),0777,true);
			recurse_copy($srcpath."product\\".iconv("utf-8", "gbk", $value["name"])."\\code",$despath."product\\".iconv("utf-8", "gbk", $value["name"])."\\code");
			recurse_copy($srcpath."product\\".iconv("utf-8", "gbk", $value["name"])."\\code",$despath."product\\".iconv("utf-8", "gbk", $value["name"])."\\imgs");
			recurse_copy($srcpath."product\\".iconv("utf-8", "gbk", $value["name"])."\\code",$despath."product\\".iconv("utf-8", "gbk", $value["name"])."\\docs");
		}

		$this->dbmgr->commit_trans();
		return outResult(0,"提交成功",$id);

	}
 }
 
 $marketMgr=MarketMgr::getInstance();
 $marketMgr->dbmgr=$dbmgr;




?>
