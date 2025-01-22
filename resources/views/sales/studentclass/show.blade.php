<x-layouts.admin>
    <x-slot name="title">
        {{ $customer->name }}
    </x-slot>

    <x-slot name="info">
        @if (! $customer->enabled)
            <x-index.disable text="{{ trans_choice('general.customers', 1) }}" />
        @endif
    </x-slot>

    <x-slot name="favorite"
        title="{{ $customer->name }}"
        icon="person"
        :route="['customers.show', $customer->id]"
    ></x-slot>

    <x-slot name="buttons">
        <x-studentclass.show.buttons type="customer" :model="$customer" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-studentclass.show.more-buttons type="customer" :model="$customer" />
    </x-slot>

    <x-slot name="content">
        <x-studentclass.show.content type="customer" :model="$customer" />
    </x-slot>

    <x-studentclass.script type="customer" />
</x-layouts.admin>
