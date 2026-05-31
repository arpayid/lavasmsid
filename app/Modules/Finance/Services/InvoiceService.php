<?php

namespace App\Modules\Finance\Services;

use App\Modules\Finance\Models\{Invoice, PaymentCategory};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceService
{
    public function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Ym') . '-';
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if (!$lastInvoice) {
            return $prefix . '0001';
        }

        $sequence = (int) substr($lastInvoice->invoice_number, -4);
        return $prefix . str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);
    }

    public function createInvoice(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $data['invoice_number'] = $this->generateInvoiceNumber();
            $data['status'] = Invoice::STATUS_UNPAID;
            $data['paid_amount'] = 0;

            return Invoice::create($data);
        });
    }

    public function bulkCreateInvoices(array $studentIds, array $data): int
    {
        return DB::transaction(function () use ($studentIds, $data) {
            $count = 0;
            foreach ($studentIds as $studentId) {
                $invoiceData = $data;
                $invoiceData['student_id'] = $studentId;
                $this->createInvoice($invoiceData);
                $count++;
            }
            return $count;
        });
    }
}
