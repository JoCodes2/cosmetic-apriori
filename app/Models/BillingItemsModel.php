<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingItemsModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'billings_items';
    protected $fillable = [
        'id',
        'id_product',
        'id_billing',
        'name_product',
        'price_product',
        'qty',
        'total_price',
        'created_at',
        'updated_at'
    ];
    public function billing(): BelongsTo
    {
        return $this->belongsTo(BillingsModel::class, 'id_billing', 'id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'id_product', 'id');
    }
}
