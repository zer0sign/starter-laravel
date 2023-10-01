@extends('_layouts.app')
@section('content')
@include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <section class="d-flex flex-column w-100">
        @include('_layouts.topbar')
        <?php 
        $lists = [
            ['/permissions', 'Permissions'],
            ['/permissions/Edit', 'Edit'],
        ];
        ?>
        @include('_layouts.breadcrumb',compact('lists'))
        <div class="container-fluid">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{$role_permissions->name}} Permissions</h6>
                    <p class="d-none" id="role_id">{{$role_permissions->id}}</p>
                </div>
                <div class="card-body">
                    <section class="d-flex justify-content-between align-items-center mb-3">
                        {{-- search --}}
                        <div class="input-group w-25">
                            <input type="text" id="search" class="form-control" placeholder="Pencarian...">
                          </div>                        
                    </section>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Permission</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all as $permission)
                                    <tr id="content-row">
                                        <td class="w-50">{{$permission->name}}</td>
                                        <td class="w-25">{{$permission->created_at}}</td>
                                        <td class="w-25">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" 
                                                       @if ($role_permissions->permissions->contains('name', $permission->name)) checked @endif>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    @if ($role_permissions->permissions->contains('name', $permission->name)) Aktif @else Tidak Aktif @endif
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
    
        </div>
    </section>

    <!-- End of Content Wrapper -->
@endsection

@section('script')
<script>
    $('#dataTable').dataTable( {
        "searching": false,
        "paging" :false,
        "ordering": false,
        "info":     false
    } );

    // ajax
    $(document).ready(function() {
        // Function to load data via AJAX
        function loadPageData(url) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "html",
                success: function(response) {
                    // Replace the table body with the new data
                    $("#dataTable tbody").html($(response).find("#dataTable tbody").html());
                    // Update the pagination links
                    $(".pagination").html($(response).find(".pagination").html());
                    // Reattach click event handlers to the newly loaded links
                    attachClickHandlers();
                }
            });
        }

        // Function to attach click event handlers to pagination links
        function attachClickHandlers() {
            $(".pagination").off("click", ".page-link"); // Remove existing click event handlers
            $(".pagination").on("click", ".page-link", function(e) {
                e.preventDefault();
                var pageUrl = $(this).attr("href");
                if (pageUrl) {
                    loadPageData(pageUrl);
                }
            });
        }

        // Handle click events on the "Next" button separately
        $(".pagination").on("click", ".load-more", function(e) {
            e.preventDefault();
            var nextPageUrl = $(this).data("url");
            if (nextPageUrl) {
                loadPageData(nextPageUrl);
            }
        });

        // Initial attachment of click event handlers
        attachClickHandlers();
    });

    // change permission
    $(document).ready(function() {
        $('input[type="checkbox"]').change(function() {
            let checkbox = $(this);
            let permissionName = $(this).closest('tr').find('td:eq(0)').text();
            let roleId = $('#role_id').text();
            var is_active = checkbox.prop('checked');
            $.ajax({
                type: 'POST',
                url: "{{ route('changePermission') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'role_id': roleId,
                    'permission_name': permissionName,
                    'is_active': is_active
                },
                success: function(response) {
                    console.log(response)
                    if (response.status === 'nonaktif') {
                        // Ubah label checkbox menjadi "Tidak Aktif"
                        checkbox.closest('td').find('.form-check-label').text('Tidak Aktif');
                    }else if(response.status === 'aktif'){
                        checkbox.closest('td').find('.form-check-label').text('Aktif');
                    }
                }
            });
        });
    });
</script>
@endsection