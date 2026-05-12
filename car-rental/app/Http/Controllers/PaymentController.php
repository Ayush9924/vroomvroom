<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    /**
     * Create a Razorpay order for a pending booking.
     * POST /payment/create-order/{booking}
     */
    public function createOrder(Request $request, $bookingId)
    {
        try {
            // Find booking and verify ownership
            $booking = Booking::with('car')->findOrFail($bookingId);

            if ($booking->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to booking.',
                ], 403);
            }

            // Idempotency: if already paid, reject
            if ($booking->status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking has already been paid.',
                ], 422);
            }

            // Only allow payment for pending bookings
            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending bookings can be paid.',
                ], 422);
            }

            // Initialize Razorpay API — keys always from config, never from client
            $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

            // Amount comes from DATABASE, never from request (security)
            $amountInPaise = (int) round($booking->total_price * 100);

            $order = $api->order->create([
                'receipt'         => 'booking_' . $booking->id,
                'amount'          => $amountInPaise,
                'currency'        => config('razorpay.currency'),
                'payment_capture' => 1,
            ]);

            // Store order ID server-side before checkout opens
            $booking->update(['razorpay_order_id' => $order->id]);

            $user = auth()->user();

            return response()->json([
                'order_id'     => $order->id,
                'amount'       => $amountInPaise,
                'currency'     => config('razorpay.currency'),
                'key_id'       => config('razorpay.key_id'),
                'booking_id'   => $booking->id,
                'user_name'    => $user->name,
                'user_email'   => $user->email,
                'company_name' => config('razorpay.company_name'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify Razorpay payment signature and mark booking as paid.
     * POST /payment/verify
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'booking_id'          => 'required|integer|exists:bookings,id',
        ]);

        try {
            // Fetch booking and verify ownership again
            $booking = Booking::findOrFail($request->booking_id);

            if ($booking->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 403);
            }

            // Idempotency check — don't process an already-paid booking
            if ($booking->status === 'paid') {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Booking already marked as paid.',
                    'redirect' => route('dashboard'),
                ]);
            }

            // Verify the razorpay_order_id matches what we stored server-side
            if ($booking->razorpay_order_id !== $request->razorpay_order_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order ID mismatch. Payment verification failed.',
                ], 422);
            }

            $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

            // Server-side signature verification — the critical security step
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            // Signature valid — update booking to paid
            $booking->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
                'status'              => 'paid',
                'paid_at'             => now(),
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Payment successful! Your booking is confirmed.',
                'redirect' => route('payment.success', $booking->id),
            ]);

        } catch (SignatureVerificationError $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed. Signature mismatch.',
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during payment verification: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show payment success confirmation page.
     * GET /payment/success/{booking}
     */
    public function success($bookingId)
    {
        $booking = Booking::with('car')->findOrFail($bookingId);

        // Only the booking owner can see this page
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only show success page for paid bookings
        if ($booking->status !== 'paid') {
            return redirect()->route('dashboard')->with('error', 'Booking is not yet paid.');
        }

        return view('payment.success', compact('booking'));
    }
}
