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
<a href="{$hrefIndex}">Test Index/index</a> <a href="{$hrefIndexAbcAction}">Test Index/abc</a> <a href="{$hrefRouter}">Test Rewrite Router</a>(only used when SEO enabled)</div>
<div>
  <h2>Welcome {$yourname|default:Guest}</h2>
  <ul>
{foreach from=$param key=key item=item}
<li>{$key}:{$item}</li>
{foreachelse}
<li>No Param set.</li>
{/foreach}
</ul>
</div>
</div>
</body>
</html>