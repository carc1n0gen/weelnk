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
	cursor: pointer;
	transition: color 500ms linear;
}

.light .shorten {
	color: #FDF6E3;
}

.dark .shorten {
	color: #022B35;
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

.show {
	text-align: center;
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
