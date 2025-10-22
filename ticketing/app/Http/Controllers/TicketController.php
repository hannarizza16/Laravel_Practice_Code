<?php

namespace App\Http\Controllers;

use App\Models\TicketModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    public function validator(Request $request, $id){
        return $request->validate([
            "case_number" => ['required', Rule::unique('tickets', 'case_number')->ignore($id, 'ticket_id')],
            "case_title" => 'required|string',
            "case_type" => 'required', 
            "petitioner_name" => 'required|string',
            "respondent_name" => 'required|string',
            "filing_date" => "required|date"
        ]); 
    }


    public function index(Request $request)
    {
        try {
            $tickets = TicketModel::all();
            //  $tickets = TicketModel::orderBy('case_number', 'desc')->get();

            if ($request->wantsJson()) {
                return response()->json([
                    "message" => "List of tickets",
                    'data' => $tickets
                ]);
            }
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    "message" => "Error fetching tickets",
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'case_number' => 'required|unique:tickets,case_number',
            'case_title' => 'required|string',
            'case_type' => 'required|string|in:civil,criminal,constitutional',
            'petitioner_name' => 'required|string',
            'respondent_name' => 'required|string',
            'filing_date' => 'required|date',

        ]);
        try {
            $ticket = TicketModel::create($validated);

            if(request()->wantsJson()){
                return response()->json([
                    'message' => 'successfully added',
                    'data' => $ticket
                ]);
            }
        }catch ( ValidationException $e ) {
            return response()->json([
                'message' => "Some Validation are not met",
                "errors" => $e->errors()
            ]);
        }catch (Exception $e) {
            return response()->json([
                "message" => "something went wrong",
                "error" => $e->getMessage()
            ]);
        }
    }
    public function show(string $id)
    {
        try {
            $ticket = TicketModel::findOrFail($id);

            return response()->json([
                "message" => 'Data has been successfully fetched',
                "data"=> $ticket
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Something went wrong",
                "error" => $e->getMessage()
            ]);
        }
    }

    
    public function update(Request $request, string $id)
    {

        // dd($request->all());
        try {
            $validated = $this->validator($request, $id);
            $ticket = TicketModel::findOrFail($id);
            $ticket->update($validated);

            return response()->json([
                "message" => "Updated Successfully",
                "data" => $ticket 
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                "message"=> "Validation did not pass",
                "errors" => $e->errors()
            ]);
        } catch (Exception $e){
            return response()->json([
                "message" => "Something went wrong",
                "error"=> $e->getMessage()
            ]);
        }
    }
    public function destroy(string $id)
    {
        $ticket = TicketModel::findOrFail($id);
        $ticket->delete();

        return response()->json([
            "message" => "$ticket->ticket_id successfully deleted",
            "data" => $ticket
        ]);
    }
}
