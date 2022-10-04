<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Model\Poll;
use App\Model\PollVote;
use App\Model\Codeblock;
use App\Rules\ReCaptcha;
use App\Model\PollOption;
use App\Model\PollCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Kmlpandey77\MathCaptcha\Captcha;

class PollController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $polls = Poll::query()
                ->leftJoin('poll_categories', 'poll_categories.id', '=', 'polls.category')
                ->select('polls.*', 'poll_categories.name as category_name')
                ->groupBy('polls.id')
                ->orderBy('start_datetime')
                ->get();

            return DataTables::of($polls)
                ->editColumn('category_name', function ($row) {
                    return isset($row->category_name) ? $row->category_name : "-";
                })
                ->editColumn('start_datetime', function ($row) {
                    return isset($row->start_datetime) ? getDateFormateView($row->start_datetime) : "";
                })
                ->editColumn('end_datetime', function ($row) {
                    return isset($row->end_datetime) ? getDateFormateView($row->end_datetime) : "";
                })
                ->escapeColumns([])
                ->toJson();
        }
        return view('admin.poll.index');
    }

    public function createForm(Request $request)
    {
        $categories = PollCategory::all();
        $captchaType = Poll::$capthcaType;
        return view('admin.poll.create', compact('categories', 'captchaType'));
    }

    public function editForm($id)
    {
        $poll = Poll::query()
            ->where('polls.id', $id)
            ->leftJoin('poll_options', 'poll_options.poll_id', '=', 'polls.id')
            ->groupBy('polls.id')
            ->select(
                'polls.*',
                DB::raw('group_concat(poll_options.id) as option_id'),
                DB::raw('group_concat(IFNULL(poll_options.title, "null")) as option_title'),
                DB::raw('group_concat(IFNULL(poll_options.image, "null")) as option_image')
            )
            ->first();

        $categories = PollCategory::all();
        $captchaType = Poll::$capthcaType;

        if (isset($poll) && !empty($poll)) {
            return view('admin.poll.edit', compact('categories', 'captchaType', 'poll'));
        } else {
            return redirect()->route('poll');
        }
    }

    public function view($slug)
    {
        $poll = Poll::query()
            ->where('polls.slug', $slug)
            ->leftJoin('poll_options', 'poll_options.poll_id', '=', 'polls.id')
            ->groupBy('polls.id')
            ->select(
                'polls.*',
                DB::raw('group_concat(poll_options.id) as option_id'),
                DB::raw('group_concat(IFNULL(poll_options.title, "null")) as option_title'),
                DB::raw('group_concat(IFNULL(poll_options.image, "null")) as option_image')
            )
            ->first();

        $categories = PollCategory::all();
        $captchaType = Poll::$capthcaType;

        if (Auth::user()) {
            $userrole = Auth::user()->user_role;
        } else {
            $userrole = '';
        }

        app('mathcaptcha')->reset();

        if (isset($poll) && !empty($poll)) {
            return view('admin.poll.view', compact('categories', 'captchaType', 'poll', 'userrole'));
        } else {
            return abort(404);
        }
    }

    public function embedView($slug)
    {
        $poll = Poll::query()
            ->where('polls.slug', $slug)
            ->leftJoin('poll_options', 'poll_options.poll_id', '=', 'polls.id')
            ->groupBy('polls.id')
            ->select(
                'polls.*',
                DB::raw('group_concat(poll_options.id) as option_id'),
                DB::raw('group_concat(IFNULL(poll_options.title, "null")) as option_title'),
                DB::raw('group_concat(IFNULL(poll_options.image, "null")) as option_image')
            )
            ->first();

        $categories = PollCategory::all();
        $captchaType = Poll::$capthcaType;

        app('mathcaptcha')->reset();

        if (isset($poll) && !empty($poll)) {
            return view('admin.poll.embedview', compact('categories', 'captchaType', 'poll'));
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

        $curruntVotes = PollVote::query()
            ->where('ip', $request->ip())
            ->where('poll_id', $request->id)
            ->where('created_at', '>', Carbon::now()->subHours($hours)->toDateTimeString())
            ->orderBy('created_at', 'DESC')
            ->get()
            ->count();

        if (isset($curruntVotes) && $curruntVotes < 1) {
            foreach (explode(',', $request->selected_options) as $option) {
                $model = new PollVote();
                $model->user_id = (Auth::user()) ? Auth::user()->id : null;
                $model->poll_id = $request->id;
                $model->ip = $request->ip();
                $model->poll_options = $option;
                $model->save();
            }

            $request->session()->flash('flash-poll-voted');

            return response()->json(['response' => 'success', 'message' => 'Your vote submitted successfully', 'data' => $model], 200);
        } else {
            return response()->json(['response' => 'error', 'message' => 'You can vote again after ' . $hours . ' hours', 'data' => $curruntVotes], 200);
        }
    }

    public function getPollOptions(Request $request)
    {
        if (isset($request->poll_id) && !empty($request->poll_id) && $request->ajax()) {
            $pollOptions = Poll::query()
                ->select(
                    'poll_options.*',
                    DB::raw("count(poll_votes.poll_options) as votes"),
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
            'start_datetime' => 'required',
            'end_datetime' => 'required',
            'feature_image' => 'image|mimes:jpg,png,jpeg',
            'option.*.title' => 'required',
            'option.*.image' => 'image|mimes:jpg,png,jpeg',
        ], [
            'option.*.title.required' => 'The title field is required.',
            'option.*.image.image' => 'The image must be an image.',
            'option.*.image.mimes' => 'The image must be a file of type: jpg, png, jpeg.',
        ]);

        if ((date('Y-m-d H:i', strtotime($request->start_datetime)) < date('Y-m-d H:i', strtotime($request->end_datetime)))) {

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
            $modelP->start_datetime = date('Y-m-d H:i', strtotime($request->start_datetime));
            $modelP->end_datetime = date('Y-m-d H:i', strtotime($request->end_datetime));
            $modelP->description = $request->description;
            $modelP->category = $request->category;
            $modelP->vote_schedule = $request->vote_schedule;
            $modelP->popular_tag = ($request->popular_tag == 'on') ? true : false;
            $modelP->captcha_type = $request->captcha_type;
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
        } else {
            return response()->json(['response' => 'error', 'message' => 'End date must be grater then start date!', 'errors' => ['end_datetime' => 'End date must be grater then start date.']], 400);
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
        return response()->json(['response' => 'success', 'message' => 'Poll deleted successfully!']);
    }
}
