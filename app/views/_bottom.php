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