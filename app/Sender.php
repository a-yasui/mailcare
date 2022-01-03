<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * App\Sender
 *
 * @property string $id
 * @property string $display_name
 * @property string $email
 * @property string|null $local_part
 * @property string|null $domain
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SenderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Sender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sender query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sender whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sender whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sender whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sender whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sender whereLocalPart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sender whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sender extends Model
{
    use Uuids;
    use HasFactory;
    
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['email', 'display_name'];
    protected $hidden = ['local_part', 'domain'];
}
