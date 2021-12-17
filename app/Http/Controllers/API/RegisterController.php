<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
   
class RegisterController extends BaseController
{


     /**
     * users model
     *
     * @var UsersModel
     */
    protected $_model = null;

     /*********************************************************************
     *                                                                   *
     * PUBLIC MAIN FUNCTIONS                                             *
     *                                                                   *
     *********************************************************************/



    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = $this->getModel()->create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken->token;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->accessToken->token;
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    /*********************************************************************
     *                                                                   *
     * PUBLIC HELPER FUNCTIONS                                           *
     *                                                                   *
     *********************************************************************/


      /**
     * get users model
     *
     * @return \App\Models\Users
     */
    public function getModel()
    {
        if (!($this->_model instanceof User)) {
            $this->_model = new User();
        }
        return $this->_model;
    }
}