@props([
    'item'
])

<div class="rounded dark:bg-gray-800/50 shadow shadow-blue-500/50 p-3 dark:text-gray-400 flex 
            justify-between items-center">
    <span>{{ $item->question }}</span>
    <div>
        <form method="post" action= "{{ route('question.like', $item) }}">
            @csrf
            <button class="flex items-stars space-x-2 text-green-500" type="submit"> 
                <x-icons.thumbs-up class="w-5 h-5 hover:text-yellow-300 cursor-pointer" id="thumbs-up"/>
                <span>{{ $item->votes_sum_like ?: 0 }}</span>
            </button>
        </form>

        <form method="post" action= "{{ route('question.dislike', $item) }}">
            @csrf
            <button class="flex items-stars space-x-2 text-red-500" type="submit">
                <x-icons.thumbs-down class="w-5 h-5 hover:text-yellow-300 cursor-pointer" id="thumbs-down"/>
                <span>{{ $item->votes_sum_dislike ?: 0 }}</span>
            </button>  
        </form>
    </div>
</div>