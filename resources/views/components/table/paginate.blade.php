@props(["data" => collect([])])

@if ($data->count() > 0)
  <div class="px-4 py-3 text-white">
    {{ $data->links() }}
  </div>
@endif
