@props(['images', 'cols'])

<div class="grid grid-cols-{{ $cols }} gap-4">
    @foreach($images as $index => $image)
        <div>
            <img src="{{ $image }}" alt="Gallery image {{ $index }}"
                    {{ $attributes->merge(['class' => 'w-full h-auto']) }}
            >
        </div>
    @endforeach
</div>