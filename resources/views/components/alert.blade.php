
@if (session()->has('session-alert'))
    @foreach (session('session-alert') as $key => $alert)
        <x-toast :type="$alert['type']" :key="$key">
            {{ $alert['message'] }}
        </x-toast>
    @endforeach
@endif
