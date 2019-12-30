<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\RestData;
use Illuminate\Http\Request;
use App\Http\Resources\RestApiResource;

class RestDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->keys ){
            return $this->show($request->keys);
        }

        $data = RestData::all();
        return RestApiResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $response = [];
        foreach ($data as $key => $value) {
            $response[] = RestData::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
        return RestApiResource::collection($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  $keys
     * @return \Illuminate\Http\Response
     */
    public function show($keys)
    {
        if( Str::contains($keys, ',') ){
            $keys = explode(',', $keys);
            $data = RestData::whereIn('key', $keys)->get();
            return RestApiResource::collection($data);
        }
        $data = RestData::where('key', $keys)->first();
        return new RestApiResource($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $response = [];
        foreach ($data as $key => $value) {
            $response[] = RestData::updateOrCreate([
                'key' => $key ],[
                'value' => $value,
            ]);
        }
        return RestApiResource::collection($response);
    }

}
