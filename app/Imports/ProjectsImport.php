<?php

namespace App\Imports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProjectsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Project([
            'name' => $row['name'],
            'contract_date' => $this->transformDate($row['contract_date']),
            'phone' => $row['phone'] ?? null,
            'location' => $row['location'] ?? null,
            'quotation_number' => $row['quotation_number'] ?? null,
            'delivery_date' => $this->transformDate($row['delivery_date']),
            'installation_date' => $this->transformDate($row['installation_date']),
            'type_of_work' => $row['type_of_work'] ?? null,
            'value' => $this->transformValue($row['value']),
            'status' => strtolower($row['status'] ?? 'pending'),
            'notes' => $row['notes'] ?? null,
        ]);
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'contract_date' => 'nullable',
            'phone' => 'nullable',
            'location' => 'nullable',
            'quotation_number' => 'nullable',
            'delivery_date' => 'nullable',
            'installation_date' => 'nullable',
            'type_of_work' => 'nullable',
            'value' => 'nullable',
            'status' => 'nullable',
            'notes' => 'nullable',
        ];
    }

    private function transformDate($value)
    {
        if (!$value) return null;
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::parse($value);
        }
    }

    private function transformValue($value)
    {
        if (empty($value)) return null;
        
        // Remove any currency symbols and thousands separators
        $value = preg_replace('/[^0-9.-]/', '', $value);
        
        // Convert to float and round to 2 decimal places
        return round((float) $value, 2);
    }
} 