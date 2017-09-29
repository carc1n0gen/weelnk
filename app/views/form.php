<?php include '_top.php' ?>

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
	
	<?php endif ?>

</div>

<?php include '_bottom.php' ?>
