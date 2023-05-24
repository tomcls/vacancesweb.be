<?php

namespace App\Http\Livewire\Admin\House;

use App\Models\House;
use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\HouseHighlight;
use App\Models\InvoiceTransaction;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Data\Enums\InvoicePaymentStatusEnum;
use App\Data\Enums\InvoiceTransactionTypeEnum;
use App\Traits\DataTable\WithPerPagePagination;

class HouseHighlights extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public HouseHighlight $editing;

    public $houseId;

    protected $queryString = ['sorts'];

    public $filters = [
        'id' => null,
        'invoice_id' => null,
        'startdate-min' => null,
        'startdate-max' => null,
        'enddate-min' => null,
        'enddate-max' => null,
        'created-min' => null,
        'created-max' => null,
    ];

    public $rules = [
        "editing.house_id" => 'required',
        "editing.startdate_for_editing" => 'required', 
        "editing.enddate_for_editing" => 'required', 
    ];

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $houseHighlights = [];

    public $selectedType = null;

    public $createInvoice = false;

    public $invoiceId = null;

    public function mount($houseId)
    {
        $this->houseId = $houseId;
        $this->editing = $this->makeBlankPublication();
        $this->houseHighlights = HouseHighlight::whereHouseId($this->houseId)->orderBy('id','desc')->limit(30)->get();
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message' => 'You\'ve deleted ' . $deleteCount . ' seasons', 'type' => 'success']);
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankPublication();

        $this->showEditModal = true;
    }

    public function edit(HouseHighlight $houseHighlight)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($houseHighlight)){
            $this->invoiceId = InvoiceTransaction::whereReference($houseHighlight->id)->whereType('house_highlight')->first()->invoice_id??null;
            $this->editing = $houseHighlight;
        } 
        $this->showEditModal = true;
    }

    public function save()
    {
        // if no invoice_id then create an invoice else just create a new transaction
        $this->editing->house_id = $this->houseId;
        $this->validate();
        $this->editing->save();

        if ($this->createInvoice) {
            $invoice = new Invoice([
                'user_id'=>House::whereId($this->houseId)->first()->user_id,
                'invoice_num'=>Str::random(20),
                'payment_status'=>InvoicePaymentStatusEnum::Success,
                'date_payed'=> now(),
            ]);
            $invoice->save();
            $transaction = new InvoiceTransaction([
                'invoice_id' => $invoice->id,
                'price' =>  12.5,
                'reference' => $this->editing->id,
                'type' =>  InvoiceTransactionTypeEnum::HouseHighlight
            ]);
            $transaction->save();
        } 

        $this->showEditModal = false;

        $this->notify(['message' => 'Publication  well saved', 'type' => 'success']);
    }

    public function makeBlankPublication()
    {
        return HouseHighlight::with('invoice')->make([]);
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count()) {
            return response()->streamDownload(function () {
                echo $this->selectedRowsQuery->toCsv();
            }, 'highlights.csv');
        }
    }

    public function getRowsQueryProperty()
    {
        $query = HouseHighlight::query()
            ->select('id', 'house_id', 'startdate', 'enddate','created_at')
            ->selectSub(function ($q) {
                $q->from('invoice_transactions')
                    ->select('invoice_id')
                    ->whereColumn('reference', 'house_highlights.id')
                    ->where('type', 'house_highlight')
                    ->limit(1);
            }, 'invoice_id')
            ->when($this->filters['startdate-min'], fn ($query, $date) => $query->where('startdate', '>=', Carbon::parse($date)))
            ->when($this->filters['startdate-max'], fn ($query, $date) => $query->where('startdate', '<=', Carbon::parse($date)))
            ->when($this->filters['enddate-min'], fn ($query, $date) => $query->where('enddate', '>=', Carbon::parse($date)))
            ->when($this->filters['enddate-max'], fn ($query, $date) => $query->where('enddate', '<=', Carbon::parse($date)))
            ->when($this->filters['created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($this->filters['invoice_id'], fn ($query, $id) => 
                $query->having('invoice_id', '=', $id))
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id))
            ->whereHouseId($this->houseId);

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        // return $this->cache(function () {
        //     return $this->applyPagination($this->rowsQuery);
        // });
    }
    public function render()
    {
        return view('livewire.admin.house.house-highlights', [
            'highlights' => $this->rows,
        ]);
    }
}
