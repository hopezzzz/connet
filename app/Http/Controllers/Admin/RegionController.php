<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Region;
use App\Helpers\GlobalFunctions as Helpers;
use Crypt;
use App\Model\Country;
use DB;

class RegionController extends Controller
{
    public $url;
    public $prefix;
    public function __construct()
    {
        $this->url=url(request()->route()->getPrefix());
        $this->admintemplatename = config('app.admintemplatename');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $currentUrl = Helpers::getCurrentUrl();

        $regions    = Region::where('is_deleted',0)->orderBy('id','DESC')->paginate(10);
        if($request->ajax()){
          $page =  ( $request['page'] - 1 ) * 10;
          if ( isset($_GET['searchKey']) ) {

            $regions    = Region::where('is_deleted',0)->where('name', 'like', '%' . $request->get('searchKey') . '%')->orderBy('id','DESC')->paginate(10);
          }
          return view($this->admintemplatename.'/regions/records_list',['url'=>$this->url,'current_url'=>$currentUrl,'regions'=>$regions,'page'=>$page]);
        }else{
            return view($this->admintemplatename.'/regions/index',['url'=>$this->url,'current_url'=>$currentUrl,'regions'=>$regions]);
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function saveRegion( Request $request )
    {
        if($request->ajax())
        {
            $postData = array_except($request->all(), ['_token']);
            $id       = null;
            if( $postData['id'] != '' )
            {
                $id  =  \Crypt::decrypt($postData['id']);
                $whereData = [
                    ['estimation_ref', $_POST['estimation_ref']],
                    ['id',  '!=', $id ]
                ];
                $region = Helpers::checkExits('regions',$whereData);
            }else{
                $whereData = [
                    ['nameestimation_ref', $_POST['estimation_ref']],
                ];
                $region = Helpers::checkExits('regions',$whereData);
            }
            if($region){
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = array('name' => 'This region name already exists!');
                return response($response);die;
            }

            $result = Region::updateOrCreate(['id'=>$id],$postData);
           	if($result)
    	   	{
    		   	$response['success']         = true;
                if( $id > 0 ){
                    $response['success_message']  = "Region updated successfully.";
                    $response['updateRecord']     = true;
                    $postData['id']               =  \Crypt::decrypt($postData['id']);
      		   	    $response['data']             = $postData;
                }
                else
    		   	    $response['success_message'] = "Region added successfully.";
                $response['delayTime'] = 1000;
                $response['url'] = $this->url.'/regions';
    		   	return response($response);
    	   	}else{
    		   	$response['success']       = false;
    		   	$response['error_message'] = "Something went wrong. Please try again.";
    	   	}
	   	    return response($response);
        }
    }

    public function RegionDetail( Request $request )
    {
        if($request->ajax())
        {
            $postData = $request->all();
            $recordID = $postData['recordID'];
            $recordDetail = array();
            if( $recordID != '' )
                $recordID= \Crypt::decrypt($recordID);
            if( $recordID > 0 )
            {

                $recordDetail   = Region::find($recordID);
            }
	          $currency = Country::select(DB::raw("CONCAT(currencyName,' - (',countryName,')') AS currency"),'countryId')->where('currencyName','!=','')->get()->pluck('currency','countryId')->toArray();
            $currency=array_map('trim',$currency);
            asort($currency);
            $currency = array_prepend($currency,'Select Currency','0');
            $view = view($this->admintemplatename.'/regions/savefrom',['recordDetail'=>$recordDetail,'currency'=>$currency,'recordID'=>$postData['recordID']])->render();
            return response()->json(['html'=>$view]);
        }
    }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
