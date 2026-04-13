@php
    use Illuminate\Support\Str;

    $logoUrl = $patrocinador->getFirstMediaUrl('logo');
    $href = null;

    if ($patrocinador->endereco) {
        $href = Str::startsWith($patrocinador->endereco, ['http://', 'https://'])
            ? $patrocinador->endereco
            : 'https://' . $patrocinador->endereco;
    }

    $baseClasses = 'sponsor-card group flex w-full items-center justify-center overflow-hidden rounded-[1.75rem] border border-base-300 bg-white p-4 shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl';
    $sizeClasses = match ($size ?? 'bronze') {
        'diamante' => 'h-72 md:h-80 lg:h-96',
        'ouro' => 'h-56 md:h-64 lg:h-72',
        'prata' => 'h-48 md:h-56 lg:h-60',
        default => 'h-40 md:h-44 lg:h-48',
    };
@endphp

@if($href)
    <a
        href="{{ $href }}"
        class="{{ $baseClasses }} {{ $sizeClasses }}"
        target="_blank"
        rel="noopener noreferrer"
    >
        <img
            src="{{ $logoUrl ?: asset('images/patrocinio-teste.jpg') }}"
            alt="Logo {{ $patrocinador->name }}"
            class="h-full w-full object-contain transition duration-500 group-hover:scale-[1.03]"
        >
    </a>
@else
    <div class="{{ $baseClasses }} {{ $sizeClasses }}">
        <img
            src="{{ $logoUrl ?: asset('images/patrocinio-teste.jpg') }}"
            alt="Logo {{ $patrocinador->name }}"
            class="h-full w-full object-contain transition duration-500 group-hover:scale-[1.03]"
        >
    </div>
@endif
