<?php

namespace App\Http\Livewire;

use App\Utils\Csv;
use Validator;
use Livewire\Component;
use App\Models\Holiday;
use Livewire\WithFileUploads;

class ImportHolidays extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $upload;
    public $columns;
    public $fieldColumnMap = [
        'title' => '',
        'amount' => '',
        'status' => '',
        'date_for_editing' => '',
    ];

    protected $rules = [
        'fieldColumnMap.title' => 'required',
        'fieldColumnMap.amount' => 'required',
    ];

    protected $customAttributes = [
        'fieldColumnMap.title' => 'title',
        'fieldColumnMap.amount' => 'amount',
    ];

    public function updatingUpload($value)
    {
        Validator::make(
            ['upload' => $value],
            ['upload' => 'required|mimes:txt,csv'],
        )->validate();
    }

    public function updatedUpload()
    {
        $this->columns = Csv::from($this->upload)->columns();

        $this->guessWhichColumnsMapToWhichFields();
    }

    public function import()
    {
        $this->validate();

        $importCount = 0;

        Csv::from($this->upload)
            ->eachRow(function ($row) use (&$importCount) {
                Holiday::create(
                    $this->extractFieldsFromRow($row)
                );
                $importCount++;
            });

        $this->reset();

        $this->emit('refreshHolidays');

        $this->notify(['message'=>'Imported '.$importCount.' holidays!','type'=>'success']);
    }

    public function extractFieldsFromRow($row)
    {
        $attributes = collect($this->fieldColumnMap)
            ->filter()
            ->mapWithKeys(function($heading, $field) use ($row) {
                return [$field => $row[$heading]];
            })
            ->toArray();
            return $attributes;
        //return $attributes + ['status' => 'success', 'date_for_editing' => now()];
    }

    public function guessWhichColumnsMapToWhichFields()
    {
        $guesses = [
            'firstname' => ['firstname', 'label'],
            'lastname' => ['lastname', 'label'],
            'email' => ['email', 'label'],
            'lang' => ['lang', 'label'],
            'phone' => ['phone', 'label'],
            'company_name' => ['company_name', 'label'],
            'company_vat' => ['company_vat', 'label'],
            'code' => ['code', 'label'],
            'active' => ['active', 'label'],
            'created_at' => ['created_at', 'label'],
        ];

        foreach ($this->columns as $column) {
            $match = collect($guesses)->search(fn($options) => in_array(strtolower($column), $options));

            if ($match) $this->fieldColumnMap[$match] = $column;
        }
    }
}
