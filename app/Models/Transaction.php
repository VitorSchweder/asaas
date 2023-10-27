<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'customer_id',
        'value',
        'status'
    ];

    const TYPE_CREDIT_CARD = 'CREDIT_CARD';
    const TYPE_BOLETO = 'BOLETO';
    const TYPE_PIX = 'PIX';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
