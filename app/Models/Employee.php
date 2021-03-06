<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Employee
 *
 * @package App\Models
 * @mixin Builder
 */
class Employee extends Model
{
    use SoftDeletes;

    // Default results per page for index
    public const RESULTS_PER_PAGE = 25;

    // Payment Methods
    public const MAIL = 'Mail';
    public const DIRECT_DEPOSIT = 'Direct Deposit';

    // Payment Classifications
    public const HOURLY = 'Hourly';
    public const SALARIED = 'Salaried';
    public const COMMISSIONED = 'Commissioned';

    // Gender options
    public const MALE = 'Male';
    public const FEMALE = 'Female';
    public const OTHER = 'Other';

    /** @var string  */
    protected $connection = 'mysql';

    /** @var string  */
    protected $table = 'employees';

    /** @var string[]  */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_initial',
        'email',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'classification',
        'payment_method',
        'salary',
        'hourly_rate',
        'commission_rate',
        'routing_number',
        'account_number',
        'phone',
        'gender',
        'birth_date',
    ];

    /** @var string[] */
    protected $dates = [
        'birth_date',
    ];

    /**
     * Returns array of payment methods
     * @return string[]
     */
    public static function getPaymentMethods()
    {
        return [
            static::MAIL,
            static::DIRECT_DEPOSIT,
        ];
    }

    /**
     * Returns array of payment classifications
     * @return string[]
     */
    public static function getClassifications()
    {
        return [
            static::HOURLY,
            static::SALARIED,
            static::COMMISSIONED,
        ];
    }

    /**
     * Returns array of gender options
     * @return string[]
     */
    public static function getGenders() : array
    {
        return [
            static::MALE,
            static::FEMALE,
            static::OTHER,
        ];
    }

    /**
     * @return HasOne
     */
    public function user() : HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * @return HasMany
     */
    public function notes() : HasMany
    {
        return $this->hasMany(EmployeeNote::class);
    }

    /**
     * Get Employee's first and last name
     * @return string
     */
    public function getFullNameAttribute() : string
    {
        if ($this->middle_initial) return $this->first_name . ' ' . $this->middle_initial . '. ' . $this->last_name;
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get Employee's full address details
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        return "$this->address" . ($this->address2 ? ' #' . $this->address2 : '') . ", $this->city $this->state, $this->zip";
    }

    /**
     * @param Builder $query
     * @param string|null $name
     * @return Builder
     */
    public function scopeName(Builder $query, $name) : Builder
    {
        if (is_null($name)) return $query;

        $names = explode(' ', $name);
        return $query->whereIn('first_name', $names)
            ->orWhereIn('last_name', $names);
    }

    /**
     * @param Builder $query
     * @param string|null $email
     * @return Builder
     */
    public function scopeEmail(Builder $query, $email) : Builder
    {
        if (is_null($email)) return $query;
        return $query->where('email', 'LIKE', '%' . $email . '%');
    }

    /**
     * @param Builder $query
     * @param string|null $classification
     * @return Builder
     */
    public function scopeClassification(Builder $query, $classification) : Builder
    {
        if (is_null($classification)) return $query;
        return $query->where('classification', $classification);
    }

    /**
     * @param Builder $query
     * @param string|null $paymentMethod
     * @return Builder
     */
    public function scopePaymentMethod(Builder $query, $paymentMethod) : Builder
    {
        if (is_null($paymentMethod)) return $query;
        return $query->where('payment_method', $paymentMethod);
    }
}
