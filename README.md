# jetframework

============

A light-weight PHP MVC framework, template based on smarty

## Class Jet_Front

### Variables

$response

$_action

$_controller

$_instance

### Methods

void addRouter( $source, $replace)

void allowRender( )

void dispatch( )

void enableSEO( [ $value = true])

static void getInstance( )

void getUrl( $controller, $action, [ $param = null])

void isEnabledSEO( )

void setNoRender( )

## Class Jet_Request

### Methods

static void getInstance( )

void addRouter( $need, $replace)

id getAllParam( )

void getParam( $name)

void getSystemVar( $name)

void getUrl( $controller, $action, [ $param = null])

void isPost( )

void isRouter( )

void parseUrl( [ $seoEnable = false])

## Class Jet_Response

### Variables 

$render

### Methods

static void getInstance( )

void assign( $name, $value)

void render( $filename)

void setDirectories( [ $templateDir = null], [ $compileDir = null], [ $configDir = null], [ $cacheDir = null])

void setTemplateExt( $fileExt)
