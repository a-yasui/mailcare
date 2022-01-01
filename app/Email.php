<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Email
 *
 * @property string $id
 * @property string $sender_id
 * @property string $inbox_id
 * @property string $subject
 * @property \Illuminate\Support\Carbon|null $read
 * @property bool $favorite
 * @property bool $has_html
 * @property bool $has_text
 * @property int|null $size_in_bytes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Attachment[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Inbox $inbox
 * @property-read \App\Sender $sender
 * @method static \Database\Factories\EmailFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Email filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Email newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Email newQuery()
 * @method static \Illuminate\Database\Query\Builder|Email onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Email query()
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereFavorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereHasHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereHasText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereInboxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereSizeInBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Email withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Email withoutTrashed()
 * @mixin \Eloquent
 */
class Email extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'favorite' => 'boolean',
        'has_html' => 'boolean',
        'has_text' => 'boolean',
        'size_in_bytes' => 'integer',
        'read' => 'datetime',
    ];

    use Uuids;

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($email) {
            if ($email->isForceDeleting()) {
                Storage::delete($email->path());
                $email->attachments()->delete();
            }
        });
    }

    public function sender()
    {
        return $this->belongsTo('App\Sender');
    }

    public function inbox()
    {
        return $this->belongsTo('App\Inbox');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    public function path()
    {
        return 'emails/' . $this->created_at->format('Y/m/d/') . $this->id;
    }

    public function fullPath()
    {
        return storage_path('app/'.$this->path());
    }

    public function isUnread()
    {
        return empty($this->read);
    }

    public function read()
    {
        $this->read = Carbon::now();
        $this->save();
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
