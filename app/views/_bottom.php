</div>

<footer>
    <p>&copy; <a href="https://carsonevans.ca">Carson Evans</a> <?= date('Y') ?></p>
	<?php if (getenv('SHOW_DO_PLUG') == 'true'): ?>
	<p>Proudly hosted on <a href="https://m.do.co/c/1c194cde1063">DigitalOcean</a></p>
	<?php endif ?>
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

<?php if (getenv('SHOW_GITHUB_LINK') === 'true'): ?>
<a href="https://github.com/carc1n0gen/weelnk"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png"></a>
<?php endif ?>
</body>
</html>