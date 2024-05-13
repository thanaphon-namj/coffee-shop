<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'Employee';

    protected $primaryKey = 'EmployeeID';

    protected $fillable = [
        'FirstName',
        'LastName',
        'BirthDate',
        'PhoneNo',
        'Email',
        'Position',
        'Salary',
        'StartDate',
        'EndDate',
    ];

    public $timestamps = false;
}
