<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_name',
        'client_code',
        'company_type',
        'industry',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'website',
        'contact_person',
        'contact_position',
        'contact_phone',
        'contact_email',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_postal_code',
        'tax_id',
        'registration_number',
        'payment_terms',
        'credit_limit',
        'preferred_currency',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'is_active' => true,
        'country' => 'United States',
        'preferred_currency' => 'USD',
        'payment_terms' => 'Net 30',
    ];

    // Relationships
    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'client_id');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->client_code ? "{$this->client_name} ({$this->client_code})" : $this->client_name;
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ', ' . $this->city;
        if ($this->state) $address .= ', ' . $this->state;
        if ($this->postal_code) $address .= ' ' . $this->postal_code;
        if ($this->country) $address .= ', ' . $this->country;
        
        return $address;
    }

    public function getFullBillingAddressAttribute()
    {
        if (!$this->billing_address) return $this->full_address;
        
        $address = $this->billing_address;
        if ($this->billing_city) $address .= ', ' . $this->billing_city;
        if ($this->billing_state) $address .= ', ' . $this->billing_state;
        if ($this->billing_postal_code) $address .= ' ' . $this->billing_postal_code;
        if ($this->billing_country) $address .= ', ' . $this->billing_country;
        
        return $address;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('client_name', 'like', "%{$search}%")
              ->orWhere('client_code', 'like', "%{$search}%")
              ->orWhere('contact_person', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Methods
    public function generateClientCode()
    {
        if ($this->client_code) return $this->client_code;
        
        // Generate code from client name
        $words = explode(' ', $this->client_name);
        $code = '';
        
        if (count($words) >= 2) {
            // Use first letter of first two words
            $code = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            // Use first 2 letters of single word
            $code = strtoupper(substr($words[0], 0, 2));
        }
        
        // Add number if code exists
        $baseCode = $code;
        $counter = 1;
        
        while (self::where('client_code', $code)->where('id', '!=', $this->id ?? 0)->exists()) {
            $code = $baseCode . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $counter++;
        }
        
        return $code;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['display_name'] = $this->display_name;
        $array['full_address'] = $this->full_address;
        $array['full_billing_address'] = $this->full_billing_address;
        
        return $array;
    }
}
