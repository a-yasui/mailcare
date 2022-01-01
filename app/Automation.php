<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Automation
 *
 * @property string $id
 * @property string $title
 * @property string|null $sender
 * @property string|null $inbox
 * @property string|null $subject
 * @property bool $has_attachments
 * @property string|null $action_url
 * @property string|null $action_email
 * @property string|null $action_secret_token
 * @property int $emails_received
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $action_delete_email
 * @property bool $in_error
 * @property bool $post_raw
 * @method static \Database\Factories\AutomationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Automation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Automation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereActionDeleteEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereActionEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereActionSecretToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereActionUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereEmailsReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereHasAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereInError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereInbox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation wherePostRaw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Automation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Automation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'title',
        'sender',
        'inbox',
        'subject',
        'has_attachments',
        'action_url',
        'action_email',
        'action_secret_token',
        'action_delete_email',
        'post_raw',
        'emails_received',
    ];
    protected $casts = [
        'emails_received' => 'int',
        'has_attachments' => 'boolean',
        'action_delete_email' => 'boolean',
        'post_raw' => 'boolean',
        'in_error' => 'boolean',
    ];
    public $incrementing = false;
    protected $keyType = 'string';
}
