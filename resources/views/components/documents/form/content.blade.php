<x-loading.content />
<div class="relative mt-4">
    <x-form 
        id="{{ $formId }}"
        :route="$formRoute"
        method="{{ $formMethod }}"
        :model="$document"
    >
        @if (! $hideCompany)
            <x-documents.form.company :type="$type"  />
        @endif


        <x-documents.form.main type="{{ $type }}" />

        @if ($showRecurring)
            <x-documents.form.recurring type="{{ $type }}" />
        @endif

        @if (! $hideAdvanced)
            <x-documents.form.advanced type="{{ $type }}" />
        @endif

        <x-form.input.hidden name="type" :value="old('type', $type)" x-model="form.type" />
        <x-form.input.hidden name="status" :value="old('status', $status)" x-model="form.status" />
        <x-form.input.hidden name="amount" :value="old('amount', '0')" x-model="form.amount" />

        @if (! $hideButtons)
            <x-documents.form.buttons :type="$type" />
        @endif
    </x-form>
</div>
