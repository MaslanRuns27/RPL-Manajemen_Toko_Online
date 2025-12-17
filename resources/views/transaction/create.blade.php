<h1>Beli {{ $fruit->name }}</h1>
<p>Harga: Rp {{ number_format($fruit->price, 0, ',', '.') }}</p>

<form action="{{ route('transaction.store') }}" method="POST">
    @csrf
    <input type="hidden" name="fruit_id" value="{{ $fruit->id }}">
    
    <label for="quantity">Jumlah:</label>
    <input type="number" name="quantity" id="quantity" min="1" value="1">
    
    <button type="submit">Beli Sekarang</button>
</form>
