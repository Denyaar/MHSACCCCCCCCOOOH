<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deductions extends Model
{
  protected $fillable = ['joining_fee','annual_sub','shares','monthly_saving'];
}
