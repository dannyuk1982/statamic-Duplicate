<?php

namespace Statamic\Addons\Duplicate;

use Statamic\Extend\Controller;
use Statamic\API\Entry;
use Statamic\API\Page;
use Statamic\API\Helper;
use Statamic\API\Stache;

class DuplicateController extends Controller
{
    /**
     * Maps to your route definition in routes.yaml
     *
     * @return Illuminate\Http\Response
     */

    public function duplicateEntry( $collection, $slug )
    {

      // stop the haxx0rz
      $this->authorize('cp:access');
      $this->authorize('collections:{$collection}:create');

      //get the entry to duplicate
      $entry = Entry::whereSlug( $slug, $collection );

      //work out the slug, if the current slug doesn't already end with a timestamp, add one, if it does, update it
      $slug = $entry->slug();
      if( ! preg_match( '~[0-9]{10}$~', $slug ) ) {
        $slug.= '-'.time();
      } else {
        $slug = preg_replace( '~[0-9]{10}$~', time(), $slug );
      }

      // Create duplicate entry - modify the slug with the current timestamp to ensure it is unique
      $duplicate_entry = Entry::create( $slug )
          ->collection( $collection )
          ->with( $entry->data() )
          ->get();

      // set the order
      $duplicate_entry->order( $entry->order() );

      // unpublish as default
      $duplicate_entry->published( false );

      // add `(copy)` after the title
      $duplicate_entry->set( 'title', $this->getTitle( $entry->get('title') ) );

      // make a new ID (clear the old one first)
      $duplicate_entry->set( 'id', '' );
      $duplicate_entry->id( Helper::makeUuid() );

      // and finally... save it
      $duplicate_entry->save();

      // update the stache
      Stache::update();

      // go back to the collection index, with a sucess message
      return back()->with('success', trans('addons.Duplicate::settings.successEntry', ['title' => $entry->get('title')]));

    }

    public function duplicatePage( $id )
    {

      // stop the haxx0rz
      $this->authorize('cp:access');
      $this->authorize('pages:create');

      // get the page to duplicate
      $page = Page::find( $id );

      // work out the uri
      $uri = $page->uri();

      // if the current uri doesn't already end with a timestamp, add one, if it does, update it
      if( ! preg_match( '~[0-9]{10}$~', $uri ) ) {
        $uri.= '-'.time();
      } else {
        $uri = preg_replace( '~[0-9]{10}$~', time(), $uri );
      }

      // Create duplicate page - modify the slug with the current timestamp to ensure it is unique
      $duplicate_page = Page::create( $uri )
          ->with( $page->data() )
          ->get();

      // set the order (this will be the same as the orginal page)
      $duplicate_page->order( $page->order() );

      // unpublish as default
      $duplicate_page->published( false );

      // add `(copy)` after the title
      $duplicate_page->set( 'title', $this->getTitle( $page->get('title') ) );

      // make a new ID (clear the old one first)
      $duplicate_page->set( 'id', '' );
      $duplicate_page->id( Helper::makeUuid() );

      // and finally... save it
      $duplicate_page->save();

      // update the stache
      Stache::update();

      // go back to the pages index, with a sucess message
      return back()->with('success', trans('addons.Duplicate::settings.successPage', ['title' => $page->get('title')]));

    }
    // This should be smarter and consider the names of other entries, and not just the one being
    // duplicated, i.e. if the entry is called `My Entry (copy)`, it should check to see if there is
    // already an entry called `My Entry (copy 2)` before blindy calling the duplicated entry this.
    private function getTitle( $title )
    {
      $suffix = $this->trans('settings.copy');

      // does the title already contain the suffix? if so then increment it to 2
      if( preg_match( "~ \({$suffix}\)$~", $title ) ) {

        $just_title = preg_replace( "~^(.+) \({$suffix}\)$~", "$1", $title );

        return "{$just_title} ({$suffix} 2)";

      // does the title already contain the suffix with an increment? if so then increment it by 1
      } else if( preg_match( "~\({$suffix} (\d)\)$~", $title, $matches ) ) {

        $just_title = preg_replace( "~^(.+) \({$suffix} \d\)$~", "$1", $title );

        return "{$just_title} ({$suffix} ".( $matches[1] + 1 ).")";

      // else, just add the suffix
      } else {

        return "{$title} ({$suffix})";

      }

    }
}
