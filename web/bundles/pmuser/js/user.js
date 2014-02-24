/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){

// Récupère le div qui contient la collection de tags
var collectionHolder = $('div.rolesworkspaces');


    
// ajoute un lien « add a tag »
var $addRWLink = $('<p class="text-right"><a href="#" class="add_rw_link">Ajouter un workspace</a></p>');
var $newLinkLi = $('<div class="add_link"></div>').append($addRWLink);

jQuery(document).ready(function() {
    
    // ajoute un lien de suppression à tous les éléments li de
    // formulaires de tag existants
    collectionHolder.find('div.rwform').each(function() {
        addRWFormDeleteLink($(this));
        console.log('test');
    });

    // ajoute l'ancre « ajouter un tag » et li à la balise ul
    collectionHolder.append($newLinkLi);

    $addRWLink.on('click', function(e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();

        // ajoute un nouveau formulaire tag (voir le prochain bloc de code)
        addRWForm(collectionHolder, $newLinkLi);
    });
});

function addRWForm(collectionHolder, $newLinkLi) {
    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');

    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un tag"
    var $newFormLi = $('<div class="row rwform" style="margin-bottom:5px;"></div>').append(newForm);
    $newLinkLi.before($newFormLi);
    
    // ajoute un lien de suppression au nouveau formulaire
    addRWFormDeleteLink($newFormLi);
}

function addRWFormDeleteLink($rwFormLi) {
    var $removeFormA = $('<a href="#" class="btn col-md-2 col-xs-2 btn-danger">Supprimer</a>');
    $rwFormLi.find('div.form-inline').append($removeFormA);

    $removeFormA.on('click', function(e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();

        // supprime l'élément li pour le formulaire de tag
        $rwFormLi.remove();
    });
}

});