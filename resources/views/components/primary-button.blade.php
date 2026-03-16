<button {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-[#000000] border-4 border-black rounded-none font-semibold text-xs text-[#FF0000] uppercase tracking-widest hover:bg-[#4d0909] focus:bg-[#4d0909] active:bg-[#200404] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>