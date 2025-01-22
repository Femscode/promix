<x-layouts.admin>
    <x-slot name="title">
        {{ trans('Edit Class', ['type' => trans_choice('general.editclass', 1)]) }}
    </x-slot>
   

    <x-slot name="content">
        <x-studentclass.form.content type="customer" :model="$class" hide-logo />
    </x-slot>

    <x-studentclass.script type="customer" :contact="$class" />
</x-layouts.admin>
