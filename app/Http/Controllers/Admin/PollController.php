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
            ->select(
                'polls.*',
                'poll_options.id as option_id',
                'poll_options.title as option_title',
                'poll_options.image as option_image',
                DB::raw("(count(poll_votes.poll_options) + poll_options.admin_vote) as votes")
            )
            ->join('poll_options', 'poll_options.poll_id', '=', 'polls.id')
            ->leftJoin('poll_votes', 'poll_votes.poll_options', '=', 'poll_options.id')
            ->where('polls.slug', $slug)
            ->groupBy('poll_options.id')
            ->get();

        $userrole = Auth::user() ? Auth::user()->user_role : '';
        $type = 'details';
        app('mathcaptcha')->reset();

        if (isset($poll) && !empty($poll)) {
            return view('admin.poll.view', compact('poll', 'userrole', 'type'));
        } else {
            return abort(404);
        }
    }

    public function viewResults($slug)
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
            ->where('polls.slug', $slug)
            ->groupBy('poll_options.id')
            ->orderBy('votes', 'desc')
            ->get();

        $userrole = Auth::user() ? Auth::user()->user_role : '';
        $type = 'results';
        app('mathcaptcha')->reset();

        if (isset($poll) && !empty($poll)) {
            return view('admin.poll.view', compact('poll', 'userrole', 'type'));
        } else {
            return abort(404);
        }
    }

    public function embedViewResults($slug)
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
            ->where('polls.slug', $slug)
            ->groupBy('poll_options.id')
            ->orderBy('votes', 'desc')
            ->get();

        $userrole = Auth::user() ? Auth::user()->user_role : '';
        $type = 'results';
        app('mathcaptcha')->reset();

        if (isset($poll) && !empty($poll)) {
            return view('admin.poll.embedview', compact('poll', 'userrole', 'type'));
        } else {
            return abort(404);
        }
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
            ->select(
                'polls.*',
                'poll_options.id as option_id',
                'poll_options.title as option_title',
                'poll_options.image as option_image',
                DB::raw("(count(poll_votes.poll_options) + poll_options.admin_vote) as votes")
            )
            ->join('poll_options', 'poll_options.poll_id', '=', 'polls.id')
            ->leftJoin('poll_votes', 'poll_votes.poll_options', '=', 'poll_options.id')
            ->where('polls.slug', $slug)
            ->groupBy('poll_options.id')
            ->get();

        $categories = PollCategory::all();

        app('mathcaptcha')->reset();

        if (isset($poll) && !empty($poll)) {
            return view('admin.poll.embedview', compact('categories', 'poll'));
        } else {
            return abort(404);
        }
    }

    public function Voting(Request $request)
    {
        $request->validate([
            'selected_options' => 'required',
            'mathcaptcha' => 'required_if:enabledmathcaptcha,==,"enabledmathcaptcha"|mathcaptcha',
            'g-recaptcha-response' => ['required_if:enabledgooglecaptcha,==,"enabledgooglecaptcha"', new ReCaptcha]
        ], [
            'mathcaptcha.mathcaptcha' => 'Your answer is wrong.',
            'mathcaptcha.required_if' => 'Please give answer.',
            'g-recaptcha-response.required_if' => 'Please valid google recaptcha.'
        ]);

        $hours = 12;
        if (isset($request->vote_schedule) && !empty($request->vote_schedule)) {
            $hours = (int) $request->vote_schedule;
        }

        $voteAdd = 1;
        if (isset($request->vote_add) && !empty($request->vote_add)) {
            $voteAdd = (int) $request->vote_add;
        }

        $curruntVotes = PollVote::query()
            ->where('ip', $request->ip())
            ->where('poll_id', $request->id)
            ->where('created_at', '>', Carbon::now()->subHours($hours)->toDateTimeString())
            ->orderBy('created_at', 'DESC')
            ->get()
            ->count();

        if (isset($curruntVotes) && $curruntVotes <= $voteAdd) {
            foreach (explode(',', $request->selected_options) as $option) {
                $model = new PollVote();
                $model->user_id = (Auth::user()) ? Auth::user()->id : null;
                $model->poll_id = $request->id;
                $model->ip = $request->ip();
                $model->poll_options = $option;
                $model->save();
            }

            $request->session()->flash('flash-poll-voted');

            return response()->json(['response' => 'success', 'message' => 'Your vote submitted successfully', 'data' => $model, 'slug' => $request->slug, 'type' => $request->page_type], 200);
        } else {
            return response()->json(['response' => 'error', 'message' => 'You can vote again after ' . $hours . ' hours', 'data' => $curruntVotes, 'type' => $request->page_type], 200);
        }
    }

    public function getPollOptions(Request $request)
    {
        if (isset($request->poll_id) && !empty($request->poll_id) && $request->ajax()) {
            $pollOptions = Poll::query()
                ->select(
                    'poll_options.*',
                    DB::raw("(count(poll_votes.poll_options) + poll_options.admin_vote) as votes"),
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
            'feature_image' => 'image|mimes:jpg,png,jpeg',
            'option.*.title' => 'required',
            'option.*.image' => 'image|mimes:jpg,png,jpeg',
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
}
