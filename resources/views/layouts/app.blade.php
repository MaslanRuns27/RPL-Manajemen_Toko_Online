<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buahpedia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f6f7f8; }
        .navbar-brand { font-weight: bold; color: #03ac0e !important; }
        .product-card { border-radius: 10px; overflow: hidden; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .product-img { height: 200px; object-fit: cover; }
        .product-name { font-weight: 600; }
        .price { color: #d0011b; font-weight: bold; }
        .quantity-control {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .quantity-control input[type="number"] {
        width: 60px;
        text-align: center;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 4px 6px;
        font-size: 14px;
        background-color: #fff;
    }

    .quantity-control button {
        width: 30px;
        height: 30px;
        padding: 0;
        font-size: 18px;
        line-height: 1;
        border-radius: 6px;
    }

    .quantity-control button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Buahpedia</a>

            <form class="d-flex ms-auto me-3" method="GET" action="{{ url('/') }}">
                <input class="form-control me-2" type="search" name="search" placeholder="Cari buah...">
                <button class="btn btn-success" type="submit">Cari</button>
            </form>

            <a href="{{ route('cart.index') }}" class="btn btn-outline-success">
                🛒 Keranjang
            </a>

            @auth
            <a href="{{ route('transaction.history') }}" class="btn btn-outline-primary">
                📜 Riwayat
            </a>
            @endauth
        </div>
    </nav>

    {{-- Content --}}
    <main class="py-4">
        @yield('content')
    </main>

    {{-- Footer (optional) --}}
    <footer class="text-center py-4 text-muted">
        &copy; {{ date('Y') }} Buahpedia. All rights reserved.
    </footer>
    
@stack('scripts')

</body>
</html>
