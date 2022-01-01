<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use App\Traits\StorageForHuman;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Attachment
 *
 * @property string $id
 * @property string $email_id
 * @property string $headers_hashed
 * @property string $file_name
 * @property string $content_type
 * @property int $size_in_bytes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Email $email
 * @property-read mixed $size_for_human
 * @method static \Database\Factories\AttachmentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereHeadersHashed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereSizeInBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Attachment extends Model
{
    use Uuids;
    use StorageForHuman;
    use HasFactory;
    
    public $incrementing = false;
    protected $keyType = 'string';
    protected $appends = ['size_for_human'];

    public function hashHeaders($headers)
    {
        return md5(json_encode($headers));
    }

    public function email()
    {
        return $this->belongsTo(Email::class);
    }

    public function getSizeForHumanAttribute()
    {
        return $this->humanFileSize($this->size_in_bytes);
    }
}
