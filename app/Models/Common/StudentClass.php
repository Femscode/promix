<?php

namespace App\Models\Common;

use App\Traits\Media;
use App\Abstracts\Model;
use App\Traits\Contacts;
use App\Traits\Currencies;
use App\Traits\Documents;
use App\Traits\Transactions;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentClass extends Model
{
    use Cloneable, Contacts, Currencies, Documents, HasFactory, Media, Notifiable, Transactions;

    // public const CUSTOMER_TYPE = 'class';
    // public const VENDOR_TYPE = 'class';
    // public const EMPLOYEE_TYPE = 'class';

    protected $table = 'student_classes';
    // protected $guarded = [];


    protected $fillable = [
        'company_id',
        'type',
        'name',
        'enabled',
        'created_from',
        'created_by',
    ];

   }
