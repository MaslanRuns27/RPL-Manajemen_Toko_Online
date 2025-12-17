@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h3>Silakan Lanjutkan Pembayaran</h3>
    <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                sendResult(result);
            },
            onPending: function(result) {
                sendResult(result);
            },
            onError: function(result) {
                alert("Pembayaran gagal.");
            }
        });
    };

    function sendResult(result) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('checkout.finish') }}";

        form.innerHTML = `
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $trx->id }}">
            <input type="hidden" name="payment_result" value='${JSON.stringify(result)}'>
        `;
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection
