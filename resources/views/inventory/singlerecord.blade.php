<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    table,
    th,
    td {
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    .barchart {
            /* height: 250px !important; */
          
        }
</style>
<x-layouts.admin>

    <x-slot name="title"><span class="title">{{ $item->name }} {{ trans_choice('Inventories', 2) }}</span></x-slot>
    <x-slot name="favorite"
        title="{{ trans_choice('general.inventory', 2) }}"
        icon="person"
        route="inventories"></x-slot>

    <x-slot name="content">
        <div class="container mt-5">
            <div class="row">
                <!-- Pie Chart -->
                <div class="col-md-6">
                    <canvas id="pieChart"></canvas>
                </div>
                <!-- Line Chart -->
                <div class="col-md-6 ">
                    <canvas class="barchart" id="barChart"></canvas>
                </div>
            </div>
        </div>
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

<script>
    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Total Quantities','Available Quantities', 'Total Sold'],
            datasets: [{
                label: 'Quantities',
                data: [@json($totalquantity), @json($availablequantity), @json($totalsold)],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                ]
            }]
        },
        options: {
            responsive: true
        }
    });

    // Line Chart

    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('barChart').getContext('2d');
        const ordersChart = new Chart(ctx, {
            type: 'bar', // Bar chart
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Total Quantities Bought',
                    data: [
                        {{ $january ?? 0 }},
                        {{ $february ?? 0 }},
                        {{ $march ?? 0 }},
                        {{ $april ?? 0 }},
                        {{ $may ?? 0 }},
                        {{ $june ?? 0 }},
                        {{ $july ?? 0 }},
                        {{ $august ?? 0 }},
                        {{ $september ?? 0 }},
                        {{ $october ?? 0 }},
                        {{ $november ?? 0 }},
                        {{ $december ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

</script>