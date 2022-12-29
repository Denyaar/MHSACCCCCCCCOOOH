<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetails extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_details';

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
        'user_id',
        'mobile',
        'tittle',
        'date_of_birth',
        'gender',
        'status', //Active or Not
        'nat_id',
        'address',
        'source_of_income',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                  => 'integer',
        'user_id'                             => 'integer',
        'mobile'                              => 'string',
        'tittle'                              => 'string',
        'date_of_birth'                        => 'date',
        'gender'                              => 'string',
        'status'                              => 'boolean',
        'nat_id'                              => 'string',
        'address'                             => 'string',
        'source_of_income'                    => 'string',

    ];


    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

}
