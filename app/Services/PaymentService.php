<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Record a new payment.
     */
    public function recordPayment(array $data, ?int $verifierId = null): Payment
    {
        $invoice = Invoice::findOrFail($data['invoice_id']);

        if ($data['amount'] > $invoice->getRemainingAmount()) {
            throw new Exception('Jumlah pembayaran melebihi sisa tagihan.');
        }

        return DB::transaction(function () use ($data, $invoice, $verifierId) {
            $payment = Payment::create([
                'invoice_id' => $data['invoice_id'],
                'amount' => $data['amount'],
                'paid_at' => $data['paid_at'],
                'method' => $data['method'],
                'receipt_number' => $data['receipt_number'] ?? 'RCP-'.date('Ymd').'-'.mt_rand(1000, 9999),
                'status' => $verifierId ? 'verified' : 'pending',
                'verified_by' => $verifierId,
            ]);

            if ($payment->status === 'verified') {
                $invoice->increment('paid_amount', $payment->amount);
                $invoice->updateStatus();
            }

            return $payment;
        });
    }

    /**
     * Verify a payment.
     */
    public function verifyPayment(int $paymentId, int $verifierId): void
    {
        DB::transaction(function () use ($paymentId, $verifierId) {
            // Lock the payment row for update to prevent race conditions
            $payment = Payment::where('id', $paymentId)->lockForUpdate()->firstOrFail();

            if ($payment->status === 'verified') {
                throw new Exception('Pembayaran sudah diverifikasi sebelumnya.');
            }

            // Lock the invoice row to safely check balance and update
            $invoice = Invoice::where('id', $payment->invoice_id)->lockForUpdate()->firstOrFail();

            if ($payment->amount > $invoice->getRemainingAmount()) {
                throw new Exception('Gagal verifikasi: Pembayaran melebihi sisa tagihan saat ini.');
            }

            $payment->update([
                'status' => 'verified',
                'verified_by' => $verifierId,
            ]);

            $invoice->increment('paid_amount', $payment->amount);
            $invoice->updateStatus();
        });
    }
}
