<?php

namespace App\Http\Controllers;

use App\Models\InvoiceTransaction;
use PDF;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $id)
    {
        if ($id) {
            $transactions = InvoiceTransaction::whereInvoiceId($id)->get();
            $user = $transactions[0]->invoice->user;
            $total = 0;
            foreach ($transactions as $key => $tr) {
                $total += $tr->price;
            }
            $vat = $total /1.21;
        }
        $data = [
            'title' => 'Invoice',
            'date' => $transactions[0]->invoice->date_payed_for_humans,
            'user' => $user,
            'transactions' => $transactions,
            'total' => $total,
            'vat' => $vat
        ];
        $pdf = Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif'])->loadView('invoice', $data);

        return $pdf->download('invoice.pdf');
    }
}
