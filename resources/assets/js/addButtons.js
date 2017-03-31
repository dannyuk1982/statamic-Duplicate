var addButtons = function($) {

  //lavel to use for the duplicate button - should be translatable really
  var label = 'Duplicate';

 	if( location.href.indexOf( 'collections/entries' ) !== -1 ) {

    //get the collection
    var collection = location.href.split('/');
    collection.splice( 0, collection.length - 1);

   	$('ul.dropdown-menu').each( function() {

      //get the slug and link
      var slug = $(this).parents('td').siblings('.cell-slug').html().trim();
      var href = '/cp/addons/duplicateentry/' + collection + '/' + slug;

      //create an element to add
     	var li = $('<li><a href="' + href + '">' + label + '</a></li>');

      //add to the DOM
     	$(this).children('li').eq(0).after( li );

   	});

 	}

}

//call this a bit after the window has loaded (give some time for vue to load) -
//really this should be called after vue has done it's thing
window.addEventListener("load", function(event) {

  setTimeout(function() { addButtons( jQuery ); }, 500 )

});
