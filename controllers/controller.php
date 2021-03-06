<?php
class Controller{
	protected $recentHouses=array();
	protected $cur_path='view/';
	protected $page_css=array();
	protected $page_html=array();
	protected $page_content=array();
	protected $page_js=array();
	private $models;
	public function __construct(){
	}
	public function redirect($page='app_main'){
		$this->cur_path='view/';
		$this->page_css=array();
		$this->page_html=array();
		$page_content=array();
		$this->page_js=array();

		if($page==='login'||!isset($_SESSION['userId'])||$_SESSION['userId']==''||!isset($_SESSION['userType'])||$_SESSION['userType']==''){
			$this->cur_path.='app/';
			$this->getFiles($this->cur_path.'login/');
		}
		elseif(substr($page,0,3)==='app'){
			$this->cur_path.='app/';
			$this->getFiles($this->cur_path.'utils');
			
			if($page==="app_main"){
				//load recent house
				$recentHouses=array();
				$this->page_content[]=$this->cur_path.'main';
			}
			elseif($page==="app_house"){
				$this->page_content[]=$this->cur_path.'house';
			}
			elseif($page==="app_floor"){
				$this->page_content[]=$this->cur_path.'floor';
			}
		}
		elseif(substr($page,0,3)==='web') {
			$this->cur_path.='web/';

			if( $page==="web_sum" ) {
				$this->getFiles($this->cur_path.'web_sum');	
			}
		}
		require realpath('view/structure.php');
	}
	public function load($path){
		$content_html=array();
		$content_css=array();
		$content_js=array();
		$this->importFolder($path,$content_html,$content_css,$content_js);
		foreach($content_css as $css){
			echo "<link rel='stylesheet' type='text/css' href='$css'>\n";
		}
		foreach($content_html as $html){
			include "$html";
		}
		foreach($content_js as $js){
			echo "<script defer src='$js'></script>\n";
		}
	}
	private function getFiles($path){
		$html=array();
		$css=array();
		$js=array();
		$this->importFolder($path,$html,$css,$js);
		$this->page_html=array_merge($this->page_html,$html);
		$this->page_css =array_merge($this->page_css,$css);
		$this->page_js  =array_merge($this->page_js,$js);
	}
	private function importFolder($path,&$html,&$css,&$js){
		$html=array_merge($html,glob("$path{/*.html,/*.php}", GLOB_BRACE));
		$css =array_merge($css ,glob("$path{/*.css}", GLOB_BRACE));
		$js  =array_merge($js  ,glob("$path{/*.js}", GLOB_BRACE));
		//foreach ($css as $h) echo "$h.<br/>";
		//echo $path."<br/>";
		$dirs = glob($path.'/*', GLOB_ONLYDIR);
		if (count($dirs) > 0) {
			foreach ($dirs as $dir) $this->importFolder($dir,$html,$css,$js);
		}
	}
}
?>
