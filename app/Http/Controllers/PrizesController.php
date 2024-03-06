<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Prize;
use App\Http\Requests\PrizeRequest;
use Illuminate\Http\Request;



class PrizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $prizes = Prize::all();

        return view('prizes.index', ['prizes' => $prizes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('prizes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrizeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PrizeRequest $request)
    {
        $requestProbability = floatval($request->input('probability')); 

        if($requestProbability > 0) {
            /* SUM OF ROBABILITY */
            $sumOfProbability = floatval(Prize::sum('probability'));

            /* CHECK AVILABLE PROBABILITY*/
            $currentProbability = (100 - $sumOfProbability);

            // dd($sumOfProbability, $currentProbability, $requestProbability);
            if(!empty($currentProbability)) {
                if(($sumOfProbability <= 100) && (($currentProbability >= $requestProbability) || $currentProbability == $requestProbability)) {
                    $prize = new Prize;
                    $prize->title = $request->input('title');
                    $prize->probability = floatval($request->input('probability'));
                    $prize->save();
                    return to_route('prizes.index');
                }
                return $this->create()->withErrors(['message' => 'The probability feild must not be greater then Number ' . $currentProbability]);
            } 
            return $this->create()->withErrors(['message' => 'You already have added 100% prizes probability.']);
        }
        return $this->create()->withErrors(['message' => 'The probability feild must be greater then Number 1']);

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $prize = Prize::findOrFail($id);
        return view('prizes.edit', ['prize' => $prize]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PrizeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PrizeRequest $request, $id)
    {

        $requestProbability = floatval($request->input('probability')); 
        
        /* SUM OF PROBABILITY */
        $sumOfProbability = floatval(Prize::sum('probability'));

        /* CHECK ALL ExcludedId WITH ALL SUM OF PROBABILITY */
        $getExcludedIdProbability = Prize::where('id', '!=', $id)->sum('probability');
        $updateValueWithProbability = ($getExcludedIdProbability + $requestProbability);
        
        if($sumOfProbability <= 100 && $updateValueWithProbability <= 100) {
            $prize = Prize::findOrFail($id);
            $prize->title = $request->input('title');
            $prize->probability = $requestProbability;
            $prize->save();
            return to_route('prizes.index');
        }

        $outOfExcludedIdProbability = (100 - $getExcludedIdProbability);
        return $this->edit($id)->withErrors(['message' => 'The probability feild must not be greater then Number ' . $outOfExcludedIdProbability]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $prize = Prize::findOrFail($id);
        $prize->delete();

        return to_route('prizes.index');
    }


    public function simulate(Request $request)
    {

        for ($i = 0; $i < $request->number_of_prizes ?? 10; $i++) {
            Prize::nextPrize();
        }

        return to_route('prizes.index');
    }

    public function reset()
    {
        Prize::truncate();
        return to_route('prizes.index');
    }
}
