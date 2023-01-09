<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormGroupInput extends Component
{
    public $name;
    public $val;
    public $id;
    public $additional_class;
    public $label;
    public $placeholder;
    public $required;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name = 'name',
        $val = '',
        $id = 'name',
        $additional_class = '',
        $label = 'Label',
        $placeholder = '',
        $required = false
    )
    {
        $this->name = $name;
        $this->val = $val;
        $this->id = $id;
        $this->additional_class = $additional_class;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-group-input');
    }
}
