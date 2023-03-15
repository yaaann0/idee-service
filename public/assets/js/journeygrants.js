var $collectionHolder;

// setup an "add a journeygrant" link
var $addJourneyButton = $('<button type="button" class="add_journeygrant_link btn btn-sm btn-secondary mt-3">Ajouter un trajet</button>');
var $newLinkLi = $('<div></div>').append($addJourneyButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of journeygrants
    $collectionHolder = $('div.journeygrants');

    // add the "add a journeygrant" anchor and li to the journeygrants ul
    $collectionHolder.append($newLinkLi);

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('div.item').each(function() {
        addJourneyFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addJourneyButton.on('click', function(e) {
        // add a new journeygrant form (see next code block)
        addJourneyForm($collectionHolder, $newLinkLi);
    });

    addJourneyForm($collectionHolder, $newLinkLi);

    const today = new Date();
    $('#journey_sheet_month')[0].selectedIndex = today.getMonth();
    $('#journey_sheet_year').val(today.getFullYear())
});

function addJourneyForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__journey_prot__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<div></div>').append(newForm);
    $newLinkLi.before($newFormLi);
    addJourneyFormDeleteLink($newFormLi);
    $('.client_name').on('input', autoComplete);
}

function addJourneyFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-sm btn-outline-danger">Supprimer cette ligne</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}