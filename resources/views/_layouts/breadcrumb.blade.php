<div class="px-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
            @foreach ($lists as $item)
            <li class="breadcrumb-item active" aria-current="page">{{ $item[1] }}</li>
            @endforeach
        </ol>
      </nav>
</div>