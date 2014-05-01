<?php
	/**
    * Class Jet_Request
    * category:Jet Framework
    * singleton class
    * 
    * @copyright 2008-12-6 Jason Chuh zhuqling@gmail.com
    */
    class Jet_Request
    {
        protected $_param = array(
                                'accept_language' => '',
                                'accept_endcoding' => array(),
                                'remote_address' => '',
                                'user_agent' => '',
                                
                                'host' => '', 
                                'request_uri' => '',
                                'request_method' => '',
                                'script_filename' => '',
                                'query_string' => '',
                                
                                'redirect_url' => '',
                                'redirect_query_string' => '',
                                'redirect_status' => '',
                                
                                'document_root' => '',
                                
                                'php_self' => '',
                                'path_info' => ''
        );
        protected $_rawParam = array(); // $_GET or URl parsed string
        
        private $_router = array(); // Router table
        
        private $_root;
        private $_requestUrl;
        private $_queryString;
        
        private $_enableSEO = false;
        
        public $controller,$action;
        
        protected static $_instance = null;
        
        public function construct()
        { /* unavailable "new" */}
        
        public static function getInstance(){
            if(null == $_handler){
                self::$_instance = new self();
            }
            
            return self::$_instance;
        }
              
        // get Controller,Action and parameters
        public function parseUrl($seoEnable = false){
            $language = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $this->_param['accept_language'] = $language[0];
            $this->_param['accept_endcoding'] = explode(',',$_SERVER['HTTP_ACCEPT_ENCODING']);
            $this->_param['remote_address'] = $_SERVER['REMOTE_ADDR'];
            $this->_param['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            
            $this->_param['host'] = $_SERVER['HTTP_HOST'];
            $this->_param['request_uri'] = $_SERVER['REQUEST_URI'];
            $this->_param['request_method'] = $_SERVER['REQUEST_METHOD'];
            $this->_param['request_time'] = $_SERVER['REQUEST_TIME'];
            $this->_param['script_filename'] = $_SERVER['ORIG_SCRIPT_FILENAME'];
            $this->_param['query_string'] = $_SERVER['QUERY_STRING'];
            
            $this->_param['redirect_url'] = $_SERVER['REDIRECT_URL'];
            $this->_param['redirect_query_string'] = $_SERVER['REDIRECT_QUERY_STRING'];
            $this->_param['redirect_status'] = $_SERVER['REDIRECT_STATUS'];
            
            $this->_param['document_root'] = $_SERVER['DOCUMENT_ROOT'];
            
            $this->_param['php_self'] = $_SERVER['PHP_SELF'];
            $this->_param['path_info'] = $_SERVER['ORIG_PATH_INFO'];
            
            $this->_root = dirname($this->_param['php_self']);
            
            $requestUrl = substr($this->_param['redirect_url'],
                                strlen($this->_root));
            $this->_requestUrl = trim($requestUrl,'/');
            
            $this->_queryString = $this->_param['query_string'];
            
            $this->_enableSEO = $seoEnable;

            // if seo friendly
            if($seoEnable) {
                // SEO Friendly Mode  
                if(false === ($newRouter = $this->isRouter())){
                    // Router Mode
                    $newRouter = $this->_requestUrl;
                }
                // normail SEO mode : /controller/action
                if(strlen(trim($newRouter)) > 0)
                    $arrRequest = explode('/',trim($newRouter));
                else
                    $arrRequest = '';
                if(is_array($arrRequest)) {
                    $this->controller = (isset($arrRequest[0]) && strlen($arrRequest[0])>0)?$arrRequest[0]:'index';
                    $this->action = (isset($arrRequest[1]) && strlen($arrRequest[1])>0)?$arrRequest[1]:'index';
                } else {
                    $this->controller = 'index';
                    $this->action =  'index';
                }
                
                if(is_array($arrRequest)) {
                    $rawParam = array_slice($arrRequest,2);
                    for($i=0,$n=count($rawParam);$i<$n;$i+=2) {
                        $this->_rawParam[$rawParam[$i]] = ($i+1<$n)?$rawParam[$i+1]:'';
                    }
                }

            } else {
                // normal mode * example: index.php?controller=user&action=view...
                $this->controller = isset($_GET['controller'])?$_GET['controller']:'index';
                $this->action = isset($_GET['action'])?$_GET['action']:'index';
                // retrieve all param into _rawparam array
                foreach($_GET as $key => $value){
                    if($key != 'controller' && $key != 'action') {
                        $this->_rawParam[$key] = $value;
                    }
                }
            }
        } 
        
        // Add Router
        public function addRouter($need,$replace){
            $this->_router[$need] = trim($replace,'/');
        }

        // Is Some Router case
        protected function isRouter(){
            $router = $this->_router;
            // regular expression sort by length,and updown read
            ksort($router);
            $router = array_reverse($router);
            foreach($router as $key => $item){
                if(preg_match($key,$this->_requestUrl)){
                    // fill new controller and action
                    $requestUrl = preg_replace($key,$item,$this->_requestUrl);
                    return $requestUrl;
                }
            }
            return false;
        }
        
        // getUrl
         public function getUrl($controller,$action,$param = null){
             // remove duplicate key
			 $tmpParam = array();   
             if(isset($param) && is_array($param)){
                foreach($param as $key => $value) {
                    if(!isset($tmpParam[$key]))
                        $tmpParam[$key] = $value;
                }
            }
            
            if($this->_enableSEO) {
                $href = array();
                foreach($tmpParam as $key => $value) {
                    $href[] = sprintf('%s/%s',$key,isset($value)?$value:'');
                }
                $href = join('/',$href);
                if(strlen(trim($href))>0)
                    $href = sprintf('%s/%s/%s/%s',$this->_root,$controller,$action,$href);
                else
                    $href = $this->_root;
                    
            }else {
                $href = array();
                foreach($tmpParam as $key => $value) {
                    $href[] = $key.'='.$value;
                }
                $href = join('&',$href);
                
                $href = sprintf('%s%s%s',
                                (isset($controller) && strtolower($controller)!= 'index')?'controller='.$controller:'',
                                (isset($action) && strtolower($action)!= 'index')?'&action='.$action:'',
                                strlen(trim($href))>0?('&'.$href):'');
                $href = ltrim($href,'&');
                if(strlen(trim($href)) >0)
                    $href = $this->_root.'?'.$href;
                else
                    $href= $this->_root;
            }
            return $href;
        }
        
        // if Post something
        public function isPost(){
            return strtolower($this->_param['request_method']) == 'post';
        }
        
        // get system variables
        public function getSystemVar($name){
            if(isset($name) && count(trim($name)) > 0 && isset($this->_param[$name]))
                return $this->_param[$name];
            else
                return false;
        }
        
        // get all param by $_GET / URL
        public function getAllParam(){
            if(count($this->_rawParam)>0)
                return $this->_rawParam;
            else
                return false;
        }
        
        // get one param value
        public function getParam($name){
            if(isset($this->_rawParam[$name]))
                return $this->_rawParam[$name];
            else
                return false;
        }
        
        
    }
?>
