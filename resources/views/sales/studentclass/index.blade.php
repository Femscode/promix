<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.studentclass', 2) }}</x-slot>
    <x-slot name="favorite"
        title="{{ trans_choice('general.studentclass', 2) }}"
        icon="person"
        route="class.index"
    ></x-slot>
    <x-slot name="buttons">
        <x-studentclass.index.buttons type="studentclass" />
    </x-slot>
    <x-slot name="moreButtons">
        <x-studentclass.index.more-buttons type="studentclass" />
    </x-slot>
    <x-slot name="content">
        <x-studentclass.index.content type="studentclass" :studentclass="$studentclass" />
    </x-slot>
    <x-studentclass.script type="studentclass" />
</x-layouts.admin>
