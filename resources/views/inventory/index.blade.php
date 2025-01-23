<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
<style>
    * {
        font-size: 0.875rem;
        line-height: 1.25rem;
    }

    .title {
        font-size: 3rem;
        font-weight: 300;
    }
</style>
<x-layouts.admin>

    <x-slot name="title"><span class="title">{{ trans_choice('Inventories', 2) }}</span></x-slot>
    <x-slot name="favorite"
        title="{{ trans_choice('general.inventory', 2) }}"
        icon="person"
        route="inventories"></x-slot>
    
    <x-slot name="content">
        <table class="table table-striped datatable" id="">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Item</th>
                    <th scope="col">Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Before Quantity</th>
                    <th scope="col">After Quantity</th>
                    <th scope="col">Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $key => $inventory)
                <tr>
                    <th scope="row">{{++$key}}</th>
                    <td>{{$inventory->item->name}}</td>
                    <td>{{$inventory->description}}</td>
                    <td>{{number_format($inventory->quantity)}}
                        @if($inventory->type == 'purchase')
                        <span class=''><i class='fa fa-arrow-trend-down'></i></span>
                        @else
                        <span class=''><i class='fa fa-arrow-trend-up'></i></span>
                        @endif

                    </td>
                    <td>{{number_format($inventory->before)}}</td>
                    <td>{{number_format($inventory->after)}}</td>
                    <td>
                        <a href="{{route('viewInventory',$inventory->item_id)}}" class="btn btn-secondary btn-sm">View Item</a>
                    </td>

                </tr>
                @endforeach


            </tbody>
        </table>
    </x-slot>
    <x-contacts.script type="customer" />
</x-layouts.admin>

<script>
    $(document).ready(function() {
        $(".datatable").DataTable({
            "lengthChange": false
        });
    });
</script>