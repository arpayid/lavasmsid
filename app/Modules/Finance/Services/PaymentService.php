<?php

namespace App\Modules\Finance\Services;

use App\Modules\Finance\Models\{Invoice, Payment};
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function generateReceiptNumber(): string
    {
        $prefix = 'REC-' . date('Ym') . '-';
        $lastPayment = Payment::where('receipt_number', 'like', $prefix . '%')
            ->orderBy('receipt_number', 'desc')
            ->first();

        if (!$lastPayment) {
            return $prefix . '0001';
        }

        $sequence = (int) substr($lastPayment->receipt_number, -4);
        return $prefix . str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);
    }

    public function recordPayment(array $data, ?int $verifierId = null): Payment
    {
        return DB::transaction(function () use ($data, $verifierId) {
            $invoice = Invoice::findOrFail($data['invoice_id']);

            // Create payment record
            $payment = new Payment([
                'invoice_id' => $invoice->id,
                'verified_by' => $verifierId,
                'receipt_number' => $this->generateReceiptNumber(),
                'amount' => $data['amount'],
                'paid_at' => $data['paid_at'] ?? now()->toDateString(),
                'method' => $data['method'] ?? 'cash',
                'status' => $verifierId ? Payment::STATUS_VERIFIED : Payment::STATUS_PENDING,
            ]);
            $payment->save();

            // If verified immediately, update invoice
            if ($payment->status === Payment::STATUS_VERIFIED) {
                $this->updateInvoiceAfterPayment($invoice);
            }

            return $payment;
        });
    }

    public function verifyPayment(int $paymentId, int $verifierId): Payment
    {
        return DB::transaction(function () use ($paymentId, $verifierId) {
            $payment = Payment::findOrFail($paymentId);

            if ($payment->status === Payment::STATUS_VERIFIED) {
                return $payment;
            }

            $payment->status = Payment::STATUS_VERIFIED;
            $payment->verified_by = $verifierId;
            $payment->save();

            $this->updateInvoiceAfterPayment($payment->invoice);

            return $payment;
        });
    }

    protected function updateInvoiceAfterPayment(Invoice $invoice): void
    {
        // Recalculate total paid amount from verified payments only
        $totalPaid = $invoice->payments()
            ->where('status', Payment::STATUS_VERIFIED)
            ->sum('amount');

        $invoice->paid_amount = $totalPaid;
        $invoice->updateStatus();
    }
}
