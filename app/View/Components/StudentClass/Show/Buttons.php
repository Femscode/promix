<?php

namespace App\View\Components\Class\Show;

use App\Abstracts\View\Components\Contacts\Show as Component;

class Buttons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.studentclass.show.buttons');
    }
}
