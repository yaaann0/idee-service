var $collectionHolder;

// setup an "add a mealgrant" link
var $addMealgrantButton = $('<button type="button" class="add_mealgrant_link btn btn-sm btn-secondary mt-3">Ajouter un repas</button>');
var $newLinkLi = $('<div></div>').append($addMealgrantButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of mealgrants
    $collectionHolder = $('div.mealgrants');

    // add the "add a mealgrant" anchor and li to the mealgrants ul
    $collectionHolder.append($newLinkLi);

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('div.item').each(function() {
        addMealFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addMealgrantButton.on('click', function(e) {
        // add a new mealgrant form (see next code block)
        addMealgrantForm($collectionHolder, $newLinkLi);
    });

    addMealgrantForm($collectionHolder, $newLinkLi);

    const today = new Date();
    $('#meal_sheet_month')[0].selectedIndex = today.getMonth();
    $('#meal_sheet_year').val(today.getFullYear())
});

function addMealgrantForm($collectionHolder, $newLinkLi) {
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
    newForm = newForm.replace(/__meal_prot__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<div></div>').append(newForm);
    $newLinkLi.before($newFormLi);
    addMealFormDeleteLink($newFormLi);
    $('.client_name').on('input', autoComplete);
}

function addMealFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-sm btn-outline-danger">Supprimer cette ligne</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}