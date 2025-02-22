<?php

namespace App\View\Components\StudentClass\Show;

use App\Abstracts\View\Components\Contacts\Show as Component;

class MoreButtons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.studentclass.show.more-buttons');
    }
}
