<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Branch;
use App\Leads;
use App\Partner;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $id_branch = $request->input('branch');

        //menampilkan user berdasarkan cabang dengan login log
        $userCabang = User::where('id_branch', $id_branch)->get();
        $collection = collect($userCabang);
        foreach ($userCabang as $user) {
            $users = $collection->collect($user->loginLog);
        }

        //data agent
        $agents = Agent::where('id_branch', $id_branch);
        if ($agents->doesntExist()) {
            $dataAgent = ["data" => [], "count" => 0];
        } else {
            $collection = collect(
                [
                    "data" => $agents->get(),
                    "count" => $agents->count()
                ]
            );
            foreach ($collection["data"] as $agent) {
                $dataAgent = $collection->collect(
                    [
                        $agent->user,
                        $agent->branch
                    ]
                );
            }
        }

        //data merchant
        $merchants = Partner::where('id_branch', $id_branch);
        if ($merchants->doesntExist()) {
            $dataMerchant = ["data" => [], "count" => 0];
        } else {
            $collection = collect(
                [
                    "data" => $merchants->get(),
                    "count" => $merchants->count()
                ]
            );
            foreach ($collection["data"] as $merchant) {
                $dataMerchant = $collection->collect(
                    [
                        $merchant->user,
                        $merchant->branch
                    ]
                );
            }
        }

        //data prospect
        $prospects = Leads::where('id_branch', $id_branch);
        if ($prospects->doesntExist()) {
            $dataProspect = ["data" => [], "count" => 0];
        } else {
            $collection = collect(
                [
                    "data" => $prospects->get(),
                    "count" => $prospects->count()
                ]
            );
            foreach ($collection["data"] as $prospect) {
                $dataProspect = $collection->collect(
                    [
                        $prospect->user,
                        $prospect->branch
                    ]
                );
            }
        }
        //data soa
        $dataSoa = Leads::selectRaw('soa, COUNT(soa) as jumlah')
            ->where('id_branch', $id_branch)
            ->groupBy('soa')
            ->having('soa', '!=', 'null')
            ->get();

        //data Ticket
        $ticket = Ticket::selectRaw("*, count(*) as jumlah")->where('id_branch', $id_branch);
        $dataTicket = collect(
            [
                // "tiket_today" => $ticket->whereDate('date_modified', date('d-m-Y'))->get(),
                // "tiket_weekly" => $ticket->whereRaw("DATE_FORMAT(date_modified, '%Y-%m-%d') BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()")->get(),
                "status_tiket" => $ticket->groupBy('status')->having('status', '>=', 0)->get()
            ],
        );

        // $dataTicket = $collection->merge([]);
        // Ticket::selectRaw('CASE    
        //              WHEN status = 0 THEN "Head Approval"
        //              WHEN status = 1 THEN "Manager Approval"
        //              WHEN status = 2 THEN "Pending HO"
        //              WHEN status = 4 THEN "Rejected/Returned"
        //              WHEN status = 5 THEN "Approved"
        //              WHEN status = 6 THEN "Finished"
        //          END AS status, COUNT(status) as jumlah')
        //     ->where('id_branch', $id_branch)
        //     ->groupBy('status')
        //     ->having('status', '>=', 0)
        //     ->get();


        $data = [
            "cabang"            => Branch::all(),
            "leads_prospect"    => $dataProspect,
            "merchant"          => $dataMerchant,
            "agent"             => $dataAgent,
            "form_survey"       => DB::table('fs_konsumen')->join('leads_full', 'leads_full.id_leads', '=', 'fs_konsumen.id_leads')->where('id_branch', $id_branch)->count(),
            "ticket"            => $dataTicket,
            "soa"               => $dataSoa,
            "users"             => $users
        ];

        return response()->json([
            "status" => "success",
            "data" => $data
        ], 200);
    }
}
