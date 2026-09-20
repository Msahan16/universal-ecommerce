<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subject',
        'requirements',
        'attachment',
        'status',
        'estimated_days',
        'valid_until',
        'subtotal',
        'tax',
        'total',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'estimated_days' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'reviewed' => 'info',
            'quoted' => 'primary',
            'accepted' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
