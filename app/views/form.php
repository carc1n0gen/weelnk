
<!DOCTYPE html>
<html>
<head>

<title>weelnk</title>

<style>
* {
	box-sizing: border-box;
}

body {
	padding-top: 4em;
	color: #93A1A1;
	font-family: Arial,Helvetica,sans-serif;
	transition: background-color 500ms linear;
}

.dark {
	background-color: #002B36;
}

.light {
	background-color: #EEE8D6;
}

header {
	text-align: center;
	font-size: 200%;
}

h1 {
    margin: 0 0 1em;
}

a {
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
	color: #657B83;
	transition: background-color 500ms linear;
}

.dark .url {
	background-color: #002B36;
}

.light .url {
	background-color: #EEE8D6;
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
	padding: 4em;
	margin: 2em auto;
	max-width: 38em;
	transition: background-color 500ms linear;
}

.dark .container {
	background-color: #073642;
}

.light .container {
	background-color: #FDF6E4;
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

a {
	color: #32A198;
}

.theme-picker {
	position: fixed;
	bottom: 0;
	left: 0;
	font-size: 200%;
}

.theme-picker-light {
	color: #B4881D;
}

.theme-picker-dark {
	color: #6D73C2;
}

.theme-picker-light,
.theme-picker-dark {
	cursor: pointer;
}

footer {
	text-align: center;
}

@media screen and (max-width: 30em) {
	.light {
		background-color: #FDF6E4;
	}

	.dark {
		background-color: #073642;
	}

	.container {
		padding: .5em;
	}

	.url {
		width: 80%;
	}
	
	.shorten {
		width: 18%;
	}
}

/* fontello */

@font-face {
  font-family: 'fontello';
  src: url('../font/fontello.eot?61502274');
  src: url('../font/fontello.eot?61502274#iefix') format('embedded-opentype'),
       url('../font/fontello.woff2?61502274') format('woff2'),
       url('../font/fontello.woff?61502274') format('woff'),
       url('../font/fontello.ttf?61502274') format('truetype'),
       url('../font/fontello.svg?61502274#fontello') format('svg');
  font-weight: normal;
  font-style: normal;
}
/* Chrome hack: SVG is rendered more smooth in Windozze. 100% magic, uncomment if you need it. */
/* Note, that will break hinting! In other OS-es font will be not as sharp as it could be */
/*
@media screen and (-webkit-min-device-pixel-ratio:0) {
  @font-face {
    font-family: 'fontello';
    src: url('../font/fontello.svg?61502274#fontello') format('svg');
  }
}
*/
 
 [class^="icon-"]:before, [class*=" icon-"]:before {
  font-family: "fontello";
  font-style: normal;
  font-weight: normal;
  speak: none;
 
  display: inline-block;
  text-decoration: inherit;
  width: 1em;
  margin-right: .2em;
  text-align: center;
  /* opacity: .8; */
 
  /* For safety - reset parent styles, that can break glyph codes*/
  font-variant: normal;
  text-transform: none;
 
  /* fix buttons height, for twitter bootstrap */
  line-height: 1em;
 
  /* Animation center compensation - margins should be symmetric */
  /* remove if not needed */
  margin-left: .2em;
 
  /* you can be more comfortable with increased icons size */
  /* font-size: 120%; */
 
  /* Font smoothing. That was taken from TWBS */
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
 
  /* Uncomment for 3D effect */
  /* text-shadow: 1px 1px 1px rgba(127, 127, 127, 0.3); */
}
 
.icon-sun-inv:before { content: '\e800'; } /* '' */
.icon-moon-inv:before { content: '\e801'; } /* '' */

/* end fontello */

</style>

</head>

<body class="<?= $theme ?>">
<div class="container">

	<header>
		<h1><a href="/">weelnk</a></h1>
	</header>
	
		
	<div id="frm">
	
		<?php if (isset($msg)): ?>
			<div class="error"><?= $msg ?></div>
		<?php endif ?>
	
		<form class="form" action="/" method="post">
			<input id="url" class="url" name="url" type="text" autocomplete="off" placeholder="http://"
			<?php if (isset($url)): ?> value="<?=$url?>" <?php endif ?> />
			<input class="shorten" type="submit" value="Shorten" />
		</form>

		<?php if (isset($link)): ?>
			<div class="link">
				<a id="short-link" href="<?= $link ?>"><?= $link ?></a>
			</div>
		<?php endif ?>
	
	</div>
</div>

<footer>
    <p>&copy; <a href="https://carsonevans.ca">Carson Evans</a> <?= date('Y') ?></p>
    <p>Check out the code on <a href="https://github.com/carc1n0gen/weelnk">github</a></p>
</footer>

<div class="theme-picker">
	<span class="theme-picker-light icon-sun-inv" title="Light theme"></span>
	<span class="theme-picker-dark icon-moon-inv" title="Dark theme"></span>
</div>

<script>
	document.getElementById('url').focus();

	var lightThemeToggle = document.querySelector('.theme-picker-light');
	var darkThemeToggle = document.querySelector('.theme-picker-dark');

	lightThemeToggle.addEventListener('click', function () {
		document.body.classList.remove('dark');
		document.body.classList.add('light');
		document.cookie = 'theme=light';
	});

	darkThemeToggle.addEventListener('click', function () {
		document.body.classList.remove('light');
		document.body.classList.add('dark');
		document.cookie = 'theme=dark';
	});
</script>

</body>
</html>
