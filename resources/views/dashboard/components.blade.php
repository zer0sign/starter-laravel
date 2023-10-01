@extends('_layouts.app')

@section('content')
@include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <?php 
    $lists = [
            ['/components', 'Components'],
            // Tambahkan objek lain di sini
        ];
    ?>
    <section class="d-flex flex-column">
        @include('_layouts.topbar')
        @include('_layouts.breadcrumb',compact('lists'))
        @include('_layouts.card')
        @include('_layouts.table')
        @include('_layouts.chart')
        @include('_layouts.footer')
    </section>
    <!-- End of Content Wrapper -->
@endsection

@section('script')
<script src="{{asset('js/demo/chart-area-demo.js')}}"></script>
<script src="{{asset('js/demo/chart-pie-demo.js')}}"></script>
<script src="{{asset('js/demo/chart-bar-demo.js')}}"></script>
@endsection