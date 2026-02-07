<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPaymentMethod extends Model
{
    protected $table = "user_payment_methods";

    protected $fillable = [
        "is_default",
        "pm_last_four",
        "brand",
        "pm_type",
        "stripe_payment_method_id",
        "user_id"
    ];

    public function paymentMethods()
    {
        return $this->hasMany(UserPaymentMethod::class);
    }
}
