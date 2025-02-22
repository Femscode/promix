<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\ContactCreated;
use App\Events\Common\ContactCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Common\CreateContactPersons;
use App\Models\Common\StudentClass;
use Illuminate\Support\Str;

class CreateStudentClass extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): StudentClass
    {
        event(new ContactCreating($this->request));

        \DB::transaction(function () {
           
            // dd($this->request->all());
            $this->model = StudentClass::create($this->request->all());

            // Upload logo
            if ($this->request->file('logo')) {
                $media = $this->getMedia($this->request->file('logo'), Str::plural($this->model->type));

                $this->model->attachMedia($media, 'logo');
            }

            // $this->dispatch(new CreateContactPersons($this->model, $this->request));
        });

        // event(new ContactCreated($this->model, $this->request));

        return $this->model;
    }

    public function createUser(): void
    {
        // Check if user exist
        if ($user = user_model_class()::where('email', $this->request['email'])->first()) {
            $message = trans('messages.error.customer', ['name' => $user->name]);

            throw new \Exception($message);
        }

        $customer_role_id = role_model_class()::all()->filter(function ($role) {
            return $role->hasPermission('read-client-portal');
        })->pluck('id')->first();

        $this->request->merge([
            'locale' => setting('default.locale', 'en-GB'),
            'roles' => $customer_role_id,
            'companies' => [$this->request->get('company_id')],
        ]);

        $user = $this->dispatch(new CreateUser($this->request));

        $this->request['user_id'] = $user->id;
    }
}
