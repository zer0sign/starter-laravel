@extends('_layouts.app')
@section('content')
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg mt-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Selamat Datang!</h1>
                                </div>

                                <form class="user" method="POST" action="{{route('authenticate')}}">
                                    @csrf
                                    @if(session('errors'))
                                            <p class="text-danger text-xs px-1">{{ session('errors') }}</p>
                                    @endif
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user {{session('errors') ? 'is-invalid' : ''}}"
                                            id="exampleInputEmail" name="username" aria-describedby="emailHelp"
                                            placeholder="Username">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user {{session('errors') ? 'is-invalid' : ''}}"
                                            id="exampleInputPassword" name="password" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" name="remember_me" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Remember
                                                Me</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">Masuk</button>
                                    <hr>
                                    <p class="text-xs" style="text-align: justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam nemo et a, animi aspernatur blanditiis obcaecati. Qui est dignissimos labore perferendis. Enim excepturi expedita reprehenderit laboriosam. Cupiditate quae libero placeat?</p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
