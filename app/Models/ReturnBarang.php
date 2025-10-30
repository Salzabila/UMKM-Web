<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnBarang extends Model
{
    use HasFactory;

    protected $table = 'returns';
    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->whereHas('good', function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                      ->orWhereHas('category', function ($query) use ($search) {
                          $query->where('nama', 'like', '%' . $search . '%');
                      });
            });
        });

        $query->when($filters['category_id'] ?? false, function ($query, $categoryId) {
            if ($categoryId !== 'all') {
                return $query->whereHas('good', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            }
        });
    }

    public function good()
    {
        return $this->belongsTo(Good::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
