<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Validator;
use Storage;
use Image;

class TeamController extends Controller
{
    private $limit;
    private $ADMIN_ROUTE_NAME;
    private $currentUrl;


    public function __construct()
    {
        $this->limit = 20;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request)
    {
        $data = [];
        $limit = $this->limit;

        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : "";
        $from = isset($request->from) ? $request->from : "";
        $to = isset($request->to) ? $request->to : "";
        $company_id = isset($request->company_id) ? $request->company_id : "";

        $from_date = CustomHelper::DateFormat($from, "Y-m-d", "d/m/Y");
        $to_date = CustomHelper::DateFormat($to, "Y-m-d", "d/m/Y");

        $team_query = Team::orderBy("created_at", "desc");

        if (!empty($name)) {
            $team_query->where("name", "like", "%" . $name . "%");
        }

        if (strlen($status) > 0) {
            $team_query->where("status", $status);
        }

        if (is_numeric($company_id) && $company_id > 0) {
            $team_query->whereHas("teamDomains", function ($query) use (
                $company_id
            ) {
                $query->where("table_to_domains.domain_id", $company_id);
            });
        }

        if (!empty($from_date)) {
            $team_query->whereRaw('DATE(created_at) >= "' . $from_date . '"');
        }

        if (!empty($to_date)) {
            $team_query->whereRaw('DATE(created_at) <= "' . $to_date . '"');
        }
        //prd($blogs->toArray());
        $teams = $team_query->paginate($limit);

        $data["teams"] = $teams;

        return view("admin.teams.index", $data);
    }

    public function add(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $team = "";
        $title = "Add Team";
        $user = auth()->user();

        if (is_numeric($id) && $id > 0) {
            $team = Team::find($id);
            $teamName = "";
            if (!empty($team->name)) {
                $teamName = $team->name;
            }
            $title = "Edit Team(" . $teamName . " )";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            //prd($request->toArray());

            $ext = "jpg,jpeg,png,gif";

            $rules["name"] = "required";
            $rules["status"] = "required";
            $rules["image"] = "nullable|image|mimes:" . $ext;

            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "image",
                "back_url",
                "old_image",
                "id",
                "pdf",
                "old_pdf",
                "domain",
            ]);

            $slug = CustomHelper::GetSlug("teams", "id", $id, $request->name);

            $req_data["slug"] = $slug;

            //prd($req_data);
            if (!empty($team) && count($team) > 0) {
                $isSaved = Team::where("id", $team->id)->update($req_data);
                $msg = "Team has been updated successfully.";

                $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "teams";
                $row_id = $id;
                $action_type = "Edit On Team";
                $action_description = "Edit On (" . $request->title . ")";
                $description = "Update(" . $request->title . ") " . $description;

            } else {
                $isSaved = Team::create($req_data);
                $id = $isSaved->id;
                $msg = "Team has been added successfully.";

                $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "teams";
                $row_id = $id;
                $action_type = "Add On Team";
                $action_description = "Add On (" . $request->title . ")";
                $description = "Add(" . $request->title . ") " . $description;
            }

            if ($isSaved) {
                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImage($file, $id);
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }

                cache()->forget("teams");

                CustomHelper::recordActionLog(
                    $function_name,
                    $action_table,
                    $row_id,
                    $action_type,
                    $action_description,
                    $description
                );

