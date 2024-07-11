
@extends('layouts.app')
@section('main')
{{-- User Sidebar --}}
@include('layouts.student.sidebar')

  <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">
    {{-- User Navbar --}}
    @include('layouts.student.navbar')

    {{-- Uesr Main content  --}}
    <main class="min-h-screen">
      @yield('student')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
  </div>

  {{-- Setting Panels  --}}

  @include('layouts.setting-panel')
    
  {{-- User Notification Panels  --}}

  @include('layouts.student.notification-panel')
  
@endsection
