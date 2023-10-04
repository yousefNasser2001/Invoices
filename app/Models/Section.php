<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Section extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['section_name', 'description', 'created_by'];

    protected static $logAttributes = [
        'section_name' => 'اسم القسم',
        'description' => 'الوصف',
        'created_by' => 'تم الإنشاء بواسطة',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['section_name', 'description', 'created_by'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                switch ($eventName) {
                    case 'created':
                        return 'انشاء قسم جديد';
                    case 'updated':
                        return 'تحديث بيانات قسم';
                    case 'deleted':
                        return 'حذف قسم';
                    default:
                        return "This model has been {$eventName}";
                }
            })
            ->useLogName('Section');

    }

    public function products()
    {
        return $this->hasMany(Product::class, 'section_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'section_id');
    }
}
