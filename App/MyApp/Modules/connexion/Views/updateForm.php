<div class="row">
    <div class="col">
        <h2>Modification de vos informations</h2>
    </div>
</div>
<div class="row">
    <div class="col">
        <form action="<?= $project_url ?>/user/modification/valide" method="post">
            <div class="form-group">
                <label for="pseudo" class="label">Pseudo</label>
                <input type="text" class="form-control" id="name" placeholder="Votre nom d'utilisateur"
                       value="<?= $myUser->name() ?>">
            </div>
            <div class="form-group">
                <label for="password" class="label">Password</label>
                <input type="password" class="form-control" name="password" id="password"
                       placeholder="Votre mot de passe" value="">
            </div>
            <div class="form-group">
                <label for="password_confirm" class="label">Password</label>
                <input type="password" class="form-control" name="password_confirm" id="password_confirm"
                       placeholder="Confirmer le mot de passe">
            </div>
            <input type="submit" class="btn btn-primary" id="valider">
        </form>
    </div>
</div>