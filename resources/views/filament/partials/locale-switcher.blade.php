@php
    $currentLocale = app()->getLocale();
@endphp
<div class="fi-locale-switcher flex items-center gap-x-1 rounded-lg bg-gray-100 p-0.5 dark:bg-white/5">
    <a
        href="{{ request()->fullUrlWithQuery(['locale' => 'ar']) }}"
        class="rounded-md px-2 py-1 text-xs font-bold transition {{ $currentLocale === 'ar' ? 'bg-white text-primary-600 shadow dark:bg-gray-700 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
    >
        عربي
    </a>
    <a
        href="{{ request()->fullUrlWithQuery(['locale' => 'en']) }}"
        class="rounded-md px-2 py-1 text-xs font-bold transition {{ $currentLocale === 'en' ? 'bg-white text-primary-600 shadow dark:bg-gray-700 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
    >
        EN
    </a>
</div>
