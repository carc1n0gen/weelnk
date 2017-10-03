<?php include '_top.php' ?>

<header>
    <h1><a>HUUUUURAY!</a></h1>
</header>

<div class="show">
    <p>Your new shortened link is <a id="short-link" href="<?= $link ?>"><?= $link ?></a></p>

    <p>If you want, you can go back and <a href="/">shorten another one</a></p>
</div>

<?php include '_bottom.php' ?>