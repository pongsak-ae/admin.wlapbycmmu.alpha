<?
	http_response_code(403);
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>403 Forbidden, You don't have permission to access this page</h1></title>
</head>
<body>

<style type="text/css">
body{
	margin: 0 auto;
	background: #f6f6f6;
}

#page403{
	background:white;
	padding:40px;
	margin: 0 auto;
	max-width: 1200px;
}
#page403 h1{
	font-size:20px;color:#EE2479;
}
#page403 ul{ padding-left:40px;}
#page403 ul,
#page403 ul li{
	list-style:disc;
}

#page403 a{
	color:gray;

}

</style>
<div id="page403" >
	<h1>403 Forbidden, You don't have permission to access this page.</h1>

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
