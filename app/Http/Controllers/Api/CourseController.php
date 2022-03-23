<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\User;

class CourseController extends Controller
{
    //COURSE ENROLLMENT API - POST
    public function courseEnrollment(Request $request) {
        $request->validate([
            "title" => "required",
            "description" => "required",
            "total_videos" => "required"
        ]);

        $course = new Course();
        $course->user_id = auth()->user()->id;
        $course->title = $request->title;
        $course->description = $request->description;
        $course->total_videos = $request->total_videos;

        $course->save();

        return response()->json([
            "status" => 1,
            "message" => "Course enrolled succesfully"
        ]);
    }

    // Total course enrolllment API - GET
    public function totalCourses() {

        $id = auth()->user()->id;
        $courses = User::find($id)->courses;

        return response()->json([
            "status" => 1,
            "message" => "Total Courses enrolled",
            "data" => $courses
        ]);
    }

    //Delete COURSE API - GET
    public function deleteCourse($id) {
        $user_id = auth()->user()->id;

        if(Course::where([
            "id" => $id,
            "user_id" => $user_id
        ])->exists()) {
            $course = Course::find($id);

            $course->delete();

            return response()->json([
                "status" => 1,
                "message" => "Course deleted succesfully"
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Course not found"
            ], 404);
        }
    }

    //GET ALL COURSES
    public function all() {
        $courses = Course::all();
        return response()->json([
            "data" => $courses
        ]);
    }
}
