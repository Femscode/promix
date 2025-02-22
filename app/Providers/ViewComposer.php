<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Support\Facades\View;

class ViewComposer extends Provider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Add Contact Type
        View::composer(
            ['contacts.*'],
            'App\Http\ViewComposers\ContactType'
        );
        // Add Student Class
        View::composer(
            ['studentclasses.*'],
            'App\Http\ViewComposers\StudentClass'
        );

        // Add Document Type
        View::composer(
            ['documents.*', 'portal.documents.*'],
            'App\Http\ViewComposers\DocumentType'
        );

        // Document Recurring Metadata
        View::composer(
            ['components.documents.form.metadata'],
            'App\Http\ViewComposers\DocumentRecurring'
        );

        View::composer(
            ['components.layouts.admin.notifications'],
            'App\Http\ViewComposers\ReadOnlyNotification'
        );

        View::composer(
            ['components.layouts.admin.header'],
            'App\Http\ViewComposers\PlanLimits'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
