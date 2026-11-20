<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    protected $fillable = [
        'id',
        'employee_no',
        'is_dept_head',
        'is_lab_sup',
        'is_hr_head',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'department_name',
        'department_id',
        'date_hired',
        'regularization_date',
        'probationary_date',
        'dept_head',
        'branch_id',
        'branch_name',
        'position_id',
        'position_name',
        'status',
        'email',
        'picture',
        'created_at',
        'updated_at'
    ];

    protected function casts(): array
    {
        return [
            'is_dept_head' => 'boolean',
            'is_lab_sup' => 'boolean',
            'is_hr_head' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function branch()
    {
        return $this->belongsTo(branch::class, 'branch_id', 'id');
    }
}
