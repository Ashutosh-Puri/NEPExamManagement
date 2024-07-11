@extends('layouts.user')
@section('user')
  <div>
    <x-breadcrumb.breadcrumb>
      <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
      <x-breadcrumb.link name="Seal Bag Report's" />
    </x-breadcrumb.breadcrumb>
    <x-card-header heading="Seal Bag Report" />
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Seal Bag Report
      </div>
      <div class="grid grid-cols-1 md:grid-cols-1">
        <form method="post" action="{{ route('user.seal_bag_report_create') }}">
          @csrf
          @livewire('user.cap.c-m-b-date-session')
          <x-form-btn wire:loading.attr="disabled" name="dnpdf" value="pdf">Download PDF</x-form-btn>
          <x-form-btn wire:loading.attr="disabled" name="dnpdf" value="excel">Download Excel</x-form-btn>
        </form>
      </div>
    </div>
  </div>
@endsection
