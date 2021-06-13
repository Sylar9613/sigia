/**
 * Created by Arian-PC on 22/03/2020.
 */
var $collectionHolder;
var $collectionHolderResp;

// setup an "add a tag" link
var $addTagButton = $('<button type="button" class="add_tag_link btn bg-primary-angular"><i class="fa fa-fw fa-file-o"></i>&nbsp;Agregar un implicado</button>');
var $newLinkLi = $('<li style="margin-left: -40px; list-style-type:none;"></li>').append($addTagButton);

var $addRespButton = $('<button type="button" class="add_tag_link btn bg-primary-angular"><i class="fa fa-fw fa-file-o"></i>&nbsp;Agregar una responsabilidad</button>');
var $newLinkRespLi = $('<li style="margin-left: -40px; list-style-type:none;"></li>').append($addRespButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.implicados');
    $collectionHolderResp = $('ul.responsabilidades');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    $collectionHolderResp.find('li').each(function() {
        addRespFormDeleteLink($(this));
    });

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);
    $collectionHolderResp.append($newLinkRespLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $collectionHolderResp.data('index', $collectionHolderResp.find(':input').length);

    $addTagButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });
    $addRespButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addRespForm($collectionHolderResp, $newLinkRespLi);
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
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
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li style="border-top: 1px dashed #dc3545; padding-top: 20px; margin-left:-40px; list-style-type:none;"></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addTagFormDeleteLink($newFormLi);
}

function addRespForm($collectionHolder, $newLinkLi) {
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
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li style="border-top: 1px dashed #dc3545; padding-top: 20px; margin-left:-40px; list-style-type:none;"></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addRespFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button class="btn btn-danger w3-margin-bottom w3-margin-top" type="button"><i class="fa fa-fw fa-trash-o"></i>&nbsp;Eliminar este implicado</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}

function addRespFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button class="btn btn-danger w3-margin-bottom w3-margin-top" type="button"><i class="fa fa-fw fa-trash-o"></i>&nbsp;Eliminar esta responsabilidad</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}