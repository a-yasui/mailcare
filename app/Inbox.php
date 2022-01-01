<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Inbox
 *
 * @property string $id
 * @property string $display_name
 * @property string $email
 * @property string|null $local_part
 * @property string|null $domain
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Email[] $emails
 * @property-read int|null $emails_count
 * @method static \Database\Factories\InboxFactory factory( ...$parameters )
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox query()
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox whereDisplayName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox whereDomain( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox whereEmail( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox whereLocalPart( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Inbox whereUpdatedAt( $value )
 * @mixin \Eloquent
 */
class Inbox extends Model
{
    use HasFactory;
    
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['email', 'display_name'];
    protected $hidden = ['local_part', 'domain'];
    
    public function emails()
    {
        return $this->hasMany('App\Email');
    }
}
