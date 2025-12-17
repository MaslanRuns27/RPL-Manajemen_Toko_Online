@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Kategori</h4>
    <div class="mb-4">
        @foreach($categories as $category)
            <a href="/?category={{ $category->id }}" class="btn btn-outline-success btn-sm me-2 mb-2">{{ $category->name }}</a>
        @endforeach
    </div>

    <div class="row">
        @forelse($fruits as $fruit)
            <div class="col-md-3 mb-4">
                <div class="product-card p-2">
                    <img src="{{ asset('storage/' . $fruit->image) }}" class="product-img w-100" alt="{{ $fruit->name }}">
                    <div class="p-2">
                        <div class="product-name">{{ $fruit->name }}</div>
                        <div class="price">Rp {{ number_format($fruit->price, 0, ',', '.') }}</div>
                        <div class="text-muted mb-1" style="font-size: 13px;">
                            Stok: {{ $fruit->stock }} buah
                        </div>
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="fruit_id" value="{{ $fruit->id }}">

                            {{-- Input jumlah dengan batas maksimal sesuai stok --}}
                            <div class="input-group mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="decreaseQuantity({{ $fruit->id }})">−</button>
                                <input type="number"
                                    id="quantity_{{ $fruit->id }}"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="{{ $fruit->stock }}"
                                    class="form-control text-center"
                                    required
                                    @if($fruit->stock <= 0) disabled @endif>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="increaseQuantity({{ $fruit->id }})">+</button>
                            </div>

                            {{-- Tombol beli --}}
                            @if($fruit->stock > 0)
                                <button type="submit" class="btn btn-success btn-sm w-100">Tambah ke Keranjang</button>
                            @else
                                <button class="btn btn-secondary btn-sm w-100" disabled>Stok Habis</button>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
        @empty
            <p>Buah tidak ditemukan.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    function increaseQuantity(id) {
        const input = document.getElementById('quantity_' + id);
        if (input) {
            input.value = parseInt(input.value) + 1;
        }
    }

    function decreaseQuantity(id) {
        const input = document.getElementById('quantity_' + id);
        if (input && parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endpush
