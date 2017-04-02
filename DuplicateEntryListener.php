<?php

namespace Statamic\Addons\DuplicateEntry;

use Statamic\Extend\Listener;

class DuplicateEntryListener extends Listener
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

      return $this->js->tag('addButtons.min');

    }
}
