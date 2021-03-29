@once
    @push('lib-styles')
        <link href="{{ asset('assets/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
        <style>
            .dataTables_length select {
                width: 60px !important;
            }
            table.dataTable {
                width: 100% !important;
            }
        </style>
    @endpush
    @push('lib-scripts')
        <script src="{{ asset('assets/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    @endpush
@endonce