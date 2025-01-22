<?php

namespace App\Http\Controllers\Sales;

use App\Abstracts\Http\Controller;
use App\Exports\Sales\Invoices\Invoices as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Document\Document as Request;
use App\Imports\Sales\Invoices\Invoices as Import;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\DownloadDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Jobs\Document\SendDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Common\Contact;
use App\Models\Document\Document;

use App\Traits\Documents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Invoices extends Controller
{
    use Documents;

    public string $type = Document::INVOICE_TYPE;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->setActiveTabForDocuments();

        $invoices = Document::invoice()->with('contact', 'items', 'item_taxes', 'last_history', 'transactions', 'totals', 'histories', 'media')->collect(['document_number' => 'desc']);

        $total_invoices = Document::invoice()->count();


        return $this->response('sales.invoices.index', compact('invoices', 'total_invoices'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function show(Document $invoice)
    {
        return view('sales.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        session(['create_type' => 'single']);

        return view('sales.invoices.create');
    }

    public function create_bulk()
    {
        session(['create_type' => 'bulk']);

        return view('sales.invoices.create-bulk');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->class_id !== null) {
            $response = $this->storeBulk($request);
            if ($response['success']) {
                $paramaters = ['invoice' => $response['data']->id];

                if ($request->has('senddocument')) {
                    $paramaters['senddocument'] = true;
                }

                $response['redirect'] = route('invoices.show', $paramaters);

                $message = trans('messages.success.created', ['type' => trans_choice('general.invoices', 1)]);

                flash($message)->success();
            } else {
                $response['redirect'] = route('invoices.create');

                $message = $response['message'];

                flash($message)->error()->important();
            }

            return response()->json($response);
        }

        $response = $this->ajaxDispatch(new CreateDocument($request));

        if ($response['success']) {
            $paramaters = ['invoice' => $response['data']->id];

            if ($request->has('senddocument')) {
                $paramaters['senddocument'] = true;
            }

            $response['redirect'] = route('invoices.show', $paramaters);

            $message = trans('messages.success.created', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('invoices.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function oldstoreBulk($data)
    {

        DB::beginTransaction();
        // dd($data);
        $allstudents = Contact::where('student_class', $data->class_id)->get();
        $document_number = $data->document_number;
        preg_match('/^([^\d]+)(\d+)$/', $document_number, $matches);
        if (!isset($matches[1], $matches[2])) {
            $message = "Invalid document number";

            flash($message)->error()->important();
        }
        [$prefix, $number] = [$matches[1], (int)$matches[2]];
        $first_amount = null;
        // $items = $data->items;
        foreach ($allstudents as $key => $student) {
            $document_number = $prefix . str_pad(++$number, strlen($matches[2]), '0', STR_PAD_LEFT);
            $data['contact_id'] = $student->id;
            $data['contact_name'] = $student->name;
            $data['contact_email'] = $student->email;
            $data['contact_tax_number'] = $student->tax_number;
            $data['contact_phone'] = $student->phone;
            $data['contact_address'] = $student->address;
            $data['contact_country'] = $student->country;
            $data['contact_state'] = $student->state;
            $data['contact_zip_code'] = $student->zip_code;
            $data['contact_city'] = $student->city;
            $data['document_number'] = $document_number;
            if ($first_amount !== null) {
                $data['amount'] = $first_amount;
            }
            $response = $this->ajaxDispatch(new CreateDocument($data));
            if ($key == 0) {
                $first_amount = $response['data']->amount;
            }
        }

        if ($response['success']) {
            DB::commit();
            $paramaters = ['invoice' => $response['data']->id];

            if ($data->has('senddocument')) {
                $paramaters['senddocument'] = true;
            }

            $response['redirect'] = route('invoices.show', $paramaters);

            $message = trans('messages.success.created', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            DB::rollback();
            $response['redirect'] = route('invoices.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function storeBulk($data)
    {
        DB::beginTransaction();

        try {
            // Validate the document number
            $document_number = $data->document_number;
            if (!preg_match('/^([^\d]+)(\d+)$/', $document_number, $matches)) {
                flash("Invalid document number")->error()->important();
                return response()->json(['success' => false, 'message' => "Invalid document number"]);
            }

            [$prefix, $number] = [$matches[1], (int) $matches[2]];
            $allStudents = Contact::where('student_class', $data->class_id)->get();

            if ($allStudents->isEmpty()) {
                flash("No students found for the selected class")->error()->important();
                return response()->json(['success' => false, 'message' => "No students found"]);
            }

            $firstAmount = 0;
            $response = null;
            $isFirstIteration = true;
            foreach ($allStudents as $key => $student) {

                $document_number = $prefix . str_pad(++$number, strlen($matches[2]), '0', STR_PAD_LEFT);
                $studentData = $this->prepareStudentData($data, $student, $document_number, $firstAmount);


                $response = $this->ajaxDispatch(new CreateDocument($studentData));

                if ($isFirstIteration && isset($response['data']->amount)) {
                    $firstAmount = 0;
                    $isFirstIteration = false;
                    // dd($response, $isFirstIteration, $response['data']->amount);
                }

                if (!$response['success']) {
                    throw new \Exception($response['message'] ?? "Failed to create document");
                }
            }

            // Commit and redirect
            DB::commit();


            $parameters = ['invoice' => $response['data']->id];
            if ($data->has('senddocument')) {
                $parameters['senddocument'] = true;
            }
            return $response;
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollback();
            flash($e->getMessage())->error()->important();
            return $response;
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Prepare student data for document creation.
     */
    private function prepareStudentData($data, $student, $document_number, $firstAmount)
    {
        return array_merge($data->toArray(), [
            'contact_id' => $student->id,
            'contact_name' => $student->name,
            'contact_email' => $student->email,
            'contact_tax_number' => $student->tax_number,
            'contact_phone' => $student->phone,
            'contact_address' => $student->address,
            'contact_country' => $student->country,
            'contact_state' => $student->state,
            'contact_zip_code' => $student->zip_code,
            'contact_city' => $student->city,
            'document_number' => $document_number,
            'amount' => $firstAmount ?? $data['amount'],
        ]);
    }


    /**
     * Duplicate the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function duplicate(Document $invoice)
    {
        $clone = $this->dispatch(new DuplicateDocument($invoice));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect()->route('invoices.edit', $clone->id);
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportRequest  $request
     *
     * @return Response
     */
    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('general.invoices', 2));

        if ($response['success']) {
            $response['redirect'] = route('invoices.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['sales', 'invoices']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function edit(Document $invoice)
    {
        return view('sales.invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Document $invoice
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Document $invoice, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDocument($invoice, $request));

        if ($response['success']) {
            $paramaters = ['invoice' => $response['data']->id];

            if ($request->has('senddocument')) {
                $paramaters['senddocument'] = true;
            }

            $response['redirect'] = route('invoices.show', $paramaters);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('invoices.edit', $invoice->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function destroy(Document $invoice)
    {
        $response = $this->ajaxDispatch(new DeleteDocument($invoice));

        $response['redirect'] = route('invoices.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('general.invoices', 2));
    }

    /**
     * Mark the invoice as sent.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function markSent(Document $invoice)
    {
        event(new \App\Events\Document\DocumentMarkedSent($invoice));

        $message = trans('documents.messages.marked_sent', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Mark the invoice as cancelled.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function markCancelled(Document $invoice)
    {
        event(new \App\Events\Document\DocumentCancelled($invoice));

        $message = trans('documents.messages.marked_cancelled', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Restore the invoice.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function restoreInvoice(Document $invoice)
    {
        event(new \App\Events\Document\DocumentRestored($invoice));

        $message = trans('documents.messages.restored', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Download the PDF file of invoice.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function emailInvoice(Document $invoice)
    {
        if (empty($invoice->contact_email)) {
            return redirect()->back();
        }

        $response = $this->ajaxDispatch(new SendDocument($invoice));

        if ($response['success']) {
            $message = trans('documents.messages.email_sent', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return redirect()->back();
    }

    /**
     * Print the invoice.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function printInvoice(Document $invoice)
    {
        event(new \App\Events\Document\DocumentPrinting($invoice));

        $view = view($invoice->template_path, compact('invoice'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Download the PDF file of invoice.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function pdfInvoice(Document $invoice)
    {
        event(new \App\Events\Document\DocumentPrinting($invoice));

        return $this->dispatch(new DownloadDocument($invoice, null, null, false, 'download'));
    }
}
