@php
    $currentTheme = $designTheme ?? 'pro';
    $currentAnimation = $designAnimation ?? 'reveal';
@endphp

<div x-data="themeSwitcher()" x-init="init()" class="fixed bottom-6 right-6 z-50">
    {{-- Toggle Button --}}
    <button
        @click="open = !open"
        class="w-14 h-14 rounded-full bg-stone-900 text-white shadow-xl hover:shadow-2xl hover:scale-110 transition-all duration-300 flex items-center justify-center group"
        title="Change Design Theme"
    >
        <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </button>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        @click.away="open = false"
        class="absolute bottom-16 right-0 w-80 bg-white rounded-2xl shadow-2xl border border-stone-100 overflow-hidden mb-4"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="px-5 py-4 border-b border-stone-100 bg-stone-50/50">
            <h3 class="text-sm font-semibold text-stone-900">Design Theme</h3>
            <p class="text-xs text-stone-400 mt-0.5">Choose a visual style for the store</p>
        </div>

        {{-- Theme Options --}}
        <div class="p-4 space-y-2">
            @php
            $themes = [
                'pro' => ['name' => 'Pro', 'icon' => '✦', 'desc' => 'Elegant, refined, premium feel', 'color' => 'bg-stone-900'],
                'modern' => ['name' => 'Modern', 'icon' => '◈', 'desc' => 'Bold, geometric, vibrant energy', 'color' => 'bg-indigo-600'],
                'ancient' => ['name' => 'Ancient', 'icon' => '✧', 'desc' => 'Warm, aged, heritage-inspired', 'color' => 'bg-amber-700'],
                'minimal' => ['name' => 'Minimal', 'icon' => '◻', 'desc' => 'Clean, whitespace, monochrome', 'color' => 'bg-black'],
                'luxury' => ['name' => 'Luxury', 'icon' => '◆', 'desc' => 'Rich, opulent, gold-accented', 'color' => 'bg-gradient-to-r from-yellow-600 to-amber-500'],
            ];
            @endphp

            @foreach($themes as $key => $theme)
            <button
                @click="setTheme('{{ $key }}')"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all duration-200 {{ $currentTheme === $key ? 'bg-stone-100 ring-2 ring-stone-900' : 'hover:bg-stone-50' }}"
            >
                <div class="w-8 h-8 {{ $theme['color'] }} rounded-lg flex items-center justify-center text-white text-sm flex-shrink-0">
                    {{ $theme['icon'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-stone-800">{{ $theme['name'] }}</p>
                    <p class="text-[11px] text-stone-400">{{ $theme['desc'] }}</p>
                </div>
                @if($currentTheme === $key)
                <svg class="w-4 h-4 text-stone-900 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                @endif
            </button>
            @endforeach
        </div>

        {{-- Animation Section --}}
        <div class="px-4 pb-4">
            <div class="border-t border-stone-100 pt-4">
                <p class="text-[11px] font-semibold text-stone-900 uppercase tracking-wider mb-3">Animation Style</p>
                <div class="grid grid-cols-5 gap-1.5">
                    @php
                    $animations = [
                        'reveal' => ['name' => 'Reveal', 'icon' => '↑'],
                        'slide' => ['name' => 'Slide', 'icon' => '→'],
                        'fade' => ['name' => 'Fade', 'icon' => '○'],
                        'parallax' => ['name' => 'Depth', 'icon' => '◎'],
                        'typewriter' => ['name' => 'Type', 'icon' => '▌'],
                    ];
                    @endphp
                    @foreach($animations as $key => $anim)
                    <button
                        @click="setAnimation('{{ $key }}')"
                        class="flex flex-col items-center gap-1 py-2 px-1 rounded-lg text-center transition-all {{ $currentAnimation === $key ? 'bg-stone-900 text-white' : 'bg-stone-50 text-stone-600 hover:bg-stone-100' }}"
                        title="{{ $anim['name'] }}"
                    >
                        <span class="text-sm">{{ $anim['icon'] }}</span>
                        <span class="text-[9px] font-medium">{{ $anim['name'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function themeSwitcher() {
    return {
        open: false,
        theme: '{{ $currentTheme }}',
        animation: '{{ $currentAnimation }}',

        init() {
            this.applyTheme();
            this.applyAnimation();
        },

        setTheme(theme) {
            this.theme = theme;
            document.body.setAttribute('data-theme', theme);
            document.cookie = `nora_theme=${theme};path=/;max-age=${60*24*365}`;
            this.saveToServer(theme, this.animation);
        },

        setAnimation(animation) {
            this.animation = animation;
            document.body.setAttribute('data-animation', animation);
            document.cookie = `nora_animation=${animation};path=/;max-age=${60*24*365}`;
            this.saveToServer(this.theme, animation);
        },

        applyTheme() {
            document.body.setAttribute('data-theme', this.theme);
        },

        applyAnimation() {
            document.body.setAttribute('data-animation', this.animation);
            this.initScrollAnimations();
        },

        saveToServer(theme, animation) {
            fetch('{{ route("theme.set") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ theme, animation }),
            });
        },

        initScrollAnimations() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('anim-visible');
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.anim-item').forEach((el) => {
                observer.observe(el);
            });
        }
    };
}
</script>
@endpush
