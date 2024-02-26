<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Customer",
 *     title="Customer",
 *     description="Customer model",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="amount", type="number", format="float"),
 *     @OA\Property(property="before_amount", type="number", format="float", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
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
