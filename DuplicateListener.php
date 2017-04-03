<?php

namespace Statamic\Addons\Duplicate;

use Statamic\Extend\Listener;

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

      return $this->js->tag('addButtons.min');

    }
}
