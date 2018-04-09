function _isJson(item)
{
    item = typeof item !== "string"
        ? JSON.stringify(item)
        : item;

    try {
        item = JSON.parse(item);
    } catch (e) {
        return false;
    }

    if (typeof item === "object" && item !== null) {
        return true;
    }

    return false;
}

function uniqid() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < 23; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    var d = new Date();
    text += ('0' + d.getHours()).slice(-2) + ('0' + d.getMinutes()).slice(-2) + ('0' + d.getSeconds()).slice(-2) + ('00' + d.getMilliseconds()).slice(-3);
    return text;
}

let bulle = new Xbulle();
