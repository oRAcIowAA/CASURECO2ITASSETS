@props(['title', 'folders', 'paramName', 'currentParams' => []])

<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $title }}</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($folders as $folder)
            @php
                // If folder is an object (like Branch model) use name/id, else use string
                $name = is_object($folder) ? ($folder->branch_name ?? $folder->name ?? $folder) : $folder;
                $value = is_object($folder) ? ($folder->id ?? $name) : $name; 
                
                // Merge current params with new param
                $params = array_merge($currentParams, [$paramName => $value]);
            @endphp
            <a href="{{ route(Route::currentRouteName(), $params) }}" 
               class="flex flex-col items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-400 transition-all group">
                <svg class="w-10 h-10 text-yellow-500 mb-2 group-hover:text-yellow-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                </svg>
                <span class="text-xs font-medium text-gray-700 text-center group-hover:text-blue-600 truncate w-full">
                    {{ strtoupper($name) }}
                </span>
            </a>
        @endforeach
    </div>
</div>
