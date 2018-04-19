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