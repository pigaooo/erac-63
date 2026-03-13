@php
    use Illuminate\Support\Str;
@endphp

@if(($patrocinadores ?? collect())->count())
    {{-- Desktop --}}
    <section class="hidden md:block">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Patrocinadores</h2>
        </div>
        <div id="patrocinadores-carousel" class="relative overflow-hidden rounded-2xl bg-base-100/80 border border-base-300">
            <div id="patrocinadores-track" class="flex transition-transform duration-700 ease-in-out">
                @foreach($patrocinadores as $patrocinador)
                    <a
                        href="{{ $patrocinador->endereco ? (Str::startsWith($patrocinador->endereco, ['http://', 'https://']) ? $patrocinador->endereco : 'https://' . $patrocinador->endereco) : '#' }}"
                        class="w-full shrink-0 flex items-center justify-center h-72"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <img id="logo-patrocinador" src="{{ asset('images/patrocinio-teste.jpg') }}" alt="Logo {{ $patrocinador->name }}" class="h-full w-full object-cover">
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Mobile --}}
    <section class="md:hidden">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Patrocinadores</h2>
        </div>
        <div id="patrocinadores-carousel-mobile" class="relative overflow-hidden rounded-2xl bg-base-100/80 border border-base-300">
            <div id="patrocinadores-track-mobile" class="flex transition-transform duration-700 ease-in-out">
                @foreach($patrocinadores as $patrocinador)
                    <a
                        href="{{ $patrocinador->endereco ? (Str::startsWith($patrocinador->endereco, ['http://', 'https://']) ? $patrocinador->endereco : 'https://' . $patrocinador->endereco) : '#' }}"
                        class="w-full shrink-0 flex items-center justify-center h-56"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <img src="{{ asset('images/patrocinio-teste.jpg') }}" alt="Logo {{ $patrocinador->name }}" class="h-full w-full object-contain">
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
