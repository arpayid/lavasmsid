<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    const STATUS_UNPAID = 'unpaid';

    const STATUS_PARTIAL = 'partial';

    const STATUS_PAID = 'paid';

    protected $fillable = [
        'student_id',
        'payment_category_id',
        'invoice_number',
        'amount',
        'paid_amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = 'INV-'.date('Ymd').'-'.mt_rand(1000, 9999);
            }
            if (empty($invoice->status)) {
                $invoice->status = self::STATUS_UNPAID;
            }
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function paymentCategory(): BelongsTo
    {
        return $this->belongsTo(PaymentCategory::class);
    }

    public function updateStatus()
    {
        if ($this->paid_amount <= 0) {
            $this->status = self::STATUS_UNPAID;
        } elseif ($this->paid_amount < $this->amount) {
            $this->status = self::STATUS_PARTIAL;
        } else {
            $this->status = self::STATUS_PAID;
        }
        $this->save();
    }

    public function getRemainingAmount(): float
    {
        return (float) ($this->amount - $this->paid_amount);
    }
}
