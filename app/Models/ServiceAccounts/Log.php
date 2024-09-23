<?php

namespace App\Models\ServiceAccounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $service_account_id
 * @property string $description
 * @property string|null $model_type
 * @property int|null $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereServiceAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Log extends Model
{
    use HasFactory;

    protected $table = 'service_account_logs';

    protected $fillable = ['description', 'model_id', 'model_type'];
}
