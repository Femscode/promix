<x-form.section>
    <x-slot name="head">
        <x-form.section.head
            title="{{ trans($textSectionGeneralTitle) }}"
            description="{{ trans($textSectionGeneralDescription) }}"
        />
    </x-slot>

    <x-slot name="body">
        @if (! $hideName)
      
            <x-form.group.text name="name" label="{{ trans($textName) }}" form-group-class="{{ $classNameFromGroupClass }}" />
        @endif

      
    </x-slot>
</x-form.section>
