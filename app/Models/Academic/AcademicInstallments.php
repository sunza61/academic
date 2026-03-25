<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicInstallments extends Model
{
    use HasFactory;
    protected $table = "academic_installments";
    protected $fillable = [
        'academic_project_id',
        'installment_no',
        'duration_days',
        'start_date',
        'end_date',
        'amount',
        'adv_deduct_pct',
        'adv_deduct_amt',
        'guarantee_pct',
        'guarantee_amt',
        'fine_amount',
        'net_amount',
        'delivery_date',
        'payment_date',
        'status',
    ];
}
