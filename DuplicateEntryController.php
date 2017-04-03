<?php

namespace Statamic\Addons\DuplicateEntry;

use Statamic\Extend\Controller;
use Statamic\API\Entry;
use Statamic\API\Helper;
use Statamic\API\Stache;

class DuplicateEntryController extends Controller
{
    /**
     * Maps to your route definition in routes.yaml
     *
     * @return Illuminate\Http\Response
     */

    public function duplicate( $collection, $slug )
    {

      //get the entry to duplicate
      $entry = Entry::whereSlug( $slug, $collection );

      //work out the slug, if the current slug doesn't already end with a timestamp, add one, if it does, update it
      $slug = $entry->slug();
      if( ! preg_match( '~[0-9]{10}$~', $entry->slug() ) ) {
        $slug.= '-'.time();
      } else {
        $slug = preg_replace( '~[0-9]{10}$~', time(), $slug );
      }

      //Create duplicate entry - modify the slug with the current timestamp to ensure it is unique
      $duplicate_entry = Entry::create( $slug )
          ->collection( $collection )
          ->with( $entry->data() )
          ->get();

      // set the order
      $duplicate_entry->order( $entry->order() );

      //unpublish as default
      $duplicate_entry->published( false );

      //add `(copy)` after the title
      $duplicate_entry->set( 'title', $this->getTitle( $entry->get('title') ) );

      //make a new ID (clear the old one first)
      $duplicate_entry->set( 'id', '' );
      $duplicate_entry->id( Helper::makeUuid() );

      //and finally... save it
      $duplicate_entry->save();

      //update the stache
      Stache::update();

      //go back to the collection index, with a sucess message
      return back()->with('success', trans('addons.DuplicateEntry::settings.success', ['title' => $entry->get('title')]));

    }

    // This should be smarter and consider the names of other entries, and not just the one being
    // duplicated, i.e. if the entry is called `My Entry (copy)`, it should check to see if there is
    // already an entry called `My Entry (copy 2)` before blindy calling the duplicated entry this. 
    private function getTitle( $title )
    {

      $suffix = 'copy';

      //does the title already contain the suffix? if so then increment it to 2
      if( preg_match( "~ \({$suffix}\)$~", $title ) ) {

        $just_title = preg_replace( "~^(.+) \({$suffix}\)$~", "$1", $title );

        return "{$just_title} ({$suffix} 2)";

      //does the title already contain the suffix with an increment? if so then increment it by 1
      } else if( preg_match( "~\({$suffix} (\d)\)$~", $title, $matches ) ) {

        $just_title = preg_replace( "~^(.+) \({$suffix} \d\)$~", "$1", $title );

        return "{$just_title} ({$suffix} ".( $matches[1] + 1 ).")";

      //else, just add the suffix
      } else {

        return "{$title} ({$suffix})";

      }

    }
}
