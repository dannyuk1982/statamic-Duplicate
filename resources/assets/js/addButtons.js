// Injects a 'Duplicate' button to the DOM
var addButtons = function( delay ) {
  ( function( $ ) {

    // label to use for the duplicate button - should be translatable really
    var label = 'Duplicate';
   
    // only continue if we are on an entries index page
    if( location.href.indexOf( 'collections/entries' ) !== -1 ) {

      // get the collection
      var collection = location.pathname.split('/');
      collection.splice( 0, collection.length - 1 );
      collection = collection[0].toString();
    
      // We need to run this code after everything else has finished executing, 
      // so call via an anonymous function, with setTimeout
      window.setTimeout( function() {

        $('ul.dropdown-menu').each( function() {

          // get the slug for this entry from the DOM
          var slug = $(this)
            .parents('td.column-actions')
            .siblings('td.cell-slug')
            .html()
            .trim();

          // get the link to the DuplicateEntry controller function
          var href = '/cp/addons/duplicateentry/' + collection + '/' + slug;

          // create an element to add
          var li = $('<li><a href="' + href + '">' + label + '</a></li>');

          // add to the DOM
          $(this).children('li').eq(0).after( li );

        });

      // delay will be 0 if the browser supports XMLHttpRequest, otherwise an arbitrary period to wait
      }, delay ); 

    }

  })( jQuery );
};

// Run the function, based on whether the browser supports XMLHttpRequest.prototype.open or not
if( typeof XMLHttpRequest.prototype.open ===  'function' ) {

  // Hijack the XMLHttpRequest.prototype.open function, and listen for any XHR `load` events
  // Listen for GET requests to `/get` (which loads entries) and then call `addButtons`
  ( function( open ) {

      XMLHttpRequest.prototype.open = function( method, url, async, user, pass ) {

          this.addEventListener( 'load', function() {

              // if we've just loaded some entries, then inject the `duplicate` buttons
              if( method.toUpperCase() === 'GET' && url.indexOf( '/get?' ) !== -1 ) {

                // Pass `jQuery` to the function, to be used as `$`. The second parameter,
                // `delay`, will be 0 – as this will only be ran after the entries have loaded
                addButtons( 0 );
              }

          }, false );

          // continue with the request
          open.call( this, method, url, async, user, pass );
      };

  })( XMLHttpRequest.prototype.open );

// older browser, so just call the function after the window has loaded, and allow a delay for
// the entries to load – guess 1 second. This can be made higher if the entries are not loading in time
} else {

  window.addEventListener( 'load', function() {

    addButtons( 1000 );

  });

}