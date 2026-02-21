@layouts('components.layouts.app')

@section('content')
<div class="bg-zinc-50 dark:bg-zinc-950 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
</div>
@endsection
