<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
