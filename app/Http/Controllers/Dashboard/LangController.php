<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveLangRequest;
use App\Http\Requests\SavePageRequest;
use App\Models\Page;
use App\Models\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LangController extends Controller
{

    public function index()
    {
        $langs = Lang::getAllLangs();
        return view('dashboard.langs.index')->with(['langs' => $langs]);
    }


    public function saveLang(SaveLangRequest $request, $langId= null)
    {


        $data['lang_symbol']    = $request->lang_symbol;
        $data['lang_name']      = $request->lang_name;
        $data['lang_direction'] = $request->lang_direction;
        $data['lang_is_active'] = $request->lang_is_active;



        if (!is_null($langId)){
            /**************  edit ***************/
            $data['lang_id'] = $langId;
            if (!is_null($request->lang_img)) {
                $pageImg = ImgHelper::uploadImage('images', $request->lang_img);
                Lang::updateLangData($data, $pageImg);
            }
            else {
                Lang::updateLangData($data);
            }

            session()->flash('success', __('site.updated_successfully'));
        }
        else{
            /**************  create ***************/
            $langImg = ImgHelper::uploadImage('images', $request->lang_img);
            $data['lang_img'] = $langImg;
            Lang::createLang($data);
            session()->flash('success', __('site.created_successfully'));
        }

        return redirect(route('lang.index'));
    }

    public function getLang($langId = null)
    {

        if (!is_null($langId)){
            //edit
            $lang               = Lang::getLangById($langId);
            return view('dashboard.langs.save')->with(['lang' => $lang,]);
        }
        //create
        return view('dashboard.langs.save');
    }

    public function updateActivationLang(Request $request)
    {

        if (isset($request->active_status) && isset($request->lang_id)){

            if ($request->active_status == 'true'){
                Lang::updateLangActivationStatus($request->lang_id, 1);
                return response()->json(['lang_id' =>$request->lang_id, 'status' => 'activate']);
            }

            Lang::updateLangActivationStatus($request->lang_id, 0);
            return response()->json(['lang_id' =>$request->lang_id, 'status' => 'deactivate']);
        }
        return response()->json(false);

    }

    public function destroy($id)
    {
        Lang::findOrFail($id)->delete();
        return back();
    }


}
