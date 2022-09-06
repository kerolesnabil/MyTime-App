<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavePageRequest;
use App\Http\Requests\SaveRejectionReasonRequest;
use App\Models\OrderRejectionReason;
use App\Models\Page;
use App\Models\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderRejectionReasonController extends Controller
{

    public function index()
    {
        $reasons = OrderRejectionReason::getAllReasons();
        return view('dashboard.order_rejection_reasons.index')->with(['reasons' => $reasons]);
    }


    public function saveOrderRejectionReason(SaveRejectionReasonRequest $request, $reasonId= null)
    {

        $data['rejection_reason'] = json_encode($request->rejection_reason);

        if (!is_null($reasonId)){
            /**************  edit ***************/

            $data['rejection_reason_id']  = $reasonId;
            OrderRejectionReason::updateReasonData($data);
            session()->flash('success', __('site.updated_successfully'));
        }
        else{
            /**************  create ***************/
            OrderRejectionReason::createReason($data);
            session()->flash('success', __('site.created_successfully'));
        }
        return redirect(route('order_rejection_reason.index'));
    }

    public function getOrderRejectionReason($reasonId = null)
    {
        $langs = Lang::getAllLangs();

        if (!is_null($reasonId)){
            //edit
            $reason                   = OrderRejectionReason::getReasonById($reasonId);
            $reason->rejection_reason = json_decode($reason->rejection_reason, true);


            return view('dashboard.order_rejection_reasons.save')->with(['reason' => $reason, 'langs' => $langs]);
        }
        //create
        return view('dashboard.order_rejection_reasons.save')->with(['langs' => $langs]);
    }

    public function destroy($id)
    {
        OrderRejectionReason::findOrFail($id)->delete();
        return back();
    }

}
