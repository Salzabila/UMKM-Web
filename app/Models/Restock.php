<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restock extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['good', 'user'];

    public function good()
    {
        return $this->belongsTo(Good::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStokSebelumAttribute()
    {
        // Calculate stock before this restock by subtracting the restock quantity
        return $this->good->stok - $this->qty_restock;
    }

    public function getStokSesudahAttribute()
    {
        return $this->good->stok;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->whereHas('good', function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%');
            })->orWhereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }
}
