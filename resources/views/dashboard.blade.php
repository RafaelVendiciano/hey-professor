<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vote for a Question') }}
        </h2>
    </x-slot>

    <x-container>

        <form action="{{ route('dashboard') }}" method="get" class="flex items-center spacex-2">
            @csrf
            <x-text-input type="text" name="search" value="{{ request()->search }}" class="w-full"/>
            <x-btn.primary type="submit">Search</x-btn.primary>
        </form>

        {{-- Listagem --}}
        <div class="dark:text-gray-300 uppercase font-bold mb-3 mt-3">list of questions:</div>

        <div class="dark:text-gray-400 space-y-4">
            @foreach ($questions as $item)
                <x-question :item="$item"></x-question>
            @endforeach

            {{ $questions->withQueryString()->links() }}
        </div>
    </x-container>
</x-app-layout>
