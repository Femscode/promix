<?php

namespace App\View\Components\StudentClass\Form;

use App\Abstracts\View\Components\StudentClass\Form as Component;

class Billing extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.studentclass.form.billing');
    }
}
