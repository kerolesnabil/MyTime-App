<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SavePaymentMethodRequest;
use App\Models\Page;
use App\Models\Lang;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{

    public function index()
    {
        $paymentMethods = PaymentMethod::getPaymentMethods('web');
        return view('dashboard.payment_methods.index')->with(['payment_methods' => $paymentMethods]);
    }


    public function savePaymentMethod(SavePaymentMethodRequest $request, $paymentMethodId= null)
    {


        $data['payment_method_name'] = json_encode($request->payment_method_name);
        $data['payment_method_type'] = $request->payment_method_type;
        $data['is_active']           = $request->is_active;


        if (!is_null($paymentMethodId)){
            /**************  edit ***************/
            PaymentMethod::savePaymentMethod($data, $paymentMethodId);
            session()->flash('success', __('site.updated_successfully'));
        }
        else{
            /**************  create ***************/

            PaymentMethod::savePaymentMethod($data);
            session()->flash('success', __('site.created_successfully'));
        }

        return redirect(route('payment_method.index'));
    }

    public function getPaymentMethod($paymentMethodId = null)
    {
        $langs = Lang::getAllLangs();

        if (!is_null($paymentMethodId)){
            //edit
            $payment_method                      = PaymentMethod::getPaymentMethodById($paymentMethodId);
            $payment_method->payment_method_name = json_decode($payment_method->payment_method_name, true);
            return view('dashboard.payment_methods.save')->with(['payment_method' => $payment_method, 'langs' => $langs]);
        }
        //create

        return view('dashboard.payment_methods.save')->with(['langs' => $langs]);
    }

    public function updateActivationPaymentMethod(Request $request)
    {

        if (isset($request->active_status) && isset($request->payment_method_id)){

            if ($request->active_status == 'true'){
                PaymentMethod::updatePaymentMethodActivationStatus($request->payment_method_id, 1);
                return response()->json(['payment_method_id' =>$request->payment_method_id, 'status' => 'activate']);
            }

            PaymentMethod::updatePaymentMethodActivationStatus($request->payment_method_id, 0);
            return response()->json(['payment_method_id' =>$request->payment_method_id, 'status' => 'deactivate']);
        }
        return response()->json(false);

    }


    public function destroy($id)
    {
        PaymentMethod::findOrFail($id)->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return back();
    }

}
