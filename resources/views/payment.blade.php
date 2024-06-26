<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment Page</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Razorpay Payment Page</h1>

        <form id="payment-form">
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" class="form-control" placeholder="Enter amount" required>
            </div>
            <button class="btn btn-primary" id="pay-button">Pay with Razorpay</button>
        </form>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $('#pay-button').click(function (e) {
           
            e.preventDefault();

            var amount = $('#amount').val() * 100; // amount in paise
            // alert(amount);
            $.ajax({
                url: '/api/create-order',
                method: 'POST',
                data: JSON.stringify({ amount: amount }),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
                },
                success: function (response) {
                    var options = {
                        "key": "{{ env('RAZORPAY_KEY') }}",
                        "amount": response.amount,
                        "currency": response.currency,
                        "order_id": response.orderId,
                        "handler": function (response){
                            $.ajax({
                                url: '/api/verify-payment',
                                method: 'POST',
                                data: JSON.stringify({
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_signature: response.razorpay_signature
                                }),
                                contentType: 'application/json',
                                success: function (response) {
                                    alert('Payment verified successfully');
                                },
                                error: function (response) {
                                    alert('Payment verification failed');
                                }
                            });
                        },
                        "prefill": {
                            "name": "Test User",
                            "email": "test.user@example.com",
                            "contact": "9999999999"
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                },
                error: function (response) {
                    alert('Order creation failed');
                }
            });
        });
    </script>
</body>
</html>
