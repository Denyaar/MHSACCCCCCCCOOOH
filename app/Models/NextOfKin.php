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
        'name',
        'mobile_num',
        'surname',
        'nat_id',
        'gender',
        'date_of_birth',
        'relationship',
        'address',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                  => 'integer',
        'user_id'                             => 'integer',
        'name'                              => 'string',
        'mobile_num'                              => 'string',
        'surname'                              => 'string',
        'date_of_birth'                        => 'date',
        'gender'                              => 'string',
        'relationship'                           => 'string',
        'nat_id'                              => 'string',
        'address'                             => 'string',


    ];


    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

}
