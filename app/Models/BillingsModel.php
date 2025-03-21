<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingsModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'billings';
    protected $fillable = [
        'id',
        'id_customer',
        'total_payment',
        'payment_date',
        'code_transaction',
        'status_transaction',
        'created_at',
        'updated_at'
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomersModel::class, 'id_customer', 'id');
    }
    public function billingItems(): HasMany
    {
        return $this->hasMany(BillingItemsModel::class, 'id_billing', 'id');
    }
}
