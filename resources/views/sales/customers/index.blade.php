<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('Students', 2) }}</x-slot>
    <x-slot name="favorite"
        title="{{ trans_choice('general.customers', 2) }}"
        icon="person"
        route="customers.index"
    ></x-slot>
    <x-slot name="buttons">
        <x-contacts.index.buttons type="customer" />
    </x-slot>
    <x-slot name="moreButtons">
        <x-contacts.index.more-buttons type="customer" />
    </x-slot>
    <x-slot name="content">
        <x-contacts.index.content type="customer" :contacts="$customers" />
    </x-slot>
    <x-contacts.script type="customer" />
</x-layouts.admin>

<script>
    document.getElementById('classFilter').addEventListener('change', function() {
        const selectedClass = this.value.toLowerCase();
        const rows = document.querySelectorAll('#studentsTable tbody tr');

        rows.forEach(row => {
            const rowClass = row.getAttribute('data-class')?.toLowerCase() || '';
            if (!selectedClass || rowClass === selectedClass) {
                row.style.display = ''; // Show the row
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });
    });
</script>
