<?php

namespace App\Http\Livewire\Admin\House;

use App\Data\Enums\InvoiceTransactionTypeEnum;
use App\Models\House;
use App\Models\HouseHighlight;
use App\Models\HousePackage;
use App\Models\HousePackageUser;
use App\Models\HousePublication;
use Livewire\Component;
use App\Models\Invoice;
use Illuminate\Support\Str;
use App\Models\InvoiceTransaction;
use App\Models\User;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Transactions extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public InvoiceTransaction $editing;
    public Invoice $invoice;

    protected $queryString = ['sorts'];

    protected $listeners = ['selectAutoCompleteItem' => 'setAutoCompleteItem'];

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
        "invoice.user_id" => 'required',
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
    public $housePackageUsers = [];

    public $selectedType = null;

    public $users = null;
    public $userSearch = null;

    public function mount()
    {
        $this->invoice = $this->makeBlankInvoice();
        $this->editing = $this->makeBlankTransaction();
        $this->housePublications = HousePublication::orderBy('id','desc')->whereNotExists(function($query)
        {
            $query->select(DB::raw(1))
                  ->from('invoice_transactions')
                  ->whereRaw("invoice_transactions.reference = house_publications.id and invoice_transactions.type='house_publication' " );
        })->limit(30)->get();

        $this->houseHighlights = HouseHighlight::orderBy('id','desc')->whereNotExists(function($query)
        {
            $query->select(DB::raw(1))
                  ->from('invoice_transactions')
                  ->whereRaw("invoice_transactions.reference = house_highlights.id and invoice_transactions.type='house_highlight' " );
        })->limit(30)->get();
        
        $this->housePackageUsers = HousePackage::all();
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
            if($this->editing->type == InvoiceTransactionTypeEnum::HousePublication) {
                $this->editing->publication = $this->editing->reference;
            }
            if($this->editing->type == InvoiceTransactionTypeEnum::HouseHighlight) {
                $this->editing->highlight = $this->editing->reference;
            }
            if($this->editing->type == InvoiceTransactionTypeEnum::HousePackage) {
                $this->editing->package = $this->editing->reference;
            }
            $this->invoice = $invoiceTransaction->invoice;
        } 
        $this->showEditModal = true;
    }

    public function save()
    {
        // if no invoice_id then create an invoice else just create a new transaction
        if($this->editing->type == InvoiceTransactionTypeEnum::HousePublication) {
            $this->editing->reference = $this->editing->publication;
        }
        if($this->editing->type == InvoiceTransactionTypeEnum::HouseHighlight) {
            $this->editing->reference = $this->editing->highlight;
        }
        if($this->editing->type == InvoiceTransactionTypeEnum::HousePackage) {
            $this->editing->reference = $this->editing->package;
        }
        $this->validate();
        
        
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

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count()) {
            return response()->streamDownload(function () {
                echo $this->selectedRowsQuery->toCsv();
            }, 'transactions.csv');
        }
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
     // Fetch records
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
         switch ($type) {
             case 'invoice.user_id':
                 $this->userSearch = $text;
                 $this->invoice->user_id = $id;
                 $this->users = null;
                 $this->houseHighlights = HouseHighlight::orderBy('id','desc')->whereNotExists(function($query)
                 {
                     $query->select(DB::raw(1))
                           ->from('invoice_transactions')
                           ->whereRaw("invoice_transactions.reference = house_highlights.id and invoice_transactions.type='house_highlight' " );
                 })->whereIn('house_id', House::whereUserId($id)->get()->pluck('id'))->limit(30)->get();
                 $this->housePublications = HousePublication::orderBy('id','desc')->whereNotExists(function($query)
                 {
                     $query->select(DB::raw(1))
                           ->from('invoice_transactions')
                           ->whereRaw("invoice_transactions.reference = house_publications.id and invoice_transactions.type='house_publication' " );
                 })->whereIn('house_id', House::whereUserId($id)->get()->pluck('id'))->limit(30)->get();

                 $this->housePackageUsers = HousePackageUser::orderBy('id','desc')->whereNotExists(function($query)
                 {
                     $query->select(DB::raw(1))
                           ->from('invoice_transactions')
                           ->whereRaw("invoice_transactions.reference = house_package_users.id and invoice_transactions.type='house_package' " );
                 })->whereUserId($id)->limit(30)->get();
                 break;
 
             default:
                 # code...
                 break;
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
            $query->whereHas('invoice', fn ($query) => $query->where('payment_status', $id)));
            // ->where(function ($query)  {
            //     $query->whereType('house_publication')->whereIn('reference', HousePublication::whereHouseId($this->houseId)->get()->pluck('id'))
            //     ->orWhere(function(Builder $query) {
            //         $query->whereIn('reference', HousePublication::whereHouseId($this->houseId)->get()->pluck('id'))
            //         ->whereType('house_highlight');
            //     });
            // });
            

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
        return view('livewire.admin.house.transactions', [
            'transactions' => $this->rows,
        ])->layout('layouts.admin');
    }
}
