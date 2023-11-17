<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\User\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {   
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->getAll();
        return response()->json([
            'code' => 200,
            'messages' => "success",
            'data' => $users,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
            $this->userRepository->create($request->all());
            return response()->json([
                "messages"=> "create success",
                "code"=> 201,
                "data"=> $this->userRepository->getAll(),
            ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = $this->userRepository->find($id);
            return response()->json([
                "messages" => "success",
                "code" => 200,
                "data" => $user,
                
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => $e->getMessage(),
                "code" => 404,
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $user = $this->userRepository->find($id);
            if (!$user) {
                throw new \Exception('Không tìm thấy người dùng', 404);
            }
            return response()->json([
                "messages" => "success",
                "code" => 200,
                "data" => $user,
                
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
                "code" => $e->getCode()
            ], $e->getCode());
        }
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
        try {
            $this->userRepository->update($request->all(), $id);
            return response()->json([
                "message"=> "update success",
                "code"=> 200,
                "data"=> $this->userRepository->find($id),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message"=> "update failed: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        try {
            $deleted = $this->userRepository->delete($id);
            if (!$deleted) {
                throw new \Exception('Không tìm thấy người dùng', 404);
            }
                return response()->json([
                    "message" => "Xóa thành công",
                    "code" => 200,
                ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "code" => $th->getCode()
            ], $th->getCode());
        }
    }
}
