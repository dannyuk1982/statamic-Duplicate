var addButtons = function($) {

  // label to use for the duplicate button - should be translatable really
  var label = 'Duplicate';

  // check we are on an entry index page...
  if( location.href.indexOf( 'collections/entries' ) !== -1 ) {

    //get the collection we are on
    var collection = location.href.split('/');
    collection.splice( 0, collection.length - 1);

    // add the buttons (this would be probably be better done via Vue?)
    $('ul.dropdown-menu').each( function() {

      // get the slug
      var slug = $(this).parents('td').siblings('.cell-slug').html().trim();
      
      // get the link to the controller
      var href = '/cp/addons/duplicateentry/' + collection + '/' + slug;

      // create an element to add
      var li = $('<li><a href="' + href + '">' + label + '</a></li>');

      // add to the DOM, after the 'edit' button, it could be after 'delete' if preferred, change eq(0) to eq(1)
      $(this).children('li').eq(0).after( li );

    });

  }
  
}

// call this a bit after the window has loaded (give some time for Vue to load) -
// this should be better than just guessing at 500ms and assuming Vue is done,
// NOTE: this is also untested with pagination
window.addEventListener("load", function(event) {

  setTimeout(function() { addButtons( jQuery ); }, 500 )

});