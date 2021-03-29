@once
    @push('lib-styles')
        <link rel="stylesheet" href="{{ asset('assets/vendors/ladda/css/ladda-themeless.min.css') }}">
    @endpush
    @push('lib-scripts')
        <script src="{{ asset('assets/vendors/ladda/js/spin.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/ladda/js/ladda.min.js') }}"></script>
    @endpush
@endonce