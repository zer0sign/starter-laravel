@extends('_layouts.app')
@section('content')
@include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <section class="d-flex flex-column w-100">
        @include('_layouts.topbar')
        <?php 
        $lists = [
            ['/user', 'User']
        ];
        ?>
        @include('_layouts.breadcrumb',compact('lists'))
        <div class="container-fluid">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Users Data</h6>
                </div>
                <div class="card-body">
                    <section class="d-flex justify-content-between align-items-center mb-3">
                        {{-- search --}}
                        <div class="input-group w-25">
                            <input type="text" id="search" class="form-control" placeholder="Pencarian...">
                          </div>

                        @can('add user')
                        <a href="/users/add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm h-75"><i
                            class="fas fa-plus fa-sm text-white-50 pr-2"></i>Tambah User</a>
                        @endcan      
                    </section>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                <tr id="content-row">
                                    <td class="w-25">{{$item->nama}}</td>
                                    <td class="w-25">{{$item->username}}</td>
                                    <td class="w-25">
                                        @foreach ($item->getRoleNames() as $role)
                                            {{'#'.$role}}
                                        @endforeach
                                    </td>
                                    <td class="w-25 text-center">
                                        <form action="/users/login-as/{{$item->id}}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary badge badge-pill badge-primary">
                                                Login As
                                              </button>
                                        </form>
                                        
                                        <a href="/users/edit/{{$item->id}}" class="badge badge-pill badge-warning">Edit</a>
                                        <form action="/users/delete/{{$item->id}}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger badge badge-pill badge-danger">
                                                Delete
                                              </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination justify-content-end" id="pagination">
                            <ul class="pagination">
                                @if ($users->currentPage() > 2)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $users->url(1) }}">1</a>
                                    </li>
                                    @if ($users->currentPage() > 3)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif
                        
                                @for ($i = max(1, $users->currentPage() - 1); $i <= min($users->lastPage(), $users->currentPage() + 1); $i++)
                                    <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                        
                                @if ($users->currentPage() < $users->lastPage() - 1)
                                    @if ($users->currentPage() < $users->lastPage() - 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $users->url($users->lastPage()) }}">{{ $users->lastPage() }}</a>
                                    </li>
                                @endif
                        
                                @if ($users->currentPage() < $users->lastPage())
                                    <li class="page-item">
                                        {{-- <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a> --}}
                                        <a class="page-link load-more" data-url="{{ $users->nextPageUrl() }}" href="javascript:void(0)">Next</a>
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

    // search live
    // $(document).ready(function() {
    //     $('#search').on('keyup', function() {
    //         var query = $(this).val();
            
    //         $.ajax({
    //             type: 'GET',
    //             url: '/searchuser',
    //             data: { query: query },
    //             success: function(data) {
    //                 $('#dataTable').html(data); // Menampilkan hasil pencarian dalam tabel
    //             }
    //         });
    //     });
    // });

    $(document).ready(function() {
  let debounceTimeout; // Variabel untuk menyimpan timeout debounce

  $('#search').on('keyup', function() {
    let query = $(this).val();

    // Hapus timeout sebelumnya (jika ada)
    clearTimeout(debounceTimeout);

    // Set timeout baru untuk debounce
    debounceTimeout = setTimeout(function() {
      if (query === "") {
        window.location.href = "/users";
      } else {
        performSearch(query);
      }
    }, 1000); // Waktu tunda 1000 ms (1 detik)
  });

  // Fungsi untuk melakukan pencarian
  function performSearch(query) {
    $.ajax({
      type: 'GET',
      url: '/searchuser',
      data: { query: query },
      success: function(data) {
        let table = $('#dataTable tbody');
        $('#pagination').hide();
        table.empty();

        $.each(data, function(index, users) {
          $.each(users, function(index, user) {
            let newRow = $('<tr id="content-row">' +
              '<td class="w-25">' + user['nama'] + '</td>' +
              '<td class="w-25">' + user['username'] + '</td>' +
              '<td class="w-25">' + (user['roles'].length > 0 ? user['roles'][0]['name'] : '') + '</td>' +
              '<td class="w-25 text-center">' +
              '<form action="/users/login-as/' + user['id'] + '" method="POST" class="d-inline">' +
              '@csrf' +
              '<button type="submit" class="btn btn-primary badge badge-pill badge-primary">Login As</button>' +
              '</form>' +
              '<a href="/users/edit/' + user['id'] + '" class="badge badge-pill badge-warning">Edit</a>' +
              '<a href="/users/delete/' + user['id'] + '" class="badge badge-pill badge-danger">Delete</a>' +
              '</td>' +
              '</tr>');

            table.append(newRow);
          });
        });
      }
    });
  }
});

</script>
@endsection