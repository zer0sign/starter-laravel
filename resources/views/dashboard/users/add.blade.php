@extends('_layouts.app')
@section('content')
@include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <section class="d-flex flex-column w-100">
        @include('_layouts.topbar')
        <?php 
        $lists = [
            ['/user', 'User'],
            ['/add', 'Add'],
        ];
        ?>
        @include('_layouts.breadcrumb',compact('lists'))
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <a href="/users/add/template" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm w-25"><i
                        class="fas fa-download fa-sm text-white-50 pr-2"></i>Template Import Excel</a>
                  </div>
                  <div class="m-4">
                    <form action="{{route('user.import')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <label for="file">File Excel</label>
                        <br>
                        <section class="d-flex justify-content-between">
                            <input required type="file" name="file" id="file">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        </section>
                    </form>
                  </div>
            </div>
        
    
        </div>
    </section>
    <!-- End of Content Wrapper -->
@endsection

@section('script')
@endsection