@extends('_layouts.app')
@section('content')
@include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <section class="d-flex flex-column w-100">
        @include('_layouts.topbar')
        <?php 
        $lists = [
            ['/permissions', 'Permissions']
        ];
        ?>
        @include('_layouts.breadcrumb',compact('lists'))
        <div class="container-fluid">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Roles Data</h6>
                </div>
                <div class="card-body">
                    <section class="d-flex justify-content-between align-items-center mb-3">
                        {{-- search --}}
                        <div class="input-group w-25">
                            <input type="text" id="search" class="form-control" placeholder="Pencarian...">
                          </div>

                        
                        <a href="{{route('addRoles')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm h-75"><i
                            class="fas fa-plus fa-sm text-white-50 pr-2"></i>Tambah Role</a>
                        
                    </section>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>created_at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $item)
                                <tr id="content-row">
                                    <td class="w-50">{{$item->name}}</td>
                                    <td class="w-25">{{$item->created_at}}</td>
                                    <td class="w-25 text-center">
                                        <a href="/permissions/{{$item->id}}" class="badge badge-pill badge-primary">Role Permissions</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination justify-content-end">
                            <ul class="pagination">
                                @if ($roles->currentPage() > 2)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $roles->url(1) }}">1</a>
                                    </li>
                                    @if ($roles->currentPage() > 3)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif
                        
                                @for ($i = max(1, $roles->currentPage() - 1); $i <= min($roles->lastPage(), $roles->currentPage() + 1); $i++)
                                    <li class="page-item {{ $i == $roles->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $roles->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                        
                                @if ($roles->currentPage() < $roles->lastPage() - 1)
                                    @if ($roles->currentPage() < $roles->lastPage() - 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $roles->url($roles->lastPage()) }}">{{ $roles->lastPage() }}</a>
                                    </li>
                                @endif
                        
                                @if ($roles->currentPage() < $roles->lastPage())
                                    <li class="page-item">
                                        {{-- <a class="page-link" href="{{ $roles->nextPageUrl() }}">Next</a> --}}
                                        <a class="page-link load-more" data-url="{{ $roles->nextPageUrl() }}" href="javascript:void(0)">Next</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        
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
</script>
@endsection