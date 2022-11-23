<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loans';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loan_name',
        'type',
        'loan_purpose',
        'amount',
        'status',
        'applied_date',
        'approved_date',
        'monthly_installments',
        'repayment_period',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                  => 'integer',
        'loan_name'                           => 'string',
        'loan_purpose'                        => 'string',
        'type'                               => 'string',
        'amount'                             => 'string',
        'status'                             => 'string',
        'applied_date'                       => 'datetime',
        'approved_date'                      => 'datetime',
        'monthly_installments'               => 'string',
        'repayment_period'                   => 'string',

    ];

    public function Users()
    {
        return $this->belongsToMany(User::class, 'user_loan');
    }


}
