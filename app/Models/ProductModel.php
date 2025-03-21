<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'tb_product';
    protected $fillable = [
        'id',
        'name',
        'price',
        'image',
        'created_at',
        'updated_at',
    ];
    public function billingItems(): HasMany
    {
        return $this->hasMany(BillingItemsModel::class, 'id_product', 'id');
    }
}
