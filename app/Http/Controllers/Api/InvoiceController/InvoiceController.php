<?php

namespace App\Http\Controllers\Api\InvoiceController;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Invoice::query();

        if ($search) {
            $query->where('invoice_number', 'LIKE', "%{$search}%")
                ->orWhere('seller_nip', 'LIKE', "%{$search}%")
                ->orWhere('buyer_nip', 'LIKE', "%{$search}%")
                ->orWhere('product_name', 'LIKE', "%{$search}%");
        }

        $invoices = $query->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $invoices->getCollection()->transform(function ($invoice) {
            $invoice->updated_at_formatted = $invoice->updated_at->format('Y-m-d');
            return $invoice;
        });

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'filters'  => $request->only(['search']),
            'success'  => session('success')
        ]);
    }

    public function create()
    {
        return Inertia::render('Invoices/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number|max:50',
            'seller_nip'     => 'required|digits:10',
            'buyer_nip'      => 'required|digits:10',
            'product_name'   => 'required|string|max:255',
            'net_amount'     => 'required|numeric|min:0',
            'issuance_date'  => 'nullable|date',
        ]);

        Invoice::create($validated);

        return redirect()->route('invoices.index')
            ->with('success', 'Faktura została dodana.');
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);

        return Inertia::render('Invoices/Edit', [
            'invoice' => $invoice
        ]);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'invoice_number' => 'required|max:50|unique:invoices,invoice_number,' . $invoice->id,
            'seller_nip'     => 'required|digits:10',
            'buyer_nip'      => 'required|digits:10',
            'product_name'   => 'required|string|max:255',
            'net_amount'     => 'required|numeric|min:0',
            'issuance_date'  => 'nullable|date',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.index')
            ->with('success', 'Faktura została zaktualizowana.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Faktura została usunięta.');
    }
}
