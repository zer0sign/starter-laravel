@extends('_layouts.app')
@section('content')
@include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <section class="d-flex flex-column w-100">
        @include('_layouts.topbar')
        <?php 
        $lists = [
            ['/permissions', 'Permissions'],
            ['/permissions/Add', 'Add'],
        ];
        ?>
        @include('_layouts.breadcrumb',compact('lists'))
        <div class="container-fluid">
            <div class="card shadow mb-4">
                  <div class="m-4">
                    <form action="{{route('permissions.storeRoles')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            @if($errors->has('nama_role'))
                                <p class="text-danger text-xs px-1">{{ $errors->first('nama_role') }}</p>
                            @endif
                            <label for="">Nama Role</label>
                            <input type="text" class="form-control form-control-user {{session('errors') ? 'is-invalid' : ''}}"
                                id="exampleInputEmail" name="nama_role" aria-describedby="emailHelp"
                                placeholder="Nama Role">
                        </div>
                        <section class="d-flex justify-content-end">
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