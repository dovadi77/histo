<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $rows, $thead, $componentID, $edit, $delete;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($componentID, $rows, $edit, $delete)
    {
        $this->componentID = $componentID;
        $this->thead = array_keys($rows[0]);
        $this->rows = $rows;
        $this->edit = $edit;
        $this->delete = $delete;
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
