<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Questions') }}
        </h2>
    </x-slot>

    <x-container>
        <x-form method="post" action="{{ route('question.store') }}">
            <x-textarea label="Question" name="question"></x-textarea>
            <x-btn.primary>Save</x-btn.primary>
            <x-btn.reset>Cancel</x-btn.reset>
        </x-form>

        <hr class="border-gray-700 border-dashed my-4">
        
        <div class="dark:text-gray-300 uppercase font-bold mb-1">Drafts</div>

        <div class="dark:text-gray-400 space-y-4">
            <x-table>
                <x-table.thead>
                        <tr>
                            <x-table.th>Question</x-table.th>
                            <x-table.th>Actions</x-table.th>
                        </tr>
                </x-table.thead>
                <tbody>
                    @foreach ($questions->where('draft', true) as $item)
                        <x-table.tr>
                            <x-table.td>{{ $item->question }}</x-table.td>
                            <x-table.td>
                                <x-methodsform :action="route('question.destroy', $item)" delete onsubmit="return confirm('Tem certeza?')">
                                    <button type="submit" class="hover:underline text-red-500 font-bold">Deletar</button>
                                </x-methodsform>

                                <x-methodsform :action="route('question.publish', $item)" put>
                                    <button type="submit" class="hover:underline text-green-500 font-bold">Publicar</button>
                                </x-methodsform>

                                <a href="{{ route('question.edit', $item) }}" class="hover:underline text-blue-500 font-bold">Editar</a>
                                
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </tbody>
            </x-table>
        </div>

        <hr class="border-gray-700 border-dashed my-4">
        
        <div class="dark:text-gray-300 uppercase font-bold mb-1">My Questions</div>

        <div class="dark:text-gray-400 space-y-4">
            <x-table>
                <x-table.thead>
                        <tr>
                            <x-table.th>Question</x-table.th>
                            <x-table.th>Actions</x-table.th>
                        </tr>
                </x-table.thead>
                <tbody>
                    @foreach ($questions->where('draft', false) as $item)
                        <x-table.tr>
                            <x-table.td>{{ $item->question }}</x-table.td>
                            <x-table.td>
                                <x-methodsform :action="route('question.destroy', $item)" delete onsubmit="return confirm('Tem certeza?')">
                                    <button type="submit" class="hover:underline text-red-500 font-bold">Deletar</button>
                                </x-methodsform>

                                <x-methodsform :action="route('question.archive', $item)" patch>
                                    <button type="submit" class="hover:underline text-yellow-500 font-bold">Arquivar</button>
                                </x-methodsform>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                    
                </tbody>
            </x-table>
        </div>

        <hr class="border-gray-700 border-dashed my-4">

        <div class="dark:text-gray-300 uppercase font-bold mb-1">Archived Questions</div>

        <div class="dark:text-gray-400 space-y-4">
            <x-table>
                <x-table.thead>
                        <tr>
                            <x-table.th>Question</x-table.th>
                            <x-table.th>Actions</x-table.th>
                        </tr>
                </x-table.thead>
                <tbody>
                    @foreach ($archivedQuestions as $item)
                        <x-table.tr>
                            <x-table.td>{{ $item->question }}</x-table.td>
                            <x-table.td>
                                <x-methodsform :action="route('question.restore', $item)" patch>
                                    <button type="submit" class="hover:underline text-green-500 font-bold">Restaurar</button>
                                </x-methodsform>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                    
                </tbody>
            </x-table>
        </div>

    </x-container>
</x-app-layout>
