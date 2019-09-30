// ---------------------------------------------------//

// Exécute un appel AJAX POST
// Prend en paramètres l'URL cible, la donnée à envoyer et la fonction callback appelée en cas de succès
// Le paramètre isJson permet d'indiquer si l'envoi concerne des données JSON
function ajaxPost(url, data, isJson, f) {
    var req = new XMLHttpRequest()
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            //f(req)
        }
    }
    req.open('POST', url)
    req.addEventListener('error', function () {
        console.error("Erreur réseau avec l'URL " + url)
    })
    if (isJson) {
        // Définit le contenu de la requête comme étant du JSON
        req.setRequestHeader('Content-Type', 'application/json')
        // Transforme la donnée du format JSON vers le format texte avant l'envoi
        data = JSON.stringify(data)
    } else {
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    }
    req.send(data)
    console.log("ajax");
}

function sleep(milliseconds) {
    var start = new Date().getTime()
    for (var i = 0; i < 1e7; i++) {
        if (new Date().getTime() - start > milliseconds) {
            break
        }
    }
}