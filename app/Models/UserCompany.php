<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCompany extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_company_id',
        'user_company_name',
        'corporate_number',
        'invoice_number',
        'address',
    ];

    /**
     * ユーザ一のリレーション(1:N)
     */
    public function users()
    {
        return $this->hasMany(User::class, 'user_company_id', 'user_company_id');
    }
}
