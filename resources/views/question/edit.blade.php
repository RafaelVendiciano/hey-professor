<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Question') }} :: {{ $question->id }}
        </h2>
    </x-slot>

    <x-container>
        <x-methodsform :action="route('question.update', $question)" put>
            <x-textarea label="Question" name="question" :value="$question->question"></x-textarea>
            <x-btn.primary>Save</x-btn.primary>
            <x-btn.reset>Cancel</x-btn.reset>
        </x-methodsform>
    </x-container>
</x-app-layout>
