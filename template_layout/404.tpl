<?
	http_response_code(404);
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>404 Error, the page you're looking for cannot be found</h1></title>
</head>
<body>

<style type="text/css">
body{
	margin: 0 auto;
	background: #f6f6f6;
}
#page404{
	background:white;
	padding:40px;
	margin: 0 auto;
	max-width: 1200px;
}
#page404 h1{
	font-size:20px;color:#EE2479;
}
#page404 ul{ padding-left:40px;}
#page404 ul,
#page404 ul li{
	list-style:disc;
}

#page404 a{
	color:gray;

}

</style>
<div id="page404" >
	<h1>404 Error, the page you're looking for cannot be found</h1>

    <p>It seems that the page you were trying to visit is no longer on my site. Please try to remain calm, these kinds of things happen all the time.</p>
	<br/>
	<br/>
	<br/>
	<ul>
		<li>Hit the “back” button on your browser.</li>
		<li>Head on over to the <a href="<?=WEB_META_BASE_LANG?>">home page</a>.</li>

	</ul>

	<p></p>

</div>

</body>
</html>
