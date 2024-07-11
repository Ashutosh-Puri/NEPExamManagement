@extends('layouts.app')
@section('main')
{{-- User Sidebar --}}
@include('layouts.user.sidebar')

  <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">
    {{-- User Navbar --}}
    @include('layouts.user.navbar')

    {{-- Uesr Main content  --}}
    <main class="min-h-screen">
      @yield('user')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
  </div>

  {{-- Setting Panels  --}}

  @include('layouts.setting-panel')
    
  {{-- User Notification Panels  --}}

  @include('layouts.user.notification-panel')
  
@endsection
