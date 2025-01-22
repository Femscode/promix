<x-form.section>
    <x-slot name="head">
        <x-form.section.head
            title=""
            description="Create a new class for a bulk invoice creation." />
    </x-slot>

    <x-slot name="body">
        
       

        <x-form.group.text name="name" label="{{ trans('Class Name') }}" required />

    </x-slot>
</x-form.section>