@if($checkPermissionCreate ?? 1)

            <x-link href="{{ route('studentclasses.create') }}" kind="primary" id="index-more-actions-new-{{ $type ?? 'studentclass' }}">
                {{ trans('Add New', ['type' => trans_choice($textPage, 1)]) }} 
            </x-link>
       
@else
    @if (! $hideCreate)
        <x-link href="{{ route('studentclass.create') }}" kind="primary" id="index-more-actions-new-{{ $type ?? 'studentclass' }}">
            {{ trans('Add New', ['type' => trans_choice($textPage, 1)]) }}
        </x-link>
    @endif
@endif
