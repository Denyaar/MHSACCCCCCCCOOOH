<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//
//class Requirements extends Model
//{
//
//    use HasFactory;
//    /**
//     * The database table used by the model.
//     *
//     * @var string
//     */
//    protected $table = 'requirements';
//
//    /**
//     * Indicates if the model should be timestamped.
//     *
//     * @var bool
//     */
//    public $timestamps = true;
//
//    /**
//     * The attributes that are not mass assignable.
//     *
//     * @var array
//     */
//    protected $guarded = [
//        'id',
//    ];
//
//    /**
//     * The attributes that should be mutated to dates.
//     *
//     * @var array
//     */
//    protected $dates = [
//        'created_at',
//        'updated_at',
//        'deleted_at',
//    ];
//
//    /**
//     * The attributes that are mass assignable.
//     *
//     * @var array
//     */
//    protected $fillable = [
//        'user_id',
//        'payslip',
//        //'copy_of_nat_id',
//        'bank_statement',
//
//    ];
//
//    /**
//     * The attributes that should be cast to native types.
//     *
//     * @var array
//     */
//    protected $casts = [
//        'id'                                    => 'integer',
//        'user_id'                                    => 'integer',
//        //'payslip'                                    => 'string',
//      //  'copy_of_nat_id'                                    => 'string',
//        'bank_statement'                                    => 'string',
//    ];
//
//    public  function Users(){
//        return $this->belongsTo(User::class,'user_id');
//    }
//
//}
