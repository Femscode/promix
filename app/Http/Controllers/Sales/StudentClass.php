<?php

namespace App\Http\Controllers\Sales;

use App\Abstracts\Http\Controller;
use App\Jobs\Common\CreateStudentClass;
use Illuminate\Http\Request;
use App\Models\Common\StudentClass as SC;
// use App\Traits\StudentClass as SC;

class StudentClass extends Controller
{

    
    // use SC;

    // /**
    //  * @var string
    //  */
    // public $type = SC::CUSTOMER_TYPE;

    public function index()
    {
        $studentclass = SC::latest()->collect();
        return $this->response('sales.studentclass.index', compact('studentclass'));
    }

    public function create() {
        return view('sales.studentclass.create');
    }

    public function newstore(Request $request) {
        $request->validate([
            'name' => 'required',
        ]);
        $check = SC::where('name', $request->name)->first();
        if($check) {
            return redirect()->back()->with('message','Class already exists');
        }
        $data = SC::create(['name' => $request->name]);
        return redirect()->route('studentclass.index')->with('message','Class created successfully');
        $response = [
            'success' => true,
            'error' => false,
            'data' => $data,
            'message' => '',
        ];
        return response()->json($response);
    }

  

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
   

    public function cancelClass() {
        return redirect()->route('studentclasses.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */

  

    public function editClass( $id)
    {
        $class = SC::find($id);
        return view('sales.studentclass.edit', compact('class'));
    }

    public function updateClass(Request $request) {

        // dd($request->all());
        $check = SC::where('name', $request->name)->first();
        if($check) {
           
            // $response = [
            //     'success' => true,
            //     'error' => false,
            //     'data' => $check,
            //     'message' => 'Already existed!',
            // ];
            $response['redirect'] = route('class.edit', $check->id);

            $message = trans('messages.success.created', ['type' => trans_choice('general.studentclass', 1)]);

           
            flash("Class already exists")->error()->important();
            return response()->json($response);
           
        }
        $class = SC::where('name', $request->previous_name)->first();
        // dd($class, $request->previous_name, $request->all());
        $class->name = $request->name;
        $class->save();
        $response['redirect'] = route('studentclasses.index', $class->id);

        $message = trans('messages.success.created', ['type' => trans_choice('general.studentclass', 1)]);

        flash($message)->success();
        return response()->json($response);
        // return redirect()->route('studentclasses.index')->with('message','Class updated successfully');
           
    }

    public function deleteClass($id) {
        $class = SC::find($id);
        $class->delete();
        return redirect()->route('studentclasses.index')->with('message','Class deleted successfully');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $check = SC::where('name', $request->name)->first();
        if($check) {
            return redirect()->back()->with('message','Class already exists');
        }
        $response = $this->ajaxDispatch(new CreateStudentClass($request));

        if ($response['success']) {
            return redirect()->route('studentclasses.index')->with('message','Class created successfully');
           
            $response['redirect'] = route('studentclasses.show', $response['data']->id);

            $message = trans('messages.success.created', ['type' => trans_choice('general.class', 1)]);

            flash($message)->success();
        } else {
            
            $response['redirect'] = route('studentclasses.create');
            return redirect()->back()->with('message',$response['message']);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function show(StudentClass $studentclass)
    {
        return view('sales.studentclass.show', compact('customer'));
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function duplicate(Contact $customer)
    {
        $clone = $this->dispatch(new DuplicateContact($customer));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.customers', 1)]);

        flash($message)->success();

        return redirect()->route('customers.edit', $clone->id);
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.customers', 2));

        if ($response['success']) {
            $response['redirect'] = route('customers.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['sales', 'customers']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function edit(StudentClass $class)
    {
        return view('sales.studentclass.edit', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Contact $customer
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Contact $customer, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, $request));

        if ($response['success']) {
            $response['redirect'] = route('customers.show', $response['data']->id);

            $message = trans('messages.success.updated', ['type' => $customer->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('customers.edit', $customer->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

  

    /**
     * Enable the specified resource.
     *
     * @param  Contact $customer
     *
     * @return Response
     */
    public function enable(Contact $customer)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $customer->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Contact $customer
     *
     * @return Response
     */
    public function disable(Contact $customer)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $customer->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Contact $customer
     *
     * @return Response
     */
    public function destroy(Contact $customer)
    {
        $response = $this->ajaxDispatch(new DeleteContact($customer));

        $response['redirect'] = route('customers.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $customer->name]);

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
        return $this->exportExcel(new Export, trans_choice('general.customers', 2));
    }

    public function createInvoice(Contact $customer)
    {
        $data['contact'] = $customer;

        return redirect()->route('invoices.create')->withInput($data);
    }

    public function createIncome(Contact $customer)
    {
        $data['contact'] = $customer;

        return redirect()->route('transactions.create', ['type' => 'income'])->withInput($data);
    }
}
