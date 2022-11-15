<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Model\Poll;
use App\Model\PollVote;
use App\Rules\ReCaptcha;
use App\Model\PollOption;
use App\Model\PollCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class PollController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $polls = Poll::query()
                ->leftJoin('poll_categories', 'poll_categories.id', '=', 'polls.category')
                ->select('polls.*', 'poll_categories.name as category_name')
                ->groupBy('polls.id')
                ->orderBy('created_at', 'desc')
                ->get();

            return DataTables::of($polls)
                ->editColumn('category_name', function ($row) {
                    return isset($row->category_name) ? $row->category_name : "-";
                })
                ->editColumn('start_datetime', function ($row) {
                    return isset($row->start_datetime) ? getDateFormateView($row->start_datetime) : "-";
                })
                ->editColumn('end_datetime', function ($row) {
                    return isset($row->end_datetime) ? getDateFormateView($row->end_datetime) : "-";
                })
                ->escapeColumns([])
                ->toJson();
        }
        return view('admin.poll.index');
    }

    public function createForm(Request $request)
    {
        $categories = PollCategory::all();
        $recaptcha = Poll::$recaptcha;
        $voteHours = Poll::$voteHours;
        return view('admin.poll.create', compact('categories', 'recaptcha', 'voteHours'));
    }

    public function editForm($id)
    {
        $poll = Poll::query()
            ->select(
                'polls.*',
                'poll_options.id as option_id',
                'poll_options.title as option_title',
                'poll_options.image as option_image',
                DB::raw("(count(poll_votes.poll_options) + poll_options.admin_vote) as votes")
            )
            ->join('poll_options', 'poll_options.poll_id', '=', 'polls.id')
            ->leftJoin('poll_votes', 'poll_votes.poll_options', '=', 'poll_options.id')
            ->where('polls.id', $id)
            ->groupBy('poll_options.id')
            ->get();

        $categories = PollCategory::all();
        $recaptcha = Poll::$recaptcha;
        $voteHours = Poll::$voteHours;

        if (isset($poll) && !empty($poll)) {
            return view('admin.poll.edit', compact('categories', 'recaptcha', 'poll', 'voteHours'));
        } else {
            return redirect()->route('poll');
        }
    }

    public function view($slug)
    {
        $poll = Poll::query()
            ->where('slug', $slug)
            ->first();

        if (empty($poll))
            return abort(404);

        $poll_options = PollOption::query()
            ->where('poll_id', $poll->id)
            ->get()
            ->keyBy('id')
            ->toArray();

        // $poll_options = $poll_option_array;

        // $poll_voting = PollVote::query()
        //     ->select('poll_options', DB::raw('count(*) as count'))
        //     ->where('poll_id', $poll->id)
        //     ->groupBy('poll_options')
        //     ->get()
        //     ->pluck('count', 'poll_options')
        //     ->toArray();

        $poll_option_array = [];
        foreach ($poll_options as $list) {
            $poll_option_array[$list['id']] = $list['admin_vote'] + $list['user_vote_count'];
        }
        arsort($poll_option_array);

        $userrole = Auth::user() ? Auth::user()->user_role : '';
        $type = 'details';
        app('mathcaptcha')->reset();

        return view('admin.poll.view', compact('poll', 'userrole', 'type', 'poll_options', 'poll_option_array'));
    }

    public function viewResults($slug)
    {
        $poll = Poll::query()
            ->where('slug', $slug)
            ->first();

        if (empty($poll))
            return abort(404);

        $poll_options = PollOption::query()
            ->where('poll_id', $poll->id)
            ->get()
            ->keyBy('id')
            ->toArray();

        // $poll_voting = PollVote::query()
        //     ->select('poll_options', DB::raw('count(*) as count'))
        //     ->where('poll_id', $poll->id)
        //     ->groupBy('poll_options')
        //     ->get()
        //     ->pluck('count', 'poll_options')
        //     ->toArray();

        $poll_option_array = [];
        foreach ($poll_options as $list) {
            $poll_option_array[$list['id']] = $list['admin_vote']+$list['user_vote_count'];
        }
        arsort($poll_option_array);

        $userrole = Auth::user() ? Auth::user()->user_role : '';
        $type = 'results';

        return view('admin.poll.view', compact('poll', 'userrole', 'type', 'poll_options', 'poll_option_array'));
    }

    public function embedViewResults($slug)
    {
        $poll = Poll::query()
            ->where('slug', $slug)
            ->first();

        if (empty($poll))
            return abort(404);

        $poll_options = PollOption::query()
            ->where('poll_id', $poll->id)
            ->get()
            ->keyBy('id')
            ->toArray();

        // $poll_voting = PollVote::query()
        //     ->select('poll_options', DB::raw('count(*) as count'))
        //     ->where('poll_id', $poll->id)
        //     ->groupBy('poll_options')
        //     ->get()
        //     ->pluck('count', 'poll_options')
        //     ->toArray();

        $poll_option_array = [];
        foreach ($poll_options as $list) {
            $poll_option_array[$list['id']] = $list['admin_vote']+$list['user_vote_count'];
        }
        arsort($poll_option_array);

        $type = 'results';

        return view('admin.poll.embedview', compact('poll', 'type', 'poll_options', 'poll_option_array'));
    }

    public function votechangePollOptions(Request $request)
    {
        if (isset($request->id) && !empty($request->id) && isset($request->title) && !empty(isset($request->title))) {
            $request->validate([
                'vote' => 'required|numeric',
                'add_remove' => 'required'
            ]);

            $model = PollOption::find($request->id);
            $model->admin_vote = ($request->add_remove == 'add') ? ($model->admin_vote + $request->vote) : ($model->admin_vote - $request->vote);
            $model->save();

            return response()->json(['response' => 'success', 'message' => 'Option vote update successfully.',], 200);
        } else {
            return response()->json(['response' => 'error', 'message' => 'Something went wrong please reload!',], 200);
        }
    }

    public function embedView($slug)
    {
        $poll = Poll::query()
            ->where('slug', $slug)
            ->first();

        if (empty($poll))
            return abort(404);

        $poll_options = PollOption::query()
            ->where('poll_id', $poll->id)
            ->get()
            ->keyBy('id')
            ->toArray();

        // $poll_options = $poll_option_array;

        // $poll_voting = PollVote::query()
        //     ->select('poll_options', DB::raw('count(*) as count'))
        //     ->where('poll_id', $poll->id)
        //     ->groupBy('poll_options')
        //     ->get()
        //     ->pluck('count', 'poll_options')
        //     ->toArray();

        $poll_option_array = [];
        foreach ($poll_options as $list) {
            $poll_option_array[$list['id']] = $list['admin_vote'] + $list['user_vote_count'];
        }
        arsort($poll_option_array);

        $type = 'details';
        app('mathcaptcha')->reset();

        return view('admin.poll.embedview', compact('poll', 'type', 'poll_options', 'poll_option_array'));
    }

    public function Voting(Request $request)
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $clientIp = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $clientIp = $forward;
        } else {
            $clientIp = $remote;
        }

        $request->validate([
            'selected_options' => 'required',
            'mathcaptcha_ctm' => 'required_if:enabledmathcaptcha,==,"enabledmathcaptcha"',
            'g-recaptcha-response' => ['required_if:enabledgooglecaptcha,==,"enabledgooglecaptcha"', new ReCaptcha]
        ], [
            'mathcaptcha.mathcaptcha' => 'Your answer is wrong.',
            'mathcaptcha_ctm.required_if' => 'Please give answer.',
            'g-recaptcha-response.required_if' => 'Please valid google recaptcha.'
        ]);

        if (isset($request->enabledmathcaptcha) && !empty($request->enabledmathcaptcha)) {
            if (isset($request->match_captcha_firstnumb) && !empty($request->match_captcha_firstnumb) && isset($request->match_captcha_secoundnumb) && !empty($request->match_captcha_secoundnumb)) {
                if ($request->mathcaptcha_ctm != ($request->match_captcha_firstnumb + $request->match_captcha_secoundnumb))
                    return response()->json(['response' => 'error_matchcaptcha', 'errors' => ['mathcaptcha_ctm' => 'Your answer is wrong.'], 'data' => $request->all(), 'type' => $request->page_type], 400);
            } else {
                return response()->json(['response' => 'error_matchcaptcha', 'errors' => ['mathcaptcha_ctm' => 'something is wrong!'], 'data' => $request->all(), 'type' => $request->page_type], 400);
            }
        }

        $hours = 12;
        if (isset($request->vote_schedule) && !empty($request->vote_schedule)) {
            $hours = (int) $request->vote_schedule;
        }

        $voteAdd = 1;
        if (isset($request->vote_add) && !empty($request->vote_add)) {
            $voteAdd = (int) $request->vote_add;
        }

        $curruntVotes = PollVote::query()
            ->where('ip', $clientIp)
            ->where('poll_id', $request->id)
            ->where('created_at', '>', Carbon::now()->subHours($hours)->toDateTimeString())
            ->orderBy('created_at', 'DESC')
            ->groupBy('created_at')
            ->get()
            ->count();

        $currunt_date = Carbon::now()->toDateTimeString();
        $insert_array = array();
        $k = 0;
        if (isset($curruntVotes) && $curruntVotes < $voteAdd) {
            foreach (explode(',', $request->selected_options) as $option) {
                $insert_array[$k]['poll_id'] = $request->id;
                $insert_array[$k]['ip'] = $clientIp;
                $insert_array[$k]['poll_options'] = $option;
                $insert_array[$k]['created_at'] = $currunt_date;
                $insert_array[$k]['updated_at'] = $currunt_date;

                // $model = new PollVote();
                // $model->user_id = (Auth::user()) ? Auth::user()->id : null;
                // $model->poll_id = $request->id;
                // $model->ip = $clientIp;
                // $model->poll_options = $option;
                // $model->created_at = $currunt_date;
                // $model->save();
                $k++;
            }

            if (!empty($insert_array)) {
                PollVote::insert($insert_array);
                PollOption::whereIn('id',explode(',', $request->selected_options))->increment('user_vote_count');
            }

            // $request->session()->flash('flash-poll-voted');

            $view = $this->getResultView($request->id,$request->page_type);

            return response()->json(['response' => 'success', 'message' => 'Your vote submitted successfully', 'data' => $insert_array, 'slug' => $request->slug, 'type' => $request->page_type,'html'=>$view], 200);
        } else {
            // $request->session()->flash('flash-poll-votedone');
            session()->flash('flash-poll-votedone', 'You Have Completed Your Votes, vote again in ' . $hours . ' hours');
            $view = $this->getResultView($request->id,$request->page_type);

            return response()->json(['response' => 'votedone', 'message' => 'You\'ve completed your vote, vote again in ' . $hours . ' hours', 'slug' => $request->slug, 'type' => $request->page_type,'html'=>$view], 200);
        }
    }

    public function getResultView($id,$pagetype)
    {
        $poll = Poll::query()
            ->where('id', $id)
            ->first();

        $poll_options = PollOption::query()
            ->where('poll_id', $id)
            ->get()
            ->keyBy('id')
            ->toArray();

        $poll_option_array = [];
        foreach ($poll_options as $list) {
            $poll_option_array[$list['id']] = $list['admin_vote']+$list['user_vote_count'];
        }
        arsort($poll_option_array);

        $userrole = '';
        $type = 'results';

        $view = View::make('admin.poll.result_ajax', compact('poll', 'userrole', 'type', 'poll_options', 'poll_option_array','pagetype'))->render();
        return $view;
            
    }

    public function getPollOptions(Request $request)
    {
        if (isset($request->poll_id) && !empty($request->poll_id) && $request->ajax()) {
            $pollOptions = Poll::query()
                ->select(
                    'poll_options.*',
                    // DB::raw("(count(poll_votes.poll_options) + poll_options.admin_vote) as votes"),
                    DB::raw("(poll_options.user_vote_count + poll_options.admin_vote) as votes"),
                    'polls.slug as slug'
                )
                ->join('poll_options', 'poll_options.poll_id', '=', 'polls.id')
                ->leftJoin('poll_votes', 'poll_votes.poll_options', '=', 'poll_options.id')
                ->where('polls.id', $request->poll_id)
                ->groupBy('poll_options.id')
                ->get();

            return DataTables::of($pollOptions)
                ->editColumn('image', function ($row) {
                    return isset($row->image) ? $row->getImagePath($row->image, $row->slug, 'poll_options') : "-";
                })
                ->escapeColumns([])
                ->toJson();
        }
        die();
    }

    public function createorupdate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'feature_image' => 'image|mimes:jpg,png,jpeg,webp',
            'option.*.title' => 'required',
            'option.*.image' => 'image|mimes:jpg,png,jpeg,webp',
        ], [
            'option.*.title.required' => 'The title field is required.',
            'option.*.image.image' => 'The image must be an image.',
            'option.*.image.mimes' => 'The image must be a file of type: jpg, png, jpeg.',
        ]);

        if ((isset($request->end_datetime) && !empty($request->end_datetime) && !isset($request->start_datetime))) {
            return response()->json(['response' => 'error', 'message' => 'End date must be grater then start date!', 'errors' => ['start_datetime' => 'The start datetime field is required.']], 400);
        } elseif ((isset($request->start_datetime) && !empty($request->start_datetime) && !isset($request->end_datetime))) {
            return response()->json(['response' => 'error', 'message' => 'End date must be grater then start date!', 'errors' => ['end_datetime' => 'The end datetime field is required.']], 400);
        } elseif (isset($request->end_datetime) && !empty($request->end_datetime) && (date('Y-m-d H:i', strtotime($request->start_datetime)) > date('Y-m-d H:i', strtotime($request->end_datetime)))) {
            return response()->json(['response' => 'error', 'message' => 'End date must be grater then start date!', 'errors' => ['end_datetime' => 'End date must be grater then start date.']], 400);
        }

        $slug = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 10);

        if (isset($request->id) && !empty($request->id)) {
            $modelP = Poll::find($request->id);
            $slug = $modelP->slug;
            $fileName = $modelP->feature_image;
        } else {
            $modelP = new Poll();
            $fileName = '';
        }

        if (isset($fileName) && !empty($fileName) && !empty($modelP->getImageStoragePath($fileName, $slug, 'poll_feature_image')) && ((!isset($request->set_image) && empty($request->set_image)) || $request->hasFile('feature_image'))) {
            unlink($modelP->getImageStoragePath($fileName, $slug, 'poll_feature_image'));
            $fileName = '';
        }

        if ($request->hasFile('feature_image')) {
            $fileName = $request->feature_image->hashName();
            $request->feature_image->store('public/poll/' . $slug);
        }

        $modelP->title = $request->title;
        $modelP->slug = $slug;
        $modelP->start_datetime = isset($request->start_datetime) && !empty($request->start_datetime) ? date('Y-m-d H:i', strtotime($request->start_datetime)) : null;
        $modelP->end_datetime = isset($request->end_datetime) && !empty($request->end_datetime) ? date('Y-m-d H:i', strtotime($request->end_datetime)) : null;
        $modelP->description = $request->description;
        $modelP->category = $request->category;
        $modelP->vote_schedule = $request->vote_schedule;
        $modelP->popular_tag = ($request->popular_tag == 'on') ? true : false;
        $modelP->captcha_type = $request->captcha_type;
        $modelP->vote_add = $request->vote_add;
        $modelP->option_select = $request->option_select;
        $modelP->feature_image = $fileName;
        $modelP->save();

        if (isset($request->removed_options) && !empty($request->removed_options)) {
            $removeOptions = PollOption::query()
                ->where('poll_id', $modelP->id)
                ->whereIn('id', $request->removed_options)
                ->get();

            foreach ($removeOptions as $removeOption) {
                if (!empty($modelP->getImageStoragePath($removeOption->image, $slug, 'poll_options'))) {
                    unlink($modelP->getImageStoragePath($removeOption->image, $slug, 'poll_options'));
                }
            }

            PollOption::query()
                ->where('poll_id', $modelP->id)
                ->whereIn('id', $request->removed_options)
                ->delete();
        }

        foreach ($request->option as $option) {
            if (array_key_exists('option_id', $option) && isset($option['option_id']) && !empty($option['option_id'])) {
                $modelPO = PollOption::find($option['option_id']);
                $fileNameO = $modelPO->image;
            } else {
                $modelPO = new PollOption();
                $fileNameO = '';
            }

            if (isset($fileNameO) && !empty($fileNameO) && !empty($modelP->getImageStoragePath($fileNameO, $slug, 'poll_options')) && ((array_key_exists('image', $option) && isset($option['image']) && !empty($option['image'])) || (!isset($option['set_image']) && empty($option['set_image'])))) {
                unlink($modelP->getImageStoragePath($fileNameO, $slug, 'poll_options'));
                $fileNameO = '';
            }

            if (array_key_exists('image', $option) && isset($option['image']) && !empty($option['image'])) {
                $fileNameO = $option['image']->hashName();
                $option['image']->store('public/poll/' . $slug . '/option_images');
            }

            $modelPO->poll_id = $modelP->id;
            $modelPO->title = $option['title'];
            $modelPO->image = (isset($fileNameO) && !empty($fileNameO)) ? $fileNameO : null;
            $modelPO->save();
        }


        $message = "Poll created successfully.";
        if (isset($request->id) && !empty($request->id)) {
            $message = "Poll updated successfully.";
            $request->session()->flash('flash-poll-update');
        } else {
            $request->session()->flash('flash-poll-create');
        }
        return response()->json(['response' => 'success', 'message' => $message, 'data' => $modelP], 200);
    }

    public function delete(Request $request)
    {
        $model = Poll::find($request->id);
        $imagePath = public_path('storage/poll/' . $model->slug);
        if (File::exists($imagePath)) {
            File::deleteDirectory($imagePath);
        }
        $model->delete();
        PollOption::where('poll_id', $request->id)->delete();
        PollVote::where('poll_id', $request->id)->delete();
        return response()->json(['response' => 'success', 'message' => 'Poll deleted successfully!']);
    }

    // call widget-poll-list
    public function getlist($slug)
    {
        $poll = Poll::query()
            ->where('slug', $slug)
            ->first();

        if (empty($poll))
            return abort(404);

        $poll_options = PollOption::query()
            ->where('poll_id', $poll->id)
            ->get()
            ->keyBy('id')
            ->toArray();

        $poll_option_array = [];
        foreach ($poll_options as $list) {
            $poll_option_array[$list['id']] = $list['admin_vote'] + $list['user_vote_count'];
        }
        arsort($poll_option_array);

        $type = 'details';
        app('mathcaptcha')->reset();

        return view('admin.poll_widget.poll_list', compact('poll', 'type', 'poll_options', 'poll_option_array'));
    }
    public function getlistHtml($slug)
    {
        $poll = Poll::query()
            ->where('slug', $slug)
            ->first();

        if (empty($poll))
            return abort(404);

        $poll_options = PollOption::query()
            ->where('poll_id', $poll->id)
            ->get()
            ->keyBy('id')
            ->toArray();

        $poll_option_array = [];
        foreach ($poll_options as $list) {
            $poll_option_array[$list['id']] = $list['admin_vote'] + $list['user_vote_count'];
        }
        arsort($poll_option_array);

        $type = 'details';
        app('mathcaptcha')->reset();

        return view('admin.poll_widget.poll_list_html', compact('poll', 'type', 'poll_options', 'poll_option_array'));
    }

    // call widget add poll
    public function votingwidget(Request $request)
    {
        $widget_token = ($request->widget_token)? $request->widget_token:"";
        $poll_id = ($request->id)? $request->id:"";
        if (!verifyWidgetToken($widget_token,$poll_id)) {
            \Log::info("widget_token:".$widget_token);
            return response()->json(['message' => 'something was wrong please try again later!'], 400);
        }
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $clientIp = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $clientIp = $forward;
        } else {
            $clientIp = $remote;
        }

        $validator = Validator::make($request->all(), [
          'selected_options' => 'required',
            'mathcaptcha_ctm' => 'required_if:enabledmathcaptcha,==,"enabledmathcaptcha"',
            'g-recaptcha-response' => ['required_if:enabledgooglecaptcha,==,"enabledgooglecaptcha"', new ReCaptcha]
        ],[
            'mathcaptcha.mathcaptcha' => 'Your answer is wrong.',
            'mathcaptcha_ctm.required_if' => 'Please give answer.',
            'g-recaptcha-response.required_if' => 'Please valid google recaptcha.'
        ]);
        if (!$validator->passes())
        {
            $err=$validator->errors()->toArray();
            $data = [];
            foreach ($err as $key => $value)
            {
                $data[$key]=$value[0];
            }
            return response()->json(['errors'=>$data],400);
        }
        if (isset($request->enabledmathcaptcha) && !empty($request->enabledmathcaptcha)) {
            if (isset($request->match_captcha_firstnumb) && !empty($request->match_captcha_firstnumb) && isset($request->match_captcha_secoundnumb) && !empty($request->match_captcha_secoundnumb)) {
                if ($request->mathcaptcha_ctm != ($request->match_captcha_firstnumb + $request->match_captcha_secoundnumb))
                    return response()->json(['response' => 'error_matchcaptcha', 'errors' => ['mathcaptcha_ctm' => 'Your answer is wrong.'], 'data' => $request->all(), 'type' => $request->page_type], 400);
            } else {
                return response()->json(['response' => 'error_matchcaptcha', 'errors' => ['mathcaptcha_ctm' => 'something is wrong!'], 'data' => $request->all(), 'type' => $request->page_type], 400);
            }
        }

        $hours = 12;
        if (isset($request->vote_schedule) && !empty($request->vote_schedule)) {
            $hours = (int) $request->vote_schedule;
        }

        $voteAdd = 1;
        if (isset($request->vote_add) && !empty($request->vote_add)) {
            $voteAdd = (int) $request->vote_add;
        }

        $curruntVotes = PollVote::query()
            ->where('ip', $clientIp)
            ->where('poll_id', $request->id)
            ->where('created_at', '>', Carbon::now()->subHours($hours)->toDateTimeString())
            ->orderBy('created_at', 'DESC')
            ->groupBy('created_at')
            ->get()
            ->count();

        $currunt_date = Carbon::now()->toDateTimeString();
        $insert_array = array();
        $k = 0;
        if (isset($curruntVotes) && $curruntVotes < $voteAdd) {
            foreach (explode(',', $request->selected_options) as $option) {
                $insert_array[$k]['poll_id'] = $request->id;
                $insert_array[$k]['ip'] = $clientIp;
                $insert_array[$k]['poll_options'] = $option;
                $insert_array[$k]['created_at'] = $currunt_date;
                $insert_array[$k]['updated_at'] = $currunt_date;
                $k++;
            }
            if (!empty($insert_array)) {
                PollVote::insert($insert_array);
                PollOption::whereIn('id',explode(',', $request->selected_options))->increment('user_vote_count');
            }            
            return response()->json(['response' => 'success', 'message' => 'Your vote submitted successfully', 'data' => $insert_array, 'slug' => $request->slug, 'type' => $request->page_type], 200);
        } else {
            return response()->json(['response' => 'votedone', 'message' => 'You\'ve completed your vote, vote again in ' . $hours . ' hours', 'slug' => $request->slug, 'type' => $request->page_type], 200);
        }
    }
    public function getWidgetResultView($slug)
    {
        $pagetype = "embeded";
        $poll = Poll::query()
            ->where('slug', $slug)
            ->first();
        $poll_id = (isset($poll->id))? $poll->id:"";
        $poll_options = PollOption::query()
            ->where('poll_id', $poll_id)
            ->get()
            ->keyBy('id')
            ->toArray();        
        $poll_option_array = [];
        if(!empty($poll_options)){
            foreach ($poll_options as $list) {
                $poll_option_array[$list['id']] = $list['admin_vote']+$list['user_vote_count'];
            }
            arsort($poll_option_array);            
        }
        $userrole = '';
        $type = 'results';

        $view = View::make('admin.poll_widget.poll_result_ajax', compact('poll', 'userrole', 'type', 'poll_options', 'poll_option_array','pagetype'))->render();
        return response()->json(['response' => 'success','html' => $view], 200);
            
    }
}
