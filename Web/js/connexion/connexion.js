$(document).ready(function () {
    $('#valider').on('click', function (e) {
        e.preventDefault();
        auth();
    });
});

function auth() {
    let pseudo = $('#pseudo').val();
    let password = $('#password').val();
    $.post('./ajax/connexion', {'pseudo': pseudo, 'password': password}, function(data) {
        if (data.etat === 'conf') {

        } else {

        }
    }, 'json');
}