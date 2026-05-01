@props(['folders', 'currentFolder', 'paramName' => 'group', 'currentParams' => []])

<div class="border-b border-gray-200 mb-6">
    <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
        @foreach($folders as $folder)
            @php
                $name = $folder; 
                $params = $currentParams;
                $params[$paramName] = $name;
                if(isset($params['division'])) unset($params['division']);
                if(isset($params['department'])) unset($params['department']);

                $isActive = ($currentFolder == $name);
            @endphp
            <a href="{{ route(Route::currentRouteName(), $params) }}"
               class="{{ $isActive ? 
               'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                      whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ strtoupper($name) }}
            </a>
        @endforeach
    </nav>
</div>


