@extends('layouts.user')
@section('user')
  <div>
    <x-breadcrumb.breadcrumb>
      <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
      <x-breadcrumb.link name="Mark List's" />
    </x-breadcrumb.breadcrumb>
    <x-card-header heading="Mark List's" />
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Mark List
      </div>
      <div class="grid grid-cols-1 md:grid-cols-1">
        <form method="post" action="{{ route('user.generate_marklist') }}">
          @csrf
          @livewire('user.cap.c-m-b-date-session')
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-checkbox id="reexam" name="reexam" value="reexam" />
            <x-input-label for="reexam" class="inline mb-1 mx-2" :value="__('Re-Exam')" />
            <x-input-error :messages="$errors->get('reexam')" class="mt-2" />
          </div>
          <x-form-btn wire:loading.attr="disabled">Download</x-form-btn>
        </form>
      </div>
    </div>
  </div>
@endsection
