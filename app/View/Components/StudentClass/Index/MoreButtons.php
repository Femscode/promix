<?php

namespace App\View\Components\StudentClass\Index;

use App\Abstracts\View\Components\StudentClass\Index as Component;

class MoreButtons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.studentclass.index.more-buttons');
    }
}
