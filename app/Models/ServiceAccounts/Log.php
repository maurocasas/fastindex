<?php

namespace App\Models\ServiceAccounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'service_account_logs';

    protected $fillable = ['description', 'model_id', 'model_type'];
}
