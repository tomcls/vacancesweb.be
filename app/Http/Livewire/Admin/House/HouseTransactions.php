<?php

namespace App\Http\Livewire\Admin\House;

use App\Data\Enums\InvoiceTransactionTypeEnum;
use App\Models\House;
use App\Models\HouseHighlight;
use App\Models\HousePackage;
use App\Models\HousePublication;
use Livewire\Component;
use App\Models\Invoice;
use Illuminate\Support\Str;
use App\Models\InvoiceTransaction;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class HouseTransactions extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public InvoiceTransaction $editing;
    public Invoice $invoice;

    public $houseId;

    protected $queryString = ['sorts'];

    public $filters = [
        'id' => null,
        'price' => null,
        'invoice_id' => null,
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
        "invoice.payment_status" => 'required',
        "editing.invoice_id" => 'sometimes',
        "editing.highlight" => 'sometimes',
        "editing.publication" => 'sometimes',
        "editing.package" => 'sometimes',
        "editing.reference" => 'required',
        "editing.price" => 'required',
        "editing.type" => 'required',
    ];

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $housePublications = [];
    public $houseHighlights = [];
    public $housePackages = [];

    public $selectedType = null;

    public function mount($houseId)
    {
        $this->houseId = $houseId;
        $this->invoice = $this->makeBlankInvoice();
        $this->editing = $this->makeBlankTransaction();

        $this->housePackages = HousePackage::all();
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

        $this->housePublications = HousePublication::whereHouseId($this->houseId)->orderBy('id', 'desc')->limit(30)->get();
        $this->houseHighlights = HouseHighlight::whereHouseId($this->houseId)->orderBy('id', 'desc')->limit(30)->get();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankTransaction();

        $this->showEditModal = true;
    }
    public function updatedEditingType($o)
    {
        switch ($o) {
            case 'house_highlight':
                $this->houseHighlights = HouseHighlight::whereHouseId($this->houseId)->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('invoice_transactions')
                        ->whereRaw("invoice_transactions.reference = house_highlights.id and invoice_transactions.type='house_highlight' ");
                })->orderBy('id', 'desc')->limit(30)->get();
                break;

            case 'house_publication':
                $this->housePublications = HousePublication::whereHouseId($this->houseId)->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('invoice_transactions')
                        ->whereRaw("invoice_transactions.reference = house_publications.id and invoice_transactions.type='house_publication' ");
                })->orderBy('id', 'desc')->limit(30)->get();

                break;
            default:
                break;
        }
    }
    public function edit(InvoiceTransaction $invoiceTransaction)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($invoiceTransaction)) {

            $this->housePublications = HousePublication::whereHouseId($this->houseId)->orderBy('id', 'desc')->limit(30)->get();
            $this->houseHighlights = HouseHighlight::whereHouseId($this->houseId)->orderBy('id', 'desc')->limit(30)->get();

            $this->editing = $invoiceTransaction;
            if ($this->editing->type == InvoiceTransactionTypeEnum::HousePublication) {
                $this->editing->publication = $this->editing->reference;
            }
            if ($this->editing->type == InvoiceTransactionTypeEnum::HouseHighlight) {
                $this->editing->highlight = $this->editing->reference;
            }
            if ($this->editing->type == InvoiceTransactionTypeEnum::HousePackage) {
                $this->editing->package = $this->editing->reference;
            }
            $this->invoice = $invoiceTransaction->invoice;
        }
        $this->showEditModal = true;
    }

    public function save()
    {
        // if no invoice_id then create an invoice else just create a new transaction
        if ($this->editing->type == InvoiceTransactionTypeEnum::HousePublication) {
            $this->editing->reference = $this->editing->publication;
        }
        if ($this->editing->type == InvoiceTransactionTypeEnum::HouseHighlight) {
            $this->editing->reference = $this->editing->highlight;
        }
        if ($this->editing->type == InvoiceTransactionTypeEnum::HousePackage) {
            $this->editing->reference = $this->editing->package;
        }
        $this->validate();

        $this->invoice->user_id = House::whereId($this->houseId)->first()->user_id;
        $this->invoice->invoice_num =  Str::random(20);
        
        if ($this->editing->invoice_id && $this->invoice->id == $this->editing->invoice_id) {
            $this->invoice->id = $this->editing->invoice_id;
            $this->invoice->update();
        } elseif(!$this->editing->invoice_id) {
            $this->notify(['message' => 'Invoice  well created', 'type' => 'success']);
            $this->invoice->save();
        }
        if($this->editing->id) {
            $transaction = InvoiceTransaction::find($this->editing->id);
            $transaction->invoice_id = $this->editing->invoice_id;
            $transaction->price =  $this->editing->price;
            $transaction->reference = $this->editing->reference;
            $transaction->type = $this->editing->type;
            $transaction->save();
        } else {
            $invoiceTransaction = new InvoiceTransaction([
                'invoice_id' => $this->editing->invoice_id,
                'price' =>  $this->editing->price,
                'reference' =>  $this->editing->reference,
                'type' =>  $this->editing->type
            ]);
            $invoiceTransaction->save();
        }
        

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
     * Get all transactions from current houseId lookup in house_highlight table for all record with house_id
     * and in house_publications for all the record with the related publicationId belonging to the current houseId
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
            ->when($this->filters['price'], fn ($query, $price) => $query->where('price', '=', $price))
            ->when($this->filters['reference'], fn ($query, $reference) => $query->where('reference', '=', $reference))
            ->when($this->filters['type'], fn ($query, $type) => $query->where('type', '=', $type))
            ->when($this->filters['invoice_id'], fn ($query, $id) => $query->where('invoice_id', '=', $id))
            ->when($this->filters['payment_status'], fn ($query, $id) =>
            $query->whereHas('invoice', fn ($query) => $query->where('payment_status', $id)))
            ->where(function ($query) {
                $query->whereType('house_publication')->whereIn('reference', HousePublication::whereHouseId($this->houseId)->get()->pluck('id'))
                    ->orWhere(function (Builder $query) {
                        $query->whereIn('reference', HouseHighlight::whereHouseId($this->houseId)->get()->pluck('id'))
                            ->whereType('house_highlight');
                    });
            });


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
        return view('livewire.admin.house.house-transactions', [
            'transactions' => $this->rows,
        ]);
    }
}
