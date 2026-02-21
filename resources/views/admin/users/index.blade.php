@extends('layouts.app')

@section('content')
<div class="bg-zinc-50 dark:bg-zinc-900 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <livewire:admin.users.index />
    </div>
</div>
@endsection
