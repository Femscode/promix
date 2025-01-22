<x-form.container>
    <x-form
    
        id="{{ $formId }}"
        :route="'class.create'"
        method="post"
        >

        <x-class.form.billing type="{{ $type }}" />

        <x-class.form.buttons type="{{ $type }}" />
    </x-form>
</x-form.container>