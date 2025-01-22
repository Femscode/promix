<x-layouts.admin>
    <x-slot name="title">
        {{ trans('Create New Class', ['type' => trans_choice('general.customers', 1)]) }}
    </x-slot>
    <x-slot name="favorite"
        title="{{ trans('Create New Class', ['type' => trans_choice('general.customers', 1)]) }}"
        icon="person"
        route="studentclasses.store"
    ></x-slot>

    <x-slot name="content">
        <x-studentclass.form.content type="class" hide-logo />
    </x-slot>

    <x-studentclass.script type="class" />
</x-layouts.admin>
