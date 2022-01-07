<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $id, $name, $type, $label, $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $name, $type, $label, $value = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input');
    }
}
