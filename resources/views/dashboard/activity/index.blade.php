@extends('_layouts.app')
@section('content')
@include('_layouts.sidebar')
    <!-- Content Wrapper -->
    <section class="d-flex flex-column w-100">
        @include('_layouts.topbar')
        <?php 
        $lists = [
            ['/activity', 'Activity']
        ];
        ?>
        @include('_layouts.breadcrumb',compact('lists'))
        <div class="container-fluid">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Log Activity</h6>
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
                                    <th>Username</th>
                                    <th>Action</th>
                                    <th>time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activitys as $item)
                                <tr id="content-row">
                                    <td class="w-25">{{$item->username}}</td>
                                    <td class="w-50">{{$item->activity}}</td>
                                    <td class="w-25">{{date("H:i d-m-Y",strtotime($item->created_at))}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination justify-content-end" id="pagination">
                            <ul class="pagination">
                                @if ($activitys->currentPage() > 2)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $activitys->url(1) }}">1</a>
                                    </li>
                                    @if ($activitys->currentPage() > 3)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif
                        
                                @for ($i = max(1, $activitys->currentPage() - 1); $i <= min($activitys->lastPage(), $activitys->currentPage() + 1); $i++)
                                    <li class="page-item {{ $i == $activitys->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $activitys->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                        
                                @if ($activitys->currentPage() < $activitys->lastPage() - 1)
                                    @if ($activitys->currentPage() < $activitys->lastPage() - 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $activitys->url($activitys->lastPage()) }}">{{ $activitys->lastPage() }}</a>
                                    </li>
                                @endif
                        
                                @if ($activitys->currentPage() < $activitys->lastPage())
                                    <li class="page-item">
                                        {{-- <a class="page-link" href="{{ $activitys->nextPageUrl() }}">Next</a> --}}
                                        <a class="page-link load-more" data-url="{{ $activitys->nextPageUrl() }}" href="javascript:void(0)">Next</a>
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
        $('#search').on('keyup', function() {
            let query = $(this).val();
            if(query === ""){
                window.location.href = "/activity";
            }
            
            $.ajax({
                type: 'GET',
                url: '/activity/search',
                data: { query: query },
                success: function(data) {
                    let table = $('#dataTable tbody'); // Menyimpan referensi ke tbody tabel pencarian
                    $('#pagination').hide();
                    table.empty(); // Mengosongkan isi tabel

                    // Loop melalui hasil pencarian dan tambahkan baris baru ke tabel
                    $.each(data, function(index, activitys) {
                    $.each(activitys, function(index, log) {
                        let newRow = $('<tr id="content-row">' +
                            '<td class="w-25">' + log['username'] + '</td>' +
                            '<td class="w-25">' + log['activity'] + '</td>' +
                            '<td class="w-25">' + new Date(log['created_at']).toLocaleString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }).replace(/\//g, '-').replace(',', '') + '</td>' +
                            '</tr>');

                        table.append(newRow); // Menambahkan baris ke tabel
                    });
                });
                }
            });
        });
    });
</script>
@endsection