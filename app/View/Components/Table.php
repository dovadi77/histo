<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $rows, $thead, $componentID;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($componentID, $rows)
    {
        $this->componentID = $componentID;
        $this->thead = array_keys($rows[0]);
        $this->rows = $rows;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table');
    }
}
