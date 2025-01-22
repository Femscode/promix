<x-form.section>
    <x-slot name="head">
        <x-form.section.head
            title="{{ trans($textSectionGeneralTitle) }}"
            description="{{ trans($textSectionGeneralDescription) }}" />
    </x-slot>

    <x-slot name="body">
        @if (! $hideName)

        <div class="relative sm:col-span-6 required" :class="[
            {'has-error': form.errors.get(&quot;name&quot;)}
        ]">
            <label class="text-black text-sm font-medium" for="name" required="required">
                Select Class

                <span class="text-red ltr:ml-1 rtl:mr-1">*</span>
            </label>
            <div class="">

                <select name='student_class' class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"  >
                    <option>--Select Class --</option>
                    @foreach (App\Models\Common\StudentClass::all() as $class)
                    <option value='{{ $class->id }}'>{{ $class->name }}</option>

                    @endforeach
                </select>
               
            </div>

            <div class="text-red text-sm mt-1 block whitespace-normal" v-if="form.errors.has(&quot;name&quot;)" v-html="form.errors.get(&quot;name&quot;)">
            </div>
        </div>



        <x-form.group.text name="name" label="{{ trans($textName) }}" form-group-class="{{ $classNameFromGroupClass }}" />
        @endif

        <div class="sm:col-span-3">
            <div class="relative sm:col-span-6 grid gap-x-8 gap-y-6">
                @if (! $hideEmail)
                <x-form.group.text name="email" label="{{ trans($textEmail) }}" form-group-class="sm:col-span-6" not-required />
                @endif

                @if (! $hidePhone)
                <x-form.group.text name="phone" label="{{ trans($textPhone) }}" form-group-class="sm:col-span-6" not-required />
                @endif

                @if (! $hideWebsite)
                <x-form.group.text name="website" label="{{ trans($textWebsite) }}" form-group-class="sm:col-span-6" not-required />
                @endif

                @if (! $hideReference)
                <x-form.group.text name="reference" label="{{ trans($textReference) }}" form-group-class="sm:col-span-6" not-required />
                @endif
            </div>
        </div>

        <div class="sm:col-span-3">
            <div class="relative sm:col-span-6 grid gap-x-8 gap-y-6">
                @if (! $hideCanLogin)
                <div class="sm:col-span-6 mt-9 mb-2">
                    @if (empty($contact))
                    <x-tooltip id="tooltip-client_portal-text" placement="bottom" message="{{ trans('customers.can_login_description') }}">
                        <x-form.group.checkbox
                            name="create_user"
                            :options="['1' => trans('customers.can_login')]"
                            @input="onCanLogin($event)" />
                    </x-tooltip>
                    @else
                    @if ($contact->user_id)
                    <x-form.group.checkbox
                        name="create_user"
                        :options="['1' => trans('customers.user_created')]"
                        checked
                        disabled />
                    @else
                    <x-tooltip id="tooltip-client_portal-text" placement="bottom" message="{{ trans('customers.can_login_description') }}">
                        <x-form.group.checkbox
                            name="create_user"
                            :options="['1' => trans('customers.can_login')]"
                            @input="onCanLogin($event)" />
                    </x-tooltip>
                    @endif
                    @endif
                </div>
                @endif

                @if (! $hideLogo)
                <x-form.group.file name="logo" label="{{ trans_choice('general.pictures', 1) }}" :value="! empty($contact) ? $contact->logo : false" form-group-class="sm:col-span-6" not-required />
                @endif
            </div>
        </div>
    </x-slot>
</x-form.section>