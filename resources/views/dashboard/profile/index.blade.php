@extends('_layouts.app')
@section('content')
@include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <section class="d-flex flex-column w-100">
        @include('_layouts.topbar')
        <?php 
        $lists = [
            ['/profile', 'Profile']
        ];
        ?>
        @include('_layouts.breadcrumb',compact('lists'))
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Basic Card Example</h6>
                </div>
                <div class="card-body">
                    The styling for this basic card example is created by using default Bootstrap
                    utility classes. By using utility classes, the style of the card component can be
                    easily modified with no need for any custom CSS!
                </div>
            </div>
        </div>
    </section>
    <!-- End of Content Wrapper -->
@endsection

@section('script')

@endsection