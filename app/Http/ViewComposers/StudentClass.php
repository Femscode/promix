<?php

namespace App\Http\ViewComposers;

use App\Http\Controllers\Sales\StudentClass as SC;
use Illuminate\View\View;

class StudentClass
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $route = request()->route();

        if (empty($route)) {
            return;
        }

        /** @var SC $controller */
        $controller = $route->getController();

        $view->with(['type' => $controller->type ?? '']);
    }
}
