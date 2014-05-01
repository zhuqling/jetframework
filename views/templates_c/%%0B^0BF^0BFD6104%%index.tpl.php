<?php /* Smarty version 2.6.19, created on 2010-05-18 22:43:00
         compiled from Index/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Index/index.tpl', 13, false),)), $this); ?>
<html>
<head>
<link href="/jet_framework/css/main.css" rel="stylesheet" type="text/css">
<title>Jet Framework,A lightweight php framework.</title>
</head>
<body>
<div id="main">
<h1>Jet Framework Demo</h1>
<div id="nav"><a href="/collection/Jet_Framework/API_Docs/">API Documents</a> <a href="/collection/Jet_Framework/Jet_Framework.rar">Download Jet Framework</a></div>
<div id="demo_nav">
<a href="<?php echo $this->_tpl_vars['hrefIndex']; ?>
">Test Index/index</a> <a href="<?php echo $this->_tpl_vars['hrefIndexAbcAction']; ?>
">Test Index/abc</a> <a href="<?php echo $this->_tpl_vars['hrefRouter']; ?>
">Test Rewrite Router</a>(only used when SEO enabled)</div>
<div>
  <h2>Welcome <?php echo ((is_array($_tmp=@$this->_tpl_vars['yourname'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Guest') : smarty_modifier_default($_tmp, 'Guest')); ?>
</h2>
  <ul>
<?php $_from = $this->_tpl_vars['param']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<li><?php echo $this->_tpl_vars['key']; ?>
:<?php echo $this->_tpl_vars['item']; ?>
</li>
<?php endforeach; else: ?>
<li>No Param set.</li>
<?php endif; unset($_from); ?>
</ul>
</div>
</div>
</body>
</html>