<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentDetails extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'employment_details';

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
        'employer',
        'employer_address',
        'employer_phone',
        'position_at_work',
        'grade',
        'approved_status',
        'department',
        'date_of_employment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                    => 'integer',
        'user_id'                               => 'integer',
        'employer'                              => 'string',
        'employer_address'                      => 'string',
        'employer_phone'                        => 'string',
        'position_at_work'                       => 'string',
        'grade'                                 => 'string',
        'approved_status'                        => 'boolean',
        'department'                            => 'string',
        'date_of_employment'                    => 'date',
    ];


}
