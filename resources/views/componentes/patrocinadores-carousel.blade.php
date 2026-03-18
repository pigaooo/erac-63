@php
    use Illuminate\Support\Str;
@endphp

@if(($patrocinadores ?? collect())->count())
    {{-- Desktop --}}
    <section class="hidden md:block">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Patrocinadores</h2>
        </div>
        <div id="patrocinadores-carousel" class="carousel rounded-box w-full bg-base-100/80 border border-base-300 shadow-sm scroll-smooth">
            @foreach($patrocinadores as $patrocinador)
                @php
                    $logoUrl = $patrocinador->getFirstMediaUrl('logo', 'thumb') ?: $patrocinador->getFirstMediaUrl('logo');
                @endphp
                <div class="carousel-item w-1/2 lg:w-1/3 p-3">
                    <a
                        href="{{ $patrocinador->endereco ? (Str::startsWith($patrocinador->endereco, ['http://', 'https://']) ? $patrocinador->endereco : 'https://' . $patrocinador->endereco) : '#' }}"
                        class="flex h-72 w-full items-center justify-center rounded-2xl border border-base-200 bg-white p-4"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <img id="logo-patrocinador" src="{{ $logoUrl ?: asset('images/patrocinio-teste.jpg') }}" alt="Logo {{ $patrocinador->name }}" class="h-full w-full object-contain">
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Mobile --}}
    <section class="md:hidden">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Patrocinadores</h2>
        </div>
        <div id="patrocinadores-carousel-mobile" class="carousel rounded-box w-full bg-base-100/80 border border-base-300 shadow-sm scroll-smooth">
            @foreach($patrocinadores as $patrocinador)
                @php
                    $logoUrl = $patrocinador->getFirstMediaUrl('logo', 'thumb') ?: $patrocinador->getFirstMediaUrl('logo');
                @endphp
                <div class="carousel-item w-full p-3">
                    <a
                        href="{{ $patrocinador->endereco ? (Str::startsWith($patrocinador->endereco, ['http://', 'https://']) ? $patrocinador->endereco : 'https://' . $patrocinador->endereco) : '#' }}"
                        class="flex h-56 w-full items-center justify-center rounded-2xl border border-base-200 bg-white p-4"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <img src="{{ $logoUrl ?: asset('images/patrocinio-teste.jpg') }}" alt="Logo {{ $patrocinador->name }}" class="h-full w-full object-contain">
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endif
