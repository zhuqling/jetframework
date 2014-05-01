<?php
	/**
    * Class Jet_Response
    * category:Jet Framework
    * singleton class
    * 
    * @copyright 2008-12-6 Jason Chuh zhuqling@gmail.com
    */
    class Jet_Response
    {
        public  $render; // smarty
        private $_templateExt = 'tpl'; // template file extension
        
        private $_assign = array(); // values assign to template
        
        protected static $_instance = null;
        
        public function construct()
        { /* unavailable "new" */}
        
        public static function getInstance(){
            if(null == $_handler){
                self::$_instance = new self();
                
                $path = rtrim(dirname(__FILE__),'/');
                // initialize smarty class
                require_once $path.'/../smarty/Smarty.class.php';
                self::$_instance->render = new Smarty();
                
                self::$_instance->render->template_dir = $path .'/../../views/templates';
                self::$_instance->render->compile_dir = $path .'/../../views/templates_c';
            }
            
            return self::$_instance;
        }
        
        // render file
        public function render($filename)
        {
            foreach ($this->_assign as $key => $value) {
                $this->render->assign($key,$value); 
            }
            
            if(false === strpos($filename,'.'))
                $filename = sprintf('%s.%s',$filename,$this->_templateExt);
                
            $this->render->display($filename);
        } 
        
        // set template filename extension
        public function setTemplateExt($fileExt)
        {
            if(isset($fileExt) && strlen(trim($fileExt))>0)
                $this->_templateExt = $fileExt;
        }
        
        // change view directory   
        public function setDirectories($templateDir=null,$compileDir=null,$configDir = null,$cacheDir = null)
        {
            if(isset($templateDir))
                $this->render->template_dir = $templateDir;
                
            if(isset($compileDir))
                $this->render->compile_dir = $compileDir;
            
            if(isset($configDir))
                $this->render->config_dir = $configsDir;
                
            if(isset($cacheDir))
                $this->render->cache_dir = $cacheDir;
        }
        
        // assign method
        public function assign($name,$value)
        {
            $this->_assign[$name] = $value;
        }
    }
?>
