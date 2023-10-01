@extends('_layouts.app')
@section('content')
    @include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <section class="d-flex flex-column w-100">
        @include('_layouts.topbar')
        <?php
        $lists = [['/user', 'User'], ['/edit', 'Edit']];
        ?>
        @include('_layouts.breadcrumb', compact('lists'))
        <div class="container-fluid">
            <div class="card shadow mb-4 p-4">
                <p class="d-none" id="user_id">{{ $data->id }}</p>
                <form action="/users/edit/{{ $data->id }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input name="username" type="text"
                                    class="form-control form-control-user {{ session('errors') ? 'is-invalid' : '' }}"
                                    id="username" name="" value="{{ $data->username }}" placeholder="username">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="username">Nama</label>
                                <input name="nama" type="text"
                                    class="form-control form-control-user {{ session('errors') ? 'is-invalid' : '' }}"
                                    id="exampleInputPassword" name="nama" value="{{ $data->nama }}" placeholder="nama">
                            </div>
                        </div>
                    </div>
                    <section class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </section>
                </form>


                {{-- table role --}}
                <label for="username">Role User</label>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $item)
    <tr id="content-row">
        <td class="w-50">{{ $item->name }}</td>
        <td class="w-25">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value=""
                    id="flexCheckDefault"
                    @foreach ($data->roles as $role)
                        @if ($role->name === $item->name)
                            checked
                        @endif
                    @endforeach
                >
                {{-- label --}}
                <label class="form-check-label" for="flexCheckDefault">
                    @php $found = false; @endphp
                    @foreach ($data->roles as $role)
                        @if ($role->name === $item->name)
                            Aktif
                            @php $found = true; break; @endphp
                        @endif
                    @endforeach
                    @if (!$found)
                        Tidak Aktif
                    @endif
                </label>
            </div>
        </td>
    </tr>
@endforeach

                        </tbody>
                    </table>
                </div>

            </div>


        </div>
    </section>
    <!-- End of Content Wrapper -->
@endsection

@section('script')
    <script>
        $('#dataTable').dataTable({
            "searching": false,
            "paging": false,
            "ordering": false,
            "info": false
        });

        // change role
        $(document).ready(function() {
            $('input[type="checkbox"]').change(function() {
                let checkbox = $(this);
                let rolesNames = $(this).closest('tr').find('td:eq(0)').text();
                let user_id = $('#user_id').text();
                let is_active = checkbox.prop('checked');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('changeRoles') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'userId': user_id,
                        'rolesNames': rolesNames,
                        'is_active': is_active
                    },
                    success: function(response) {
                        // console.log(response)
                        if (response.status === 'nonaktif') {
                            // Ubah label checkbox menjadi "Tidak Aktif"
                            checkbox.closest('td').find('.form-check-label').text(
                            'Tidak Aktif');
                        } else if (response.status === 'aktif') {
                            checkbox.closest('td').find('.form-check-label').text('Aktif');
                        }
                    }
                });
            });
        });
    </script>
@endsection
