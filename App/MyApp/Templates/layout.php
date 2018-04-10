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

    <nav>
        <ul>
            <li><a href="<?= $project_url ?>">Accueil</a></li>
            <?php if ($user->isAuthenticated()) { ?>
                <li><a href="<?= $project_url ?>/deconnexion">Déconnexion</a></li>
                <li><a href="<?= $project_url ?>/admin/">Admin</a></li>
                <li><a href="<?= $project_url ?>/admin/news-insert.html">Ajouter une news</a></li>
            <?php } ?>
        </ul>
    </nav>

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