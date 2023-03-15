$('.client_name').on('input', autoComplete);

$('body').on('click', function () {
    $('.client_suggests').children('li').remove(); 
});

function autoComplete (e) {
    const input = e.currentTarget;
    input.parentNode.lastElementChild.innerHTML = '';
    if (input.value.length >= 3) {
        ajaxGet('/app/clients?query=' + input.value, input, suggest);
    }
}

function suggest(elmt, client) {
    elmt.parentNode.lastElementChild.innerHTML = '';

    if (!client.name) {
        return;
    }

    let listItem = document.createElement('li');
    listItem.classList.add('list-group-item');
    listItem.classList.add('list-group-item-action');
    listItem.innerHTML = client.name + ' - ' + client.adress + ' ' + client.city;

    listItem.addEventListener('click', function () {
        elmt.value = listItem.innerHTML;
        elmt.parentNode.lastElementChild.innerHTML = '';
    })

    elmt.parentNode.lastElementChild.appendChild(listItem);
}