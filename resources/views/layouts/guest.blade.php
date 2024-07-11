@extends('layouts.app')
@section('main')
  <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">
    {{--Main content  --}}
    <main class="min-h-screen">
      @yield('guest')
    </main>
  </div>

  {{--  Theam Toggle Buttons  --}}
  @include('layouts.dark-mode-toggle')

  {{-- Setting Panels  --}}
  @include('layouts.setting-panel')
@endsection
