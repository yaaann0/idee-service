var $collectionHolder;

// setup an "add a vacation" link
var $addMealgrantButton = $('<button type="button" class="add_vacation_link btn btn-sm btn-secondary mt-3">Ajouter une ligne</button>');
var $newLinkLi = $('<div></div>').append($addMealgrantButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of vacations
    $collectionHolder = $('div.vacations');

    // add the "add a vacation" anchor and li to the vacations ul
    $collectionHolder.append($newLinkLi);

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('div.item').each(function() {
        addMealFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addMealgrantButton.on('click', function(e) {
        // add a new vacation form (see next code block)
        addMealgrantForm($collectionHolder, $newLinkLi);
    });

    addMealgrantForm($collectionHolder, $newLinkLi);
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
    newForm = newForm.replace(/__vac_prot__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<div></div>').append(newForm);
    $newLinkLi.before($newFormLi);
    addMealFormDeleteLink($newFormLi);

    $("select[id$='_hour'").each(function(i,e){
        $e = $(e)
        if (!$e.val()) {
            $e.val(8)
        }
    })
    $("select[id$='_minute'").each(function(i,e){
        $e = $(e)
        if (!$e.val()) {
            $e.val(0)
        }
    })
}

function addMealFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-sm btn-outline-danger">Supprimer cette ligne</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}