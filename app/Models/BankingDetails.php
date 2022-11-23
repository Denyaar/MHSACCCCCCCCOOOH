<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankingDetails extends Model
{

    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banking_details';

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
        'bank',
        'user_id',
        'bank_branch',
        'branch_code',
        'acc_name',
        'acc_number',
        'acc_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                    => 'integer',
        'user_id'                                    => 'integer',
        'bank'                                   => 'string',
        'bank_branch'                           => 'string',
        'branch_code'                           => 'string',
        'acc_name'                             => 'string',
        'acc_number'                          => 'string',
        'acc_type'                            => 'string',
    ];

    public  function Users(){
        return $this->belongsTo(User::class,'user_id');
    }

}
