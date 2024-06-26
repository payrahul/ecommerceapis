<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        $key = env('RAZORPAY_KEY');
        $secret = env('RAZORPAY_SECRET');

        // Log the API credentials being used
        Log::info('Using Razorpay Key: ' . $key);
        
        $api = new Api($key, $secret);

        try {
            $order = $api->order->create([
                'receipt' => 'order_rcptid_11',
                'amount' => $request->amount * 100, // amount in the smallest currency unit
                'currency' => 'INR',
                'payment_capture' => 1 // auto capture
            ]);

            return response()->json([
                'orderId' => $order['id'],
                'amount' => $order['amount'],
                'currency' => $order['currency']
            ]);
        } catch (\Razorpay\Api\Errors\BadRequestError $e) {
            Log::error('Razorpay BadRequestError: ' . $e->getMessage());
            return response()->json(['error' => 'Authentication failed. Please check your Razorpay API keys.'], 500);
        } catch (\Exception $e) {
            Log::error('General error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the order. Please try again.'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $signatureStatus = $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ]);

        if ($signatureStatus) {
            return response()->json(['status' => 'Payment verified successfully']);
        } else {
            return response()->json(['error' => 'Payment verification failed'], 400);
        }
    }
}
