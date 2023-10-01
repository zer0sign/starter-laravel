@extends('_layouts.app')

@section('content')

@include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            @include('_layouts.topbar')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Keterangan</h1>
                </div>
                <p>ini Adalah Templat Starter-pack yang dibuat oleh hasan pada september 2023, yang digunakan untuk
                    kebutuhan pengembangan web yang sering dibutuhkun pada umumnya, ada beberapa plugin yang ada dalam template ini, antara lain.
                </p>
                <section class="mx-3">
                    <details>
                        <summary>Laravel 8</summary>
                        <p>Laravel 8 dipilih Karena Berdasarkan Mayoritas server masih menggunakan PHP 7.4, sedangkan penambahan
                            fitur dari PHP 7 ke 8(PHP Terbaru) tidak signifikan.</p>
                      </details>
                      <details>
                        <summary>SB Admin 2</summary>
                        <p>dipilih karena kemudahan dan support bootstrap versi 5, yang notabennya bootstrap versi terbaru, ditambah komponen
                            komponennya jauh lebih bagus dari bootstrap 5 standard,dan sesuai dengan kebutuhan dashboard template ini.</p>
                      </details>
                      <details>
                        <summary>Clockwork</summary>
                        <p>untuk analisis optimasi project laravel,seperti performa dan database checking, bisa dilihat di inspect lalu cek clockwork, kalau tidak muncul, i
                            install extension terlebih dahulu.</p>
                      </details>
                      <details>
                        <summary>Basic Auth</summary>
                        <p>Seperti Login & Logout Sudah disiapkan, serta Login As untuk role superadmin.</p>
                      </details>
                      <details>
                        <summary>Role Management</summary>
                        <p>bisa menerapkan multi role, dengan menggunakan spatie.
                            agar bisa membuat user login sbg role tertentu bisa menggunakan <code>$roles = Role::find($id);

                                Session::put('nama_role', $roles->name);
                                Session::save();</code>
                        </p>
                      </details>
                      <details>
                        <summary>Users Management</summary>
                        <p>Bisa CRUD user lalu bisa import dari Excel.</p>
                      </details>
                      <details>
                        <summary>Activity Log</summary>
                        <p>semua activity user bisa dimasukkan dengan helper yang sudah disiapkan, direkomendasikan
                            hanya buat activity log jika user sedang melakukan perubahan data.</p>
                      </details>
                      <details>
                        <summary>Sweet Alert</summary>
                        <p>untuk feedback user</p>
                      </details>
                </section>
                

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->
@endsection