function ajaxGet(url, inputElmt, callback) {

    url = '/index.php' + url;

    const myInit = {
        headers: {
            'Content-Type': 'application/json',
        }
    }

    fetch(url, myInit)
        .then(function (reponse) {
            return reponse.json()
        })
        .then(function (data) {
            callback(inputElmt, data);
        })
        .catch(function(error) {
            console.error('Erreur avec l\'opération fetch : ' + error.message);
        });
}