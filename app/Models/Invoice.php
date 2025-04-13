<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'seller_nip',
        'buyer_nip',
        'product_name',
        'net_amount',
        'issuance_date',
    ];
}
