<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(User::getExportMembers());
        //return User::all(['id', 'user_id', 'plan_id', 'name', 'email', 'phone_no']);
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Name',
            'Email',
            'Phone No',
            'Plan',
            'Plan Expiry Date'
        ];
    }
}
