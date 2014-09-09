<?php
/**
 * 
 * @author wanggsh0825
 *
 */
class PACACHE
{
    private $epid=null;
    private $uid=null;
    private $cachepath=null;
		
    public function __construct($epid,$uid)
	{
        $this->epid = $epid;
        $this->uid  = $uid;
        $path           = "D:/interface/cache";
        if(!file_exists($path))
		{
            mkdir($path,0777);
		}
        $path.= ("/".$epid);
        if(!file_exists($path))
		{
            mkdir($path,0777);
		}
		$this->cachepath = $path."/".$uid.".cah";
    }
    
    /**
     * 检查缓存数据是否过期，过期则删除缓存文件
     * @param unknown $interval
     * @return boolean
     */
    private function isExpired($interval)
	{	
        if(!file_exists($this->cachepath))
		{
			echo "o";
            return true;
		}
        elseif((time()-filemtime($this->cachepath))>$interval)
		{
			echo "t";
            unlink($this->cachepath);
            return true;
        }
        elseif(filesize($this->cachepath)==0)
		{
			echo "th";
            unlink($this->cachepath);
            return true;
        }
        else
		{
			echo "f";
            return false;
		}
    }
    
    /**
     * 清除指定企业ID的所有缓存文件
     */
    public function clearCustomerCache()
	{
        $pathArr = pathinfo($this->cachepath);
        $dir = $pathArr[dirname];
        $dh = opendir($dir);
        while( ($file=readdir($dh)) != false)
        {
            if($file=="." ||$file=="..") continue;
            else unlink($dir."/".$file);
        }
        closedir($dh);
        //rmdir($dir);
    }
	
	//模板缓存文件方法
	public function getPaCacheNew($dsn,$interval)
	{	
        if($this->isExpired($interval))
		{
			//echo "this is new";
    		return $this->doPaCacheNew($dsn);
		}
		else
		{
			//echo "this is cache";
			$contents = file_get_contents($this->cachepath);
			$array = json_decode($contents,true);
			return $array;
		}
    }
	
	private function doPaCacheNew($dsn)
	{
		global $dsn;
	    $arr	= array();
		$cmd 	= "sql";//sql
		$rs  = odbc_exec($dsn,$cmd);	
		while(odbc_fetch_row($rs))
		{
			$arr;//结果集数组
		}
	    $json = json_encode($arr);
	    file_put_contents($this->cachepath,$json);
	    return $arr;
	}
}
?>