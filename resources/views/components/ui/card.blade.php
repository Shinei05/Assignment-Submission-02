@props(['title' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'bg-surface border border-border shadow-sm rounded-xl overflow-hidden flex flex-col']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-border shrink-0">
            <h3 class="text-lg font-bold text-text-main">{{ $title }}</h3>
        </div>
    @endif

    <div class="p-6 flex-1 flex flex-col">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 bg-background border-t border-border mt-auto shrink-0">
            {{ $footer }}
        </div>
    @endif
</div>
