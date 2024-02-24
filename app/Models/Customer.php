<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    const ID = 'id';
    const NAME = 'name';
    const BEFORE_AMOUNT = 'before_amount';
    const AMOUNT = 'amount';

    protected $fillable = [
        self::ID,
        self::NAME,
        self::BEFORE_AMOUNT,
        self::AMOUNT
    ];
}