                return redirect(url($this->ADMIN_ROUTE_NAME . "/teams"))->with(
                    "alert-success",
                    $msg
                );
            } else {
                return back()->with(
                    "alert-danger",
                    "The Team could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["team"] = $team;
        $data["id"] = $id;

        return view("admin.teams.form", $data);
    }

    public function saveImage($file, $id)
    {
        //prd($file);
        //echo $id; die;

        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "teams/";
            $thumb_path = "teams/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings("TEAM_IMG_HEIGHT");
            $IMG_WIDTH = CustomHelper::WebsiteSettings("TEAM_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings("TEAM_THUMB_HEIGHT");
            $THUMB_WIDTH = CustomHelper::WebsiteSettings("TEAM_THUMB_WIDTH");

            $IMG_WIDTH = !empty($IMG_WIDTH) ? $IMG_WIDTH : 768;
            $IMG_HEIGHT = !empty($IMG_HEIGHT) ? $IMG_HEIGHT : 768;
            $THUMB_WIDTH = !empty($THUMB_WIDTH) ? $THUMB_WIDTH : 336;
            $THUMB_HEIGHT = !empty($THUMB_HEIGHT) ? $IMG_WIDTH : 336;

            $uploaded_data = CustomHelper::UploadImage(
                $file,
                $path,
                $ext = "",
                $IMG_WIDTH,
                $IMG_HEIGHT,
                $is_thumb = true,
                $thumb_path,
                $THUMB_WIDTH,
                $THUMB_HEIGHT
            );

            if ($uploaded_data["success"]) {
                $new_image = $uploaded_data["file_name"];

                if (is_numeric($id) && $id > 0) {
                    $team = Team::find($id);

                    if (!empty($team) && count($team) > 0) {
                        $storage = Storage::disk("public");

                        $old_image = $team->image;

                        $team->image = $new_image;

                        $isUpdated = $team->save();

                        if ($isUpdated) {
                            if (
                                !empty($old_image) &&
                                $storage->exists($path . $old_image)
                            ) {
                                $storage->delete($path . $old_image);
                            }

                            if (
                                !empty($old_image) &&
                                $storage->exists($thumb_path . $old_image)
                            ) {
                                $storage->delete($thumb_path . $old_image);
                            }
                        }
                    }
                }
            }

            if (!empty($uploaded_data)) {
                return $uploaded_data;
            }
        }
    }

    public function ajax_delete_image(Request $request)
    {
        //prd($request->toArray());
        $result["success"] = false;

        $image_id = $request->has("image_id") ? $request->image_id : 0;

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_images($image_id);
            if ($is_img_deleted) {
                $result["success"] = true;
                $result["msg"] =
                    '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Team image has been delete successfully.</div>';
            }
        }

        if ($result["success"] == false) {
            $result["msg"] =
                '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "teams/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $team = Team::find($id);

                $function_name = $this->currentUrl;
                $action_table = "teams";
                $row_id = $id;
                $action_type = "Delete Team";
                $action_description = "Delete (" . $team->title . ")";
                $description = "Delete (" . $team->title . ")";
                //prd($event->eventToDomains->toArray());

                if (!empty($team) && count($team) > 0) {
                    if (!empty($team->image)) {
                        $image = $team->image;
                        if (
                            !empty($image) &&
                            $storage->exists($path . "thumb/" . $image)
                        ) {
                            $is_deleted = $storage->delete(
                                $path . "thumb/" . $image
                            );
                        }
                        if (
                            !empty($image) &&
                            $storage->exists($path . $image)
                        ) {
                            $is_deleted = $storage->delete($path . $image);
                        }
                    }

                    if (!empty($team->pdf)) {
                        $pdf = $team->pdf;
                        if (
                            !empty($pdf) &&
                            $storage->exists($path . "pdf/" . $pdf)
                        ) {
                            $is_deleted = $storage->delete(
                                $path . "pdf/" . $pdf
                            );
                        }
                        if (!empty($pdf) && $storage->exists($path . $pdf)) {
                            $is_deleted = $storage->delete($path . $pdf);
                        }
                    }

                    $team->teamToDomains()->delete();

                    $is_deleted = $team->delete();
                }
            }
        }

        if ($is_deleted) {

            CustomHelper::recordActionLog(
                $function_name,
                $action_table,
                $row_id,
                $action_type,
                $action_description,
                $description
            );
            //$event->eventToDomains()->detach();

            return redirect(url($this->ADMIN_ROUTE_NAME . "/teams"))->with(
                "alert-success",
                "The Team has been deleted successfully."
            );
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/teams"))->with(
                "alert-danger",
                "The Team cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function delete_images($id)
    {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "teams/";
        $team = Team::find($id);

        $image = isset($team->image) ? $team->image : "";
        if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
            $is_deleted = $storage->delete($path . "thumb/" . $image);
        }
        if (!empty($image) && $storage->exists($path . $image)) {
            $is_deleted = $storage->delete($path . $image);
        }
        if ($is_deleted) {
            $team->image = "";
            $is_updated = $team->save();
        }
        return $is_updated;
    }
    /* end of controller */
}
