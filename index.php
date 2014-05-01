<?php
    require_once 'include/Jet/Jet_Front.php';
    
    $front = Jet_Front::getInstance();
    // $front->enableSEO(false);
	$front->enableSEO(true);

    $front->addRouter('/user\/([0-9]+)\/edit/','index/edit/id/$1');

    $front->dispatch();
    $front->dispatch();

?>
