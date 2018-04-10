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
            bulle.redirectAndAddBulle({redirect: './', type: data.etat, message: data.message});
        } else {
            bulle.addBulle({type: data.etat, message: data.message});
        }
    }, 'json');
}