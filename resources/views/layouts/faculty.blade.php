@extends('layouts.app')
@section('main')
{{-- User Sidebar --}}
@include('layouts.faculty.sidebar')

  <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">
    {{-- User Navbar --}}
    @include('layouts.faculty.navbar')

    {{-- Uesr Main content  --}}
    <main class="min-h-screen">
      @yield('faculty')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
  </div>

  {{-- Setting Panels  --}}

  @include('layouts.setting-panel')
    
  {{-- User Notification Panels  --}}

  @include('layouts.faculty.notification-panel')
  
@endsection
