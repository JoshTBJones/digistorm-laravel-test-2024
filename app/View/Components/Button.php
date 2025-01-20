<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public string $type;
    public string $color;
    public string $label;

    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param string $color
     * @param string $label
     */
    public function __construct(
        string $type = 'button',
        string $color = 'blue',
        string $label = 'Click Me'
    ) {
        $this->type = $type;
        $this->color = $color;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form.button');
    }
}
