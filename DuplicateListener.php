<?php

namespace Statamic\Addons\Duplicate;

use Statamic\Extend\Listener;
use Statamic\API\URL;

class DuplicateListener extends Listener
{
    /**
     * The events to be listened for, and the methods to call.
     *
     * @var array
     */

    public $events = [
      'cp.add_to_head' => 'js'
    ];


    public function js()
    {

        // get the current URL
        $current_url = URL::getCurrent();

        // if on a relevant URL then add in the duplicate buttons...
        if( 

            // on /cp/pages (i.e. the pages index)
            $current_url === '/'.CP_ROUTE.'/pages' ||
            
            // on /cp/collections/entries/* (i.e. the index of any collection)
            preg_match( '~^/'.CP_ROUTE.'/collections/entries/[^/]+$~' , $current_url )

        ) {
            return $this->js->tag('addButtons.min');
        }
        
    }
}
