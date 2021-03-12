@if (Session::has('message'))
<div x-data="{ show: true }" x-show="show"
    class="relative flex flex-col sm:flex-row sm:items-center bg-blue-100 shadow rounded-md py-5 pl-6 pr-8 sm:pr-6 md:w-full">
    <div class="flex flex-row items-center border-b sm:border-b-0 w-full sm:w-auto pb-4 sm:pb-0">
        <div class="text-green-500">
            <svg class="w-6 sm:w-5 h-6 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="text-sm font-medium ml-3">Success Payment.</div>
    </div>
    <div class="text-sm tracking-wide text-gray-500 mt-4 sm:mt-0 sm:ml-4">{{ Str::title(Session::get('message')) ?? ''}}
    </div>
    <div
        class="absolute sm:relative sm:top-auto sm:right-auto ml-auto right-4 top-4 text-gray-400 hover:text-gray-800 cursor-pointer">
        <svg @click="show = false" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </div>
</div>
@endif