<div class="row" id="inscription">
    <div class="col">
        <form action="<?= $project_url ?>/inscription/validation" method="post">
            <div class="form-group">
                <label for="pseudo" class="label">Pseudo</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Votre nom d'utilisateur">
            </div>
            <div class="form-group">
                <label for="password" class="label">Password</label>
                <input type="password" class="form-control" name="password" id="password"
                       placeholder="Votre mot de passe">
            </div>
            <div class="form-group">
                <label for="password_confirm" class="label">Password</label>
                <input type="password" class="form-control" name="password_confirm" id="password_confirm"
                       placeholder="Confirmer le mot de passe">
            </div>
            <button type="submit" class="btn btn-primary">Valider</button>
        </form>
    </div>
</div>