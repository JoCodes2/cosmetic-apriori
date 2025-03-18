<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomersModel extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'customers';
    protected $fillable = [
        'id',
        'name',
        'telepon',
        'created_at',
        'updated_at'
    ];

    public function billings(): HasMany
    {
        return $this->hasMany(BillingsModel::class, 'id_customer', 'id');
    }
}
