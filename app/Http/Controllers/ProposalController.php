<?php

namespace App\Http\Controllers;

use App\Http\Services\EmailService;
use App\Http\Services\ProposalService;
use App\Models\Employees;
use App\Models\InternalUser;
use App\Models\Proposal;
use App\Models\ProposalDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProposalController extends Controller
{
    private $vp;
    private $service;

    public function __construct(
        ProposalService $service
    )
    {
        $this->vp = 'proposals';
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        breadcrumb([
            [
                'name' => __('view.proposal'),
                'active' => false,
            ],
            [
                'name' => __('view.list'),
                'active' => false,
            ],
        ]);
        return view($this->vp . '.index');
    }

    public function ajax()
    {
        $data = Proposal::all();
        return DataTables::of($data)
            ->editColumn('event_date', function($d) {
                $time = $d->event_time;
                $date = generate_indo_date($d->event_date) . ' ' . $time;
                return $date;
            })
            ->editColumn('pic', function($d) {
                $type = $d->pic_user_type;
                if ($type == 1) {
                    $user = InternalUser::select('name', 'id')
                        ->with(['institution:id,name', 'class:id,name', 'level:id,name'])
                        ->find($d->pic);
                    $text = '<p style="margin: 0; font-weight: bolder;">'. $user->name .'</p>';
                    $text .= '<p style="margin-bottom: 0; margin-top: 5px; font-size: 10px;" class="themed-color-night">'. $user->institution->name . ' (' . $user->class->name . $user->level->name .')</p>';
                } else {
                    $user = Employees::select('name', 'id', 'position_id', 'institution_id')
                        ->with(['institution:id,name', 'position:id,name'])
                        ->where('id', $d->pic)
                        ->first();
                    $text = '<p style="margin: 0; font-weight: bolder;">'. $user->name .'</p>';
                    $text .= '<p style="margin-bottom: 0; font-size: 10px;" class="themed-color-night">'. $user->institution->name . ' (' . $user->position->name .')</p>';    
                }

                return $text;
            })
            ->editColumn('budget_total', function($d) {
                return 'Rp. ' . number_format($d->budget_total, 0, '.', '.');
            })
            ->editColumn('status', function($d) {
                $class = '';
                $text = '';
                if ($d->status == 1) {
                    $class="themed-background-spring themed-color-white";
                    $text = __('view.approved');
                } else if ($d->status == 2) {
                    $class = "themed-background-autumn themed-color-white";
                    $text = __('view.waiting_approval');
                } else if ($d->status == 3) {
                    $class = "themed-background-amethyst themed-color-white";
                    $text = __('view.approved_wait_budget');
                } else if ($d->status == 4) {
                    $class = "themed-background-fire themed-color-white";
                    $text = __('view.reject');
                } else {
                    $class = "themed-background-night";
                    $text = __('view.draft');
                }
                return '<span class="label '. $class .'">'. $text .'</span>';
            })
            ->addColumn('action', function($data) {
                $action = view($this->vp . '.components.action', compact('data'))->render();
                return $action;
            })
            ->rawColumns(['event_date', 'pic', 'budget_total', 'status', 'action'])
            ->make(true);
    }

    /**
     * Function to download documents
     */
    public function download($document_id)
    {
        $data = ProposalDoc::find($document_id);
        return response()->download(public_path('storage/' . $data->path));
    }

    /**
     * Function to publish proposal
     * This function run if user click button 'publish' in list proposal view
     * @param int id
     */
    public function publish($id)
    {
        $data = Proposal::find($id);
        $data->status = Proposal::WAITING_APPROVAL;
        $data->save();

        return $this->success_response(__('view.proposal_published'));
    }

    /**
     * Function to approve proposal
     * @param int id
     * 
     * @return JsonResponse
     */
    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $this->service->approve($id);
            DB::commit();

            return $this->success_response(__('view.proposal_approved'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->error_response($th->getMessage());
        }
    }

    /**
     * Function to funding proposal that has been approved before
     * @param int id
     * @param int amount
     * @param int account
     * @param array attachments_funding
     * 
     * @return JsonResponse
     */
    public function funding(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->service->funding($request, $id);
            DB::commit();

            return $this->success_response(__('view.approved_and_fund'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->error_response($th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employees::active()->get();
        $view = view($this->vp . '.create', compact('employees'))->render();
        return $this->render_response($view);
    }

    public function uploadAttachments(Request $request)
    {
        if ($request->hasFile('attachments_proposal')) {
            $file = $request->file('attachments_proposal');
            $folder = uniqid().'-'.now()->timestamp;
            $ext = $file[0]->getClientOriginalExtension();
            $filename = 'temporary-'.now()->timestamp.'.'.$ext;
            $file[0]->storeAs('tmp/'.$folder, $filename, 'public');

            return $folder.'@@'.$filename.'@@'.'.'.$ext;
        }

        return '';
    }

    public function deleteAttachments(Request $request)
    {
        $content = $request->getContent();
        if ($content) {
            $folder = '';
            $exp = explode('@@', (string) $content);
            $folder = str_replace(' ', '', $exp[0]);
            $link = public_path('storage/tmp/'.$folder.'/'.$exp[1]);
            unlink($link);
            rmdir(public_path('storage/tmp/'.$folder));
        }

        return '';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'attachments_proposal' => 'required',
                    'budget_total' => 'required',
                    'event_date' => 'required',
                    'title' => 'required',
                    'message' => 'required',
                    'pic' => 'required',
                ],
                [
                    'attachments_proposal.required' => __('view.attachments_required'),
                    'budget_total.required' => __('view.budget_required'),
                    'event_date.required' => __('view.event_date_required'),
                    'title.required' => __('view.title_required'),
                    'message.required' => __('view.description_proposal_required'),
                    'pic.required' => __('view.pic_required'),
                ]
            );
    
            $this->service->store($request);
            DB::commit();

            return $this->success_response(__("view.proposal_stored"));
        } catch (\Throwable $th) {
            DB::rollBack();
            
            return $this->error_response($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Proposal::find($id);
        $docs = $data->docs;

        $view = view($this->vp . '.show', compact('data', 'docs'))->render();

        return $this->render_response($view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employees = Employees::active()->get();
        $proposal = Proposal::with('docs')->find($id);
        $pic = $proposal->pic;
        $docs = $proposal->docs;
        $doc_path = collect($docs)->pluck('path')->map(function ($item) {
            return asset('storage/' . $item);
        })->values()->toArray();
        $docs_path = implode(',', $doc_path);

        $employees = collect($employees)->map(function ($item) use ($pic) {
            $item['selected'] = '';

            if ($item->id == $pic) {
                $item['selected']  = 'selected';
            }

            return $item;
        })->all();

        $view = view($this->vp . '.create', compact('employees', 'proposal', 'docs_path'))->render();
        return $this->render_response($view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->service->update($request, $id);
            DB::commit();

            return $this->success_response(__('view.proposal_stored'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->error_response($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
