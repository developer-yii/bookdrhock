<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Model\PollCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Poll;

class PollCategoryController extends Controller
{
    public function category(Request $request)
    {
        if ($request->ajax()) {
            $category = PollCategory::all();
            return DataTables::of($category)
                ->escapeColumns([])
                ->toJson();
        }
        return view('admin.poll.category');
    }

    public function categoryCreateorupdate(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:poll_categories,slug,' . $request->id . ',id,deleted_at,NULL',
        ]);

        if (isset($request->id) && !empty($request->id)) {
            $model = PollCategory::find($request->id);
        } else {
            $model = new PollCategory();
        }

        $model->name = $request->name;
        $model->slug = $request->slug;
        $model->save();

        $message = "Poll category created successfully.";
        if (isset($request->id) && !empty($request->id)) {
            $message = "Poll category updated successfully.";
        }
        return response()->json(['code' => 200, 'message' => $message, 'data' => $model], 200);
    }

    public function getSlugUrl(Request $request)
    {
        if (isset($request->name) && !empty($request->name)) {
            $slug = Str::slug($request->name, '-');
            $checkCategorySlug = PollCategory::query()
                ->where('name', $request->name)
                ->pluck('slug', 'id')
                ->toArray();
            if (isset($checkCategorySlug) && !empty($checkCategorySlug)) {
                $count = count($checkCategorySlug) + 10;
                for ($i = 0; $i <= $count; $i++) {
                    if ($i == 0) {
                        if (!in_array($slug, $checkCategorySlug)) {
                            $checkCategorySlug = PollCategory::query()
                                ->where('slug', $slug)
                                ->where('deleted_at', '!=', null)
                                ->pluck('slug', 'id')
                                ->toArray();

                            if (isset($checkCategorySlug) && empty($checkCategorySlug)) {
                                $slug = $slug;
                                break;
                            }
                        }
                    } else {
                        if (!in_array($slug . '-' . $i, $checkCategorySlug)) {
                            $checkCategorySlug = PollCategory::query()
                                ->where('slug', $slug . '-' . $i)
                                ->pluck('slug', 'id')
                                ->toArray();

                            if (isset($checkCategorySlug) && empty($checkCategorySlug)) {
                                $slug = $slug . '-' . $i;
                                break;
                            }
                        }
                    }
                }
            }

            return response()->json(['code' => 200, 'message' => 'Found Slug', 'data' => $slug], 200);
        } else {
            return response()->json(['code' => 200, 'message' => 'Empty name', 'data' => []], 200);
        }
    }

    public function categoryDelete(Request $request)
    {
        PollCategory::find($request->id)->delete();
        return response()->json(['response' => 'success', 'message' => 'Poll category deleted successfully!']);
    }
}
