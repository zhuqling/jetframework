<?php
  /**
  * @desc Index Controller
  * class name : Uppercase the first character of each word in a string 
  */
  class IndexController 
  {
    // function name : lowercase all words
    public function indexAction($request,$response,$front){
        $hrefIndex = $front->getUrl('index','index');
        $hrefIndexAbcAction = $front->getUrl('index','abc',array('edit'=>true,'id'=>'888'));
        $hrefRouter = $front->getUrl('user','888',array('edit'=>null));
        $response->assign('hrefIndex',$hrefIndex);
        $response->assign('hrefIndexAbcAction',$hrefIndexAbcAction);
        $response->assign('hrefRouter',$hrefRouter);
        // test auto render
    }
    function abcAction($request,$response,$front)
    {
        $hrefIndex = $front->getUrl('index','index');
        $hrefIndexAbcAction = $front->getUrl('index','abc',array('edit'=>true,'id'=>'888'));
        $hrefRouter = $front->getUrl('user','888',array('edit'=>null)); 
        $response->assign('hrefIndex',$hrefIndex);
        $response->assign('hrefIndexAbcAction',$hrefIndexAbcAction);
        $response->assign('hrefRouter',$hrefRouter);
        // test template assign value
        $response->assign('yourname','Jason');
        $response->assign('param',$request->getAllparam());
        // test customer render
        $front->setNoRender();
        $response->render('Index/index');
    }
	function editAction($request,$response,$front)
	{
        $hrefIndex = $front->getUrl('index','index');
        $hrefIndexAbcAction = $front->getUrl('index','abc',array('edit'=>true,'id'=>'888'));
        $hrefRouter = $front->getUrl('user','888',array('edit'=>null)); 
        $response->assign('hrefIndex',$hrefIndex);
        $response->assign('hrefIndexAbcAction',$hrefIndexAbcAction);
        $response->assign('hrefRouter',$hrefRouter);
        // test url router
		$userId = $request->getParam('id');
        $front->setNoRender();
        $response->assign('yourname','Your ID:'.$userId);
        $response->assign('param',$request->getAllparam());
        $response->render('Index/index');
	}
  }
?>
