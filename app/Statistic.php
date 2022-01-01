<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Statistic
 *
 * @property int $id
 * @property string $created_at
 * @property int $emails_received
 * @property int $inboxes_created
 * @property int $storage_used
 * @property int $cumulative_storage_used
 * @property int $emails_deleted
 * @method static \Database\Factories\StatisticFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereCumulativeStorageUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereEmailsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereEmailsReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereInboxesCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereStorageUsed($value)
 * @mixin \Eloquent
 */
class Statistic extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $fillable = [
        'created_at',
        'emails_received',
        'inboxes_created',
        'storage_used',
        'cumulative_storage_used',
        'emails_deleted',
    ];
    protected $casts = [
        'emails_received' => 'int',
        'inboxes_created' => 'int',
        'storage_used' => 'int',
        'cumulative_storage_used' => 'int',
        'emails_deleted' => 'int',
    ];
    protected $hidden = ['id'];

    public static function metaEmailsReceived(): int
    {
        return self::sum('emails_received');
    }

    public static function metaInboxesCreated(): int
    {
        return self::sum('inboxes_created');
    }

    public static function metaStorageUsed(): int
    {
        return disk_total_space(storage_path()) - disk_free_space(storage_path());
    }

    public static function metaEmailsDeleted(): int
    {
        return self::sum('emails_deleted');
    }

    public static function storageUsedBetween($date1, $date2): int
    {
        return self::whereBetween('created_at', [$date1, $date2])->sum('storage_used');
    }
}
