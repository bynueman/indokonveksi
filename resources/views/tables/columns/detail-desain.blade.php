@php
    $detailPesanan = $getRecord()->detailPesanan;
@endphp

@if ($detailPesanan->count())
    <div class="flex flex-wrap gap-2">
        @foreach ($detailPesanan as $detail)
            @if ($detail->file_desain)
                <a href="{{ Storage::url($detail->file_desain) }}" target="_blank">
                    <img
                        src="{{ Storage::url($detail->file_desain) }}"
                        alt="Desain"
                        class="w-10 h-10 object-cover rounded border"
                    />
                </a>
            @endif
        @endforeach
    </div>
@else
    <span class="text-gray-400 text-sm">Tidak ada desain</span>
@endif
