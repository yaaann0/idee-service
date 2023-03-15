$('form').on('submit', function(e) {
    const title = $('#news_title').val();
    const file = $('#news_newsFile')[0].files[0];

    if (title.length > 250) {
        $('#news_title')[0].setCustomValidity('250 caractère maximum')
        e.preventDefault();
    }

    const acceptedType =  [
        "application/pdf", 
        "application/x-pdf", 
        'application/msword', 
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
        'image/jpeg', 
        'image/png'
    ]

    if (!acceptedType.includes(file.type)) {
        $('#news_newsFile')[0].setCustomValidity('Format de fichier non valide')
        e.preventDefault();
    }

    if (file.size > 200000) {
        $('#news_newsFile')[0].setCustomValidity('Fichier trop volumineux')
        e.preventDefault();
    }
})

$('input').on('change', function(e) {
    e.currentTarget.setCustomValidity('');
})

$('#news_newsFile').on('change', function (e) {
    let filename = e.currentTarget.files[0].name;
    let nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = filename.substring(0, 20) + '...';
})

