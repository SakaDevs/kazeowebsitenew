@foreach($scripts as $script)
    <a href="{{ route('script.show', $script->slug) }}" class="h-full block bg-white rounded-xl sm:rounded-2xl border border-zinc-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group flex flex-col hover:-translate-y-1 select-none">
        
        <div class="w-full h-32 sm:h-40 overflow-hidden bg-zinc-100 relative shrink-0">
            @if($script->image)
                <img src="{{ Storage::url($script->image) }}" alt="{{ $script->title }}" draggable="false" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out select-none">
            @else
                <img src="https://via.placeholder.com/600x400" alt="No Image" draggable="false" class="w-full h-full object-cover select-none">
            @endif
        </div>

        <div class="p-4 sm:p-5 text-center flex flex-col flex-grow">
            <p class="text-[9px] sm:text-[10px] font-bold text-red-700 uppercase tracking-wider mb-1 sm:mb-1.5 line-clamp-1">
                {{ $script->category->name ?? 'Uncategorized' }}
            </p>
            
            <h3 class="text-sm sm:text-lg font-extrabold text-zinc-900 line-clamp-2 sm:line-clamp-1 group-hover:text-red-700 transition-colors" title="{{ $script->title }}">
                {{ $script->title }}
            </h3>
            
            <div class="w-8 sm:w-12 h-1 bg-yellow-400 mx-auto mt-2 sm:mt-3 mb-auto rounded-full"></div>

            <div class="flex flex-col sm:flex-row items-center sm:items-end justify-between text-[10px] sm:text-xs mt-4 sm:mt-6 gap-2 sm:gap-0">
                <div class="flex items-center gap-1.5 text-zinc-700 font-bold hover:text-red-700 transition-colors">
                    @php
                        $defaultAvatar = 'https://ui-avatars.com/api/?name='.urlencode($script->user->name ?? 'User').'&background=f4f4f5&color=18181b';
                        $userAvatar = ($script->user && $script->user->avatar) ? asset('storage/' . $script->user->avatar) : $defaultAvatar;
                    @endphp
                    <img src="{{ $userAvatar }}" class="w-5 h-5 rounded-full object-cover border border-zinc-200 shrink-0">
                    <span class="line-clamp-1">{{ $script->user->name ?? 'Unknown' }}</span>
                </div>

                <div class="text-center sm:text-right text-zinc-400 font-medium space-y-0.5">
                    <p>{{ $script->updated_at->format('d M Y') }}</p>
                    <p class="font-bold">{{ number_format($script->views) }}x dilihat</p>
                </div>
            </div>
        </div>
    </a>
@endforeach