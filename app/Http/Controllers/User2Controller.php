<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use App\Models\Users;
    use App\Traits\ApiResponser;
    use DB;

    Class UserController extends Controller {
        use ApiResponser;

      

        public function index() {
            $users = Users::all();
            return $this->successResponse($users);
        }

        public function add(Request $request) {
            $rules = [
                'username' => 'required|max:255',
                'password' => 'required|max:255',
                'gender' => 'required|in:M,F',
            ];
            
            $this->validate($request, $rules);
            $users = Users::create($request->all());
            return $this->successResponse($users, Response::HTTP_CREATED);
        }

        public function show($id) {

            $users = Users::findOrFail($id);
            return $this->successResponse($users);
            /*
            $users = Users::where('id', $id)->first();
            if ($users){
                return $this->successResponse($users);
            }
            return $this->errorResponse('User ID Does Not Exist', Response::HTTP_NOT_FOUND);
            */
        }

        public function update(Request $request, $id) {
            $rules = [
                'username' => 'max:255',
                'password' => 'max:255',
                'gender' => 'in:M,F',
            ];

            $this->validate($request, $rules);
            $users= Users::findOrFail($id);

            $users->fill($request->all());

            if($users->isClean()){
                return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $users->save();
            return $this->successResponse($users);

            /*
            $this->validate($request, $rules);
            $users = Users::where('id', $id)->first();
                if($users){
                    $users->fill($request->all());
                    if($users->isClean()) {
                        return $this->errorResponse('At least one value must be changed',
                        Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    $users->save();
                    return $this->successResponse($users);
                }
                return $this->errorResponse('User ID does not exist', Response::HTTP_NOT_FOUND);
            */
        }

        public function delete($id) {
            $users = Users::findOrFail($id);
            $users->delete();
            return $this->errorResponse('User ID Does Not Exist',Response::HTTP_NOT_FOUND);

            /*
            $users = Users::where('id', $id)->first();
            if($users){
                $users->delete();
                return $this->successResponse($users);
            }
            return $this->errorResponse('User ID does not exist', Response::HTTP_NOT_FOUND);
            */

        }


    }

?>