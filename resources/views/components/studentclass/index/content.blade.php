@if ($hideEmptyPage || ($studentclasses->count() || request()->get('search', false)))

<x-index.container>
    @if ((! $hideSearchString) && (! $hideBulkAction))
    <x-index.search
        search-string="{{ $searchStringModel }}"
        bulk-action="{{ $bulkActionClass }}"
        route="{{ $searchRoute }}" />
    @elseif ((! $hideSearchString) && $hideBulkAction)
    <x-index.search
        search-string="{{ $searchStringModel }}"
        route="{{ $searchRoute }}" />
    @elseif ($hideSearchString && (! $hideBulkAction))
    <x-index.search
        bulk-action="{{ $bulkActionClass }}"
        route="{{ $searchRoute }}" />
    @endif

    <!-- @if(Session::has('message')) -->

    <!-- @endif -->

    <x-table>
        <style>
            /* Alert container styling */
            .alert {
                padding: 20px;
                background-color: #d4edda;
                /* Green */
                color: #155724;
                margin-bottom: 15px;
                border-radius: 5px;
                position: relative;
                font-family: Arial, sans-serif;
                font-size: 16px;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            }

            /* Success alert specific styling */
            .success-alert {
                background-color: #d4edda;
                /* A softer green */
            }

            /* Close button styling */
            .closebtn {
                margin-left: 15px;
                color: #155724;
                font-weight: bold;
                float: right;
                font-size: 20px;
                line-height: 20px;
                cursor: pointer;
                transition: 0.3s;
            }

            /* Hover effect for close button */
            .closebtn:hover {
                color: black;
            }
        </style>
        @if(Session::has('message'))
        <div class="alert success-alert">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            {{ Session::get('message') }}
        </div>
        @endif

        <x-table.thead>
            <x-table.tr>
                @if (! $hideBulkAction)
                <x-table.th class="{{ $classBulkAction }}" override="class">
                    <x-index.bulkaction.all />
                </x-table.th>
                @endif

                @stack('name_and_tax_number_th_start')
                <x-table.th class="{{ $classNameAndTaxNumber }}">

                    <x-slot name="first">
                        <x-sortablelink column="name" title="{{ trans($textName) }}" />
                    </x-slot>


                </x-table.th>


                <x-table.th class="{{ $classOpenAndOverdue }}" kind="amount">

                    <x-slot name="first">
                        {{ trans('Actions') }}
                    </x-slot>

                </x-table.th>

            </x-table.tr>
        </x-table.thead>

        <x-table.tbody>
            @foreach($studentclasses as $item)
            <x-table.tr href="{{ route($routeButtonShow, $item->id) }}">
                @if (! $hideBulkAction)
                <x-table.td class="{{ $classBulkAction }}" override="class">
                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                </x-table.td>
                @endif


                <x-table.td class="{{ $classNameAndTaxNumber }}">

                    <x-slot name="first" class="flex items-center">


                        <div class="font-bold truncate {{ $showLogo ? 'ltr:lg:pl-8 rtl:lg:pr-8' : '' }}">
                            {{ $item->name }}
                        </div>


                    </x-slot>



                </x-table.td>



                <x-table.td class="{{ $classOpenAndOverdue }}" kind="amount">


                    <a class='px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-blue hover:bg-info-700 text-white disabled:bg-secondary-100' href='{{route("class.edit",[$item->id])}}'>Edit</a>
                    <a class='px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-red hover:bg-info-700 text-white disabled:bg-secondary-100' onclick="return confirm('Are you sure you want to delete this class');" href='{{route("class.delete",[$item->id])}}'>Delete</a>
                </x-table.td>


            </x-table.tr>
            @endforeach
        </x-table.tbody>
    </x-table>

    <x-pagination :items="$studentclasses" />
</x-index.container>
@else
<x-empty-page
    group="{{ $group }}"
    page="{{ $page }}"
    image-empty-page="{{ $imageEmptyPage }}"
    text-empty-page="{{ $textEmptyPage }}"
    url-docs-path="{{ $urlDocsPath }}"
    create-route="{{ $createRoute }}"
    check-permission-create="{{ $checkPermissionCreate }}"
    permission-create="{{ $permissionCreate }}" />
@endif