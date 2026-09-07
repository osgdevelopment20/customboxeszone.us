<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleIndexRequest extends Model
{
    use HasFactory;

    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED  = 'failed';
    public const STATUS_SKIPPED = 'skipped';

    protected $fillable = [
        'url',
        'url_hash',
        'type',
        'source',
        'status',
        'http_code',
        'batch_id',
        'notify_time',
        'message',
        'response',
        'submitted_at',
    ];

    protected $casts = [
        'notify_time'  => 'datetime',
        'submitted_at' => 'datetime',
    ];

    /**
     * Hash used to look a URL up without indexing the whole (long) string.
     */
    public static function hash(string $url): string
    {
        return sha1($url);
    }

    public function setUrlAttribute($value): void
    {
        $this->attributes['url'] = $value;
        $this->attributes['url_hash'] = static::hash((string) $value);
    }

    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Requests that actually consumed quota (skipped rows never hit Google).
     */
    public function scopeBillable(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_SUCCESS, self::STATUS_FAILED]);
    }

    public function scopeForUrl(Builder $query, string $url): Builder
    {
        return $query->where('url_hash', static::hash($url));
    }

    public function getStatusBadgeAttribute(): string
    {
        return [
            self::STATUS_SUCCESS => 'success',
            self::STATUS_FAILED  => 'danger',
            self::STATUS_SKIPPED => 'secondary',
        ][$this->status] ?? 'warning';
    }

    public function getDecodedResponseAttribute(): array
    {
        $decoded = json_decode((string) $this->response, true);

        return is_array($decoded) ? $decoded : [];
    }
}
