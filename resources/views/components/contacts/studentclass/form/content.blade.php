<x-form.container>
    @php
    $segments = request()->segments();
    $secondLastSegment = $segments[count($segments) - 2] ?? null; // Handles cases with fewer segments

    @endphp
    @if($secondLastSegment == 'edit-class')
   
    <x-form

        id="{{ $formId }}"
        :route="'class.update'"
        method="post">

        <style>
            /* Alert container styling */
            .alert {
                padding: 20px;
                background-color: #f8d7da;
                /* Green */
                color: #721c24;
                margin-bottom: 15px;
                border-radius: 5px;
                position: relative;
                font-family: Arial, sans-serif;
                font-size: 16px;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            }

            /* Success alert specific styling */
            .success-alert {
                background-color: #f8d7da;
                /* A softer green */
            }

            /* Close button styling */
            .closebtn {
                margin-left: 15px;
                color: #721c24;
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

        <x-studentclass.form.billing type="{{ $type }}" />
       
        <x-studentclass.form.buttons type="{{ $type }}" />
    </x-form>
    @else
    <x-form

        id="{{ $formId }}"
        :route="'studentclasses.store'"
        method="post">
        <style>
            /* Alert container styling */
            .alert {
                padding: 20px;
                background-color: #f8d7da;
                /* Green */
                color: #721c24;
                margin-bottom: 15px;
                border-radius: 5px;
                position: relative;
                font-family: Arial, sans-serif;
                font-size: 16px;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            }

            /* Success alert specific styling */
            .success-alert {
                background-color: #f8d7da;
                /* A softer green */
            }

            /* Close button styling */
            .closebtn {
                margin-left: 15px;
                color: #721c24;
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


        <x-studentclass.form.billing type="{{ $type }}" />

        <x-studentclass.form.buttons type="{{ $type }}" />
    </x-form>

    @endif
  
</x-form.container>