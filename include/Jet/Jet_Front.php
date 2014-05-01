<?php
    /**
    * Class Jet_Front
    * category:Jet Framework
    * singleton class
    * 
    * $jet = Jet_Front->getInstance();
    * $jet->enableSEO();
    * $jet->dispatch();
    * 
    * @copyright 2008-12-6 Jason Chuh zhuqling@gmail.com
    */
    require_once 'Jet_Request.php';
    require_once 'Jet_Response.php';  
    
    class Jet_Front
    {
        protected $_controller;
        protected $_action;
        
        public $response;
        private $request;
        
        private $_allowRender = true;
        
        private $_controllerDir;
        
        private $_SEO_Friendly = true;
        
        // Singleton Pattern Implementation
        protected static $_instance = null;
        
        public function construct()
        { /* unavailable "new" */}
        
        public static function getInstance(){
            if(null == $_handler){
                self::$_instance = new self();
                
                // initilize request and response
                self::$_instance->request = Jet_Request::getInstance();
                self::$_instance->response = Jet_Response::getInstance();
                
                $path = rtrim(dirname(__FILE__),'/'); 
                self::$_instance->_controllerDir = $path .'/../../controllers';
            }
            
            return self::$_instance;
        } 
    
        // if URL SEO Friendly 
        public function enableSEO($value = true){
            $this->_SEO_Friendly = $value;
        }
		public function isEnabledSEO()
        {
            return $this->_SEO_Friendly;
        }
        // message dispatch
        public function dispatch(){
            $this->request->parseUrl($this->_SEO_Friendly);
            
            // Controller and file use Camel style
            // action use lowercase
            $this->_controller = ucwords($this->request->controller);
            $this->_action = strtolower($this->request->action);
            $classFile = $this->_controllerDir . '/' . $this->_controller .'Controller.php';
            
            try {
                if(!file_exists($classFile)) {
                    $errorFile = $this->_controllerDir . '/error404.php';
                    if(file_exists($errorFile)) {
                        require_once $errorFile;
                        $class = new error404($this->request,$this->response,$this);
                        $class->error404();
                    } else {
                        throw new Exception(sprintf('<h1>Jet_Framework</h1><br/>File does not exists.(%s)',$classFile));  
                    }
                    return false;
                }
            
                require_once $classFile;
                $className = $this->_controller .'Controller';
                $functionName = $this->_action .'Action';
                
                $class = new $className();
                // preDispatch
                if(method_exists($class,'preDispatch'))
                    $class->preDispatch($this->request,$this->response,$this);
                    
                // call function
                if(method_exists($class,$functionName))
                    $class->$functionName($this->request,$this->response,$this);
                else {
                    $errorFile = $this->_controllerDir . '/error404.php';
                    if(file_exists($errorFile)) {
                        require_once $errorFile;
                        $class = new error405($this->request,$this->response,$this);
                        $class->error404();
                    } else {
                        throw new Exception(sprintf('<h1>Jet_Framework</h1><br/>action Error.(%s->%s)',$this->_controller,$this->_action));
                    }
                    
                }
                
                // postDispath
                if(method_exists($class,'postDispatch'))
                    $class->postDispatch($this->request,$this->response,$this);
                    
                // render    
                if($this->allowRender()){
                    $this->response->render(sprintf('%s/%s',$this->_controller , $this->_action));  
                }
            } catch(Exception $e) {
                echo $e->getMessage();
            }
            
        }
        
        // forbidden Auto Render
        public function setNoRender()
        {
            $this->_allowRender = false;
        }
        // is allowed Render?
        public function allowRender()
        {
            return $this->_allowRender;
        } 
        
        // getUrl by URL SEO mode or normal mode
        public function getUrl($controller,$action,$param = null){
            return $this->request->getUrl($controller,$action,$param);
        } 
        
        
        /** Add Router , use Perl compatiable regular expression
        * example
        * addRouter(''/user\/([0-9])+\/edit\//','/article/edit/id/{1}/')
        */
        public function addRouter($source,$replace){
            $this->request->addRouter($source,$replace);
        }
         
        // jump
        public function _director($controller = null,$action = null){
            if(!isset($controller))
                $controller = $this->_controller;
            if(!isset($action))
                $action = $this->_action;
                
            $url = $this->getUrl($controller,$action);
            Header('Location:' . $url);
        }
        // inner jump
        public function _forward($action)
        {
            $this->_director(null,$action);
        }

    }
?>
