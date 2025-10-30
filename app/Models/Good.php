<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Good extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'category_id',
        'tgl_masuk',
        'nama',
        'type',
        'expired_date',
        'stok',
        'harga_asli',
        'harga',
        'markup_percentage', 
        'min_qty_grosir',
        'harga_grosir',
        'is_grosir_active',
        'is_tebus_murah_active',
        'min_total_tebus_murah',
        'harga_tebus_murah',
        'barcode',
        'use_existing_barcode',
        'existing_barcode'
    ];

    protected $casts = [
        'expired_date' => 'date',
        'tgl_masuk' => 'date',
        'is_grosir_active' => 'boolean',
        'is_tebus_murah_active' => 'boolean'
    ];


    protected $appends = ['harga_p_eats'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('barcode', 'like', '%' . $search . '%')
                        ->orWhereHas('category', function ($query) use ($search) {
                            $query->where('nama', 'like', '%' . $search . '%');
                        });
        });

        $query->when($filters['category'] ?? false, function ($query, $category) {
            return $query->whereHas('category', function ($query) use ($category) {
                $query->where('slug', $category);
            });
        });

        $query->when($filters['mitra'] ?? false, function ($query, $mitra) {
            return $query->whereHas('category', function ($query) use ($mitra) {
                $query->where('id', $mitra);
            });
        });
    }

    /**
     * Calculate selling price from original price based on type
     */
    public function calculateSellingPrice()
    {
        if (!$this->harga_asli) {
            return 0;
        }
        // Jika markup_percentage diset, gunakan itu; jika tidak, gunakan default berdasarkan type
        $markup = $this->markup_percentage !== null ? ($this->markup_percentage / 100) : ($this->type === 'makanan' ? 0.02 : 0.05);
        return $this->harga_asli + ($this->harga_asli * $markup);
    }

    /**
     * Accessor for harga_p_eats attribute.
     * Calculates price with P-Eats tax (12% additional)
     *
     * @return float
     */
    public function getHargaPEatsAttribute()
    {
        // Pastikan 'harga' adalah numerik sebelum perhitungan
        $basePrice = is_numeric($this->harga) ? $this->harga : 0;
        return $basePrice * 1.12;
    }

    /**
     * Generate barcode statically based on type and name.
     */
    public static function generateBarcodeStatic($type, $name)
    {
        $typeInitial = '';
        switch (strtolower($type)) {
            case 'makanan':
                $typeInitial = 'M';
                break;
            case 'non_makanan':
                $typeInitial = 'N';
                break;
            case 'handycraft':
                $typeInitial = 'H';
                break;
            case 'fashion':
                $typeInitial = 'F';
                break;
            default:
                $typeInitial = 'L'; 
                break;
        }

        // Sanitize and get first 3 chars of name
        $sanitizedName = preg_replace('/[^a-zA-Z0-9]/', '', $name); // Remove non-alphanumeric
        $namePart = strtoupper(substr($sanitizedName, 0, 3));
        if (empty($namePart)) {
            $namePart = 'XXX'; 
        }

        do {
            $randomNumber = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // 3-digit random number
            $barcode = $typeInitial . '-' . $namePart . '-' . $randomNumber;
        } while (self::where('barcode', $barcode)->exists());

        return $barcode;
    }

    /**
     * Find product by barcode
     */
    public static function findByBarcode($barcode)
    {
        return self::where('barcode', $barcode)->first();
    }

    /**
     * Generate barcode for this instance
     */
    public function generateBarcode()
    {
        if (!$this->barcode) {
            // Pass current instance's type and name to the static method
            $this->update(['barcode' => self::generateBarcodeStatic($this->type, $this->nama)]);
        }
        return $this->barcode;
    }

    /**
     * Check if product is expired
     */
    public function isExpired()
    {
        if (!$this->expired_date) {
            return false;
        }
        return Carbon::now()->gt($this->expired_date);
    }

    /**
     * Check if product will expire soon (within 7 days)
     */
    public function isExpiringSoon()
    {
        if (!$this->expired_date) {
            return false;
        }
        return Carbon::now()->diffInDays($this->expired_date, false) <= 7 && Carbon::now()->lte($this->expired_date);
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpiration()
    {
        if (!$this->expired_date) {
            return null;
        }
        return Carbon::now()->diffInDays($this->expired_date, false);
    }

    /**
     * Get expiration status
     */
    public function getExpirationStatus()
    {
        if (!$this->expired_date) {
            return 'no_expiry';
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->isExpiringSoon()) {
            return 'expiring_soon';
        }

        return 'fresh';
    }

    /**
     * Get type label
     */
    public function getTypeLabel()
    {
        $types = [
            'makanan' => 'Makanan',
            'non_makanan' => 'Non Makanan',
            'lainnya' => 'Lainnya',
            'handycraft' => 'Handycraft',
            'fashion' => 'Fashion'
        ];

        return $types[$this->type] ?? 'Lainnya';
    }

    /**
     * Get price based on quantity (wholesale or retail)
     */
    public function getPriceByQuantity($qty)
    {
        if ($this->is_grosir_active && $qty >= $this->min_qty_grosir && $this->harga_grosir) {
            return $this->harga_grosir;
        }
        return $this->harga;
    }

    /**
     * Check if quantity qualifies for wholesale price
     */
    public function isWholesaleQuantity($qty)
    {
        return $this->is_grosir_active && $qty >= $this->min_qty_grosir && $this->harga_grosir;
    }

    /**
     * Get wholesale info
     */
    public function getWholesaleInfo()
    {
        if (!$this->is_grosir_active) {
            return null;
        }

        return [
            'min_qty' => $this->min_qty_grosir,
            'price' => $this->harga_grosir,
            'savings' => $this->harga - $this->harga_grosir,
            'savings_percent' => round((($this->harga - $this->harga_grosir) / $this->harga) * 100, 1)
        ];
    }

    /**
     * Check if total qualifies for tebus murah price
     */
    public function isTebusMusahTotal($totalTransaction)
    {
        return $this->is_tebus_murah_active && $totalTransaction >= $this->min_total_tebus_murah && $this->harga_tebus_murah;
    }

    /**
     * Get tebus murah info
     */
    public function getTebusMusahInfo()
    {
        if (!$this->is_tebus_murah_active) {
            return null;
        }

        return [
            'min_total' => $this->min_total_tebus_murah,
            'price' => $this->harga_tebus_murah,
            'savings' => $this->harga - $this->harga_tebus_murah,
            'savings_percent' => round((($this->harga - $this->harga_tebus_murah) / $this->harga) * 100, 1)
        ];
    }
}
