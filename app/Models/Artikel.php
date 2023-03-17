<?php

namespace App\Models;

use App\Http\Traits\ConfigId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use ConfigId;

    public const ENABLE = 1;
    public const HEADLINE = 1;
    public const NOT_IN_ARTIKEL = [999, 1000, 1001];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artikel';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'author',
        'category',
        'comments',
    ];

    /** {@inheritdoc} */
    protected $casts = [
        'tgl_upload' => 'datetime',
    ];

    /**
     * Scope a query to only include article.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlyArticle($query)
    {
        return $query->whereNotIn('id_kategori', static::NOT_IN_ARTIKEL);
    }

    /**
     * Scope a query to only enable article.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeEnable($query)
    {
        return $query->where('enabled', static::ENABLE);
    }

    /**
     * Scope a query to only headline article.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeHeadline($query)
    {
        return $query->where('headline', static::HEADLINE);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Komentar::class, 'id_artikel');
    }

    public function getPerkiraanMembacaAttribute()
    {
        return Str::perkiraanMembaca($this->isi);
    }

    /**
     * Getter untuk menambahkan url gambar.
     *
     * @return string
     */
    public function getUrlGambarAttribute()
    {
        return $this->gambar
            ? config('filesystems.disks.ftp.url') . "/desa/upload/artikel/sedang_{$this->gambar}"
            : '';
    }

    /**
     * Getter untuk menambahkan url gambar.
     *
     * @return string
     */
    public function getUrlGambar1Attribute()
    {
        return $this->gambar1
            ? config('filesystems.disks.ftp.url') . "/desa/upload/artikel/sedang_{$this->gambar1}"
            : '';
    }

    /**
     * Getter untuk menambahkan url gambar.
     *
     * @return string
     */
    public function getUrlGambar2Attribute()
    {
        return $this->gambar2
            ? config('filesystems.disks.ftp.url') . "/desa/upload/artikel/sedang_{$this->gambar2}"
            : '';
    }

    /**
     * Getter untuk menambahkan url gambar.
     *
     * @return string
     */
    public function getUrlGambar3Attribute()
    {
        return $this->gambar3
            ? config('filesystems.disks.ftp.url') . "/desa/upload/artikel/sedang_{$this->gambar3}"
            : '';
    }

    /**
     * Getter untuk menambahkan url share artikel.
     *
     * @return string
     */
    public function getUrlArtikelAttribute()
    {
        return "/artikel/{$this->tgl_upload->format('Y/m/d')}/{$this->slug}";
    }
}
