<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NextOfKin extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'next_of_kin';

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
        'next_of_kin_name',
        'next_of_kin_mobile_num',
        'next_of_kin_surname',
        'next_of_kin_nat_id',
        'next_of_kin_gender',
        'relationship',
        'next_of_kin_date_of_birth',
        'next_of_kin_address',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                  => 'integer',
        'user_id'                             => 'integer',
        'next_of_kin_name'                              => 'string',
        'next_of_kin_mobile_num'                              => 'string',
        'next_of_kin_surname'                              => 'string',
        'relationship'                                     => 'string',
        'next_of_kin_gender'                              => 'string',
        'next_of_kin_date_of_birth'                        => 'date',
        'next_of_kin_nat_id'                              => 'string',
        'next_of_kin_address'                             => 'string',


    ];


    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

}
