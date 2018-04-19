<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'Mon super site' ?>
    </title>
    <meta charset="utf-8" />
    <?= $css ?>
</head>

<body>
<div id="wrap">
    <header>
        <h1><a href="<?= $project_url ?>">Mon site</a></h1>
    </header>
    <?=
    $menu
    ?>
    <div id="content-wrap">
        <section id="main">
            <div class="container">
                <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>

                <?= $content ?>
            </div>
        </section>
    </div>

    <footer></footer>
    <?= $js ?>
</div>
</body>
</html>