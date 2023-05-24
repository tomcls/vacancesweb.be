<?php

namespace App\Http\Livewire\Admin\Holiday;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Invoice;
use Illuminate\Support\Str;
use App\Models\InvoiceTransaction;
use Illuminate\Support\Facades\DB;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Data\Enums\InvoiceTransactionTypeEnum;
use App\Traits\DataTable\WithPerPagePagination;

class HolidayTransactions extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public InvoiceTransaction $editing;
    public Invoice $invoice;

    public $holidayId;

    protected $queryString = ['sorts'];

    public $filters = [
        'id' => null,
        'price' => null,
        'invoice_id' => null,
        'user_id' => null,
        'reference' => null,
        'datepayed-min' => null,
        'datepayed-max' => null,
        'created-min' => null,
        'created-max' => null,
        'type' => null,
        'payment_status' => null,
    ];

    public $rules = [
        "invoice.date_payed_for_editing" => 'sometimes',
        "invoice.user_id" => 'required',
        "invoice.payment_status" => 'required',
        "editing.invoice_id" => 'sometimes',
        "editing.reference" => 'required', 
        "editing.price" => 'required',
        "editing.type" => 'required',
    ];

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $holidayPurchase = [];

    public $selectedType = null;

    public $users = null;
    public $userSearch = null;

    protected $listeners = [
        'selectAutoCompleteItem' => 'setAutoCompleteItem',
    ];

    public function mount($holidayId)
    {
        $this->holidayId = $holidayId;
        $this->invoice = $this->makeBlankInvoice();
        $this->editing = $this->makeBlankTransaction();
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message' => 'You\'ve deleted ' . $deleteCount . ' transaction(s)', 'type' => 'success']);
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankTransaction();

        $this->showEditModal = true;
    }

    public function edit(InvoiceTransaction $invoiceTransaction)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($invoiceTransaction)){
            
            $this->editing = $invoiceTransaction;
            $this->editing->type = InvoiceTransactionTypeEnum::HolidayPurchase;
           
            $this->invoice = $invoiceTransaction->invoice;
        } 
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->editing->reference = $this->holidayId;
        $this->editing->type = InvoiceTransactionTypeEnum::HolidayPurchase;
        $this->validate();
        
        // $this->invoice->user_id = Holiday::whereId($this->holidayId)->first()->user_id;
        $this->invoice->invoice_num =  Str::random(20);
        
        if ($this->editing->invoice_id) {
            $this->invoice->id = $this->editing->invoice_id;
            $this->invoice->update();
        } else {
            $this->notify(['message' => 'Invoice  well created', 'type' => 'success']);
            $this->invoice->save();
        }
        
        $invoiceTransaction = new InvoiceTransaction([
            'invoice_id' => $this->invoice->id,
            'price' =>  $this->editing->price,
            'reference' =>  $this->editing->reference,
            'type' =>  $this->editing->type
        ]);
        $invoiceTransaction->save();

        $this->showEditModal = false;

        $this->notify(['message' => 'Transaction  well saved', 'type' => 'success']);
    }

    public function makeBlankTransaction()
    {
        return InvoiceTransaction::with('invoice')->make([]);
    }
    public function makeBlankInvoice()
    {
        return Invoice::make([]);
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
            }, 'transactions.csv');
        }
    }
    /**
     * Get all transactions from current holidayId lookup in house_highlight table for all record with house_id
     * and in house_publications for all the record with the related publicationId belonging to the current holidayId
     * and same for house_packages
     */
    public function getRowsQueryProperty()
    {
        $query = InvoiceTransaction::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id))
            ->when($this->filters['created-min'], fn ($query, $date) => 
                $query->whereHas('invoice', fn ($query) => $query->where('created_at', '>=', Carbon::parse($date))))
            ->when($this->filters['created-max'], fn ($query, $date) => 
                $query->whereHas('invoice', fn ($query) => $query->where('created_at', '<=', Carbon::parse($date))))
            ->when($this->filters['datepayed-min'], fn ($query, $date) => 
                $query->whereHas('invoice', fn ($query) => $query->where('date_payed', '>=', Carbon::parse($date))))
            ->when($this->filters['datepayed-max'], fn ($query, $date) => 
                $query->whereHas('invoice', fn ($query) => $query->where('date_payed', '<=', Carbon::parse($date))))
            ->when($this->filters['user_id'], fn ($query, $id) => 
                $query->whereHas('invoice', fn ($query) => $query->where('user_id', '=', $id)))
            ->when($this->filters['price'], fn ($query, $price) => $query->where('price', '=', $price))
            ->when($this->filters['reference'], fn ($query, $reference) => $query->where('reference', '=', $reference))
            ->when($this->filters['type'], fn ($query, $type) => $query->where('type', '=', $type))
            ->when($this->filters['invoice_id'], fn ($query, $id) => $query->where('invoice_id', '=', $id))
            ->when($this->filters['payment_status'], fn ($query, $id) =>
            $query->whereHas('invoice', fn ($query) => $query->where('payment_status', $id)))
            ->where('reference','=',$this->holidayId)->where('type','=','holiday_purchase');

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        // return $this->cache(function () {
        //     return $this->applyPagination($this->rowsQuery);
        // });
    }
    public function usersResult()
    {
        if (!empty($this->userSearch)) {
            $query = User::query()->select(DB::raw("id, CONCAT(firstname,' ', lastname) AS title, email as subtitle"))
                ->when($this->userSearch, fn ($query, $name) => $query
                    ->where('firstname', 'like', '%' . $name . '%')
                    ->orWhere('lastname', 'like', '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%'))->limit(5);
            $this->users = $query->get();
        }
    }
    public function setAutoCompleteItem($type,$text, $id)
    {
        $this->userSearch = $text;
        $this->invoice->user_id = $id;
        $this->users = null;
    }
    public function render()
    {
        return view('livewire.admin.holiday.holiday-transactions', [
            'transactions' => $this->rows,
        ]);
    }
}
