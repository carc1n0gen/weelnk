
<!DOCTYPE html>
<html>
<head>

<title>weelnk</title>

<style type="text/css">
* {
	box-sizing: border-box;
}

body {
	padding-top: 6em;
	background-color: #002B36;
	color: #93A1A1;
	font-family: Arial,Helvetica,sans-serif;
}

header {
    text-align: center;
}

h1 {
    margin: 0 0 1em;
}

a {
	color: #93A1A1;
	text-decoration: none;
}

.url:focus,
.url:active,
.shorten:focus,
.shorten:active {
	outline: none;
}

.url {
	width: 84%;
	margin-right: 2%;
	background-color: #FDF6E3;
	color: #657B83;
}

.shorten {
	width: 14%;
	background-color: #2AA198;
	color: #FDF6E3;
	cursor: pointer;
}

.url,
.shorten {
	display: block;
	padding: .5em;
	float: left;
	border: none;
	font-size: 14px;
}

.container {
	padding: 2em;
	margin: 2em auto;
	width: 36em;
	background-color: #073642;
}

.form:after {
	display: table;
	content: '';
	clear: both;
}

.error {
	margin: 1em 0;
	color: #DC322F;
}

.link {
	margin: 2em 0 0;
}

.link a {
	color: #268BD2;
}

footer {
	text-align: center;
}

</style>

</head>

<body>
<div class="container">

	<header>
		<a href="/"><h1>weelnk</h1></a>
	</header>
	
		
	<div id="frm">
	
		<?php if (isset($msg)): ?>
		<div class="error">
			<?= $msg ?>
		</div>
		<?php endif ?>
	
		<form class="form" action="/" method="post">
			<input id="url" class="url" name="url" type="text" autocomplete="off" placeholder="http://"
			<?php if (isset($url)): ?> value="<?=$url?>" <?php endif ?> />
			<input class="shorten" type="submit" value="Shorten" />
		</form>

		<?php if (isset($link)): ?>
		<div class="link">
			<a href="<?= $link ?>"><?= $link ?></a>
		</div>
		<?php endif ?>
	
	</div>
</div>

<footer>
    <p>&copy; Carson Evans <?= date('Y') ?></p>
</footer>

<script>
	document.getElementById('url').focus();
</script>

</body>
</html>
