<x-layouts::app.sidebar :title="$title ?? null">
    <div class="h-20 bg-[#FAFAFA] w-full p-5 hidden border-b border-[#DEDEDE] lg:block">
        <select name="" id="" class="p-2 border h-10 rounded-md w-40">
            <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">2026/2027</option>
            <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">2024/2025</option>
            <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">2023/2024</option>
        </select>
    </div>

    <flux:main>




        {{ $slot }}

    </flux:main>
</x-layouts::app.sidebar>
