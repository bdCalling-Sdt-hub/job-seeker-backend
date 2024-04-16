<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Interest;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    // profile section

    public function addProfileInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone_number' => 'nullable|string',
            'nid_number' => 'nullable|string',
            'gender' => 'nullable|string',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $profileInfo = new Candidate();
        $profileInfo->user_id = auth()->user()->id;
        $profileInfo->phone_number = $request->phone_number;
        $profileInfo->nid_number = $request->nid_number;
        $profileInfo->gender = $request->gender;
        $profileInfo->present_address = $request->present_address;
        $profileInfo->permanent_address = $request->permanent_address;
        $profileInfo->save();
        if ($profileInfo){
            return response()->json([
                'message' => 'Candidate Profile information added successfully',
                'data' => $profileInfo,
            ]);
        }else{
            return response()->json([
                'message' => 'Something Went Wrong'
            ],402);
        }
    }

    public function updateProfileInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone_number' => 'nullable|string',
            'nid_number' => 'nullable|string',
            'gender' => 'nullable|string',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Find the candidate by user_id
        $candidate = Candidate::where('user_id', $request->user_id)->first();

        // If candidate not found, return error
        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate not found'
            ], 404);
        }

        // Update candidate information
        $candidate->phone_number = $request->phone_number ?? $candidate->phone_number;
        $candidate->nid_number = $request->nid_number ?? $candidate->nid_number;
        $candidate->gender = $request->gender ?? $candidate->gender;
        $candidate->present_address = $request->present_address ?? $candidate->present_address;
        $candidate->permanent_address = $request->permanent_address ?? $candidate->permanent_address;
        $candidate->save();

        // Check if the update was successful
        if ($candidate->wasChanged()) {
            return response()->json([
                'message' => 'Candidate profile information updated successfully',
                'data' => $candidate,
            ]);
        } else {
            return response()->json([
                'message' => 'No changes detected in candidate pro'
            ]);
        }

    }
    // education section
    public function addExperienceInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'job_title' => 'required|string',
            'company_name' => 'required|string',
            'location' => 'required|string',
            'working_status' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'responsibility' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create a new Experience instance
        $experience = new Experience();
        $experience->user_id = $request->user_id;
        $experience->job_title = $request->job_title;
        $experience->company_name = $request->company_name;
        $experience->location = $request->location;
        $experience->working_status = $request->working_status;
        $experience->date_from = $request->date_from;
        $experience->date_to = $request->date_to;
        $experience->responsibility = $request->responsibility;
        $experience->save();

        return response()->json([
            'message' => 'Experience information added successfully',
            'data' => $experience,
        ]);
    }

    public function updateExperienceInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'experience_id' => 'required',
            'job_title' => 'nullable|string',
            'company_name' => 'nullable|string',
            'location' => 'nullable|string',
            'working_status' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'responsibility' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Find the experience by user_id and experience_id
        $experience = Experience::where('user_id', $request->user_id)
            ->where('id', $request->experience_id)
            ->first();

        // If experience not found, return error
        if (!$experience) {
            return response()->json([
                'message' => 'Experience not found'
            ], 404);
        }

        // Update experience information
        $experience->job_title = $request->job_title ?? $experience->job_title;
        $experience->company_name = $request->company_name ?? $experience->company_name;
        $experience->location = $request->location ?? $experience->location;
        $experience->working_status = $request->working_status ?? $experience->working_status;
        $experience->date_from = $request->date_from ?? $experience->date_from;
        $experience->date_to = $request->date_to ?? $experience->date_to;
        $experience->responsibility = $request->responsibility ?? $experience->responsibility;
        $experience->save();

        return response()->json([
            'message' => 'Experience information updated successfully',
            'data' => $experience,
        ]);
    }

    // educational qualification section

    public function addEducationInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'education_level' => 'required|string',
            'institution' => 'required|string',
            'result' => 'nullable|string',
            'result_type' => 'nullable|string',
            'passing_year' => 'required|integer|min:1900|max:' . date('Y'),
            'duration' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create a new Education instance
        $education = new Education();
        $education->user_id = $request->user_id;
        $education->education_level = $request->education_level;
        $education->institution = $request->institution;
        $education->result = $request->result;
        $education->result_type = $request->result_type;
        $education->passing_year = $request->passing_year;
        $education->duration = $request->duration;
        $education->save();

        return response()->json([
            'message' => 'Education information added successfully',
            'data' => $education,
        ]);
    }
    public function updateEducationInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'education_id' => 'required',
            'education_level' => 'nullable|string',
            'institution' => 'nullable|string',
            'result' => 'nullable|string',
            'result_type' => 'nullable|string',
            'passing_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'duration' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Find the education record by user_id and education_id
        $education = Education::where('user_id', $request->user_id)
            ->where('id', $request->education_id)
            ->first();

        // If education record not found, return error
        if (!$education) {
            return response()->json([
                'message' => 'Education information not found'
            ], 404);
        }

        // Update education information
        $education->education_level = $request->education_level ?? $education->education_level;
        $education->institution = $request->institution ?? $education->institution;
        $education->result = $request->result ?? $education->result;
        $education->result_type = $request->result_type ?? $education->result_type;
        $education->passing_year = $request->passing_year ?? $education->passing_year;
        $education->duration = $request->duration ?? $education->duration;
        $education->save();

        return response()->json([
            'message' => 'Education information updated successfully',
            'data' => $education,
        ]);
    }

    //Training Section

    public function addTrainingInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'training_title' => 'required|string',
            'training_topic' => 'required|string',
            'institute_name' => 'required|string',
            'location' => 'required|string',
            'duration' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'responsibility' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create a new Training instance
        $training = new Training();
        $training->user_id = $request->user_id;
        $training->training_title = $request->training_title;
        $training->training_topic = $request->training_topic;
        $training->institute_name = $request->institute_name;
        $training->location = $request->location;
        $training->duration = $request->duration;
        $training->date_from = $request->date_from;
        $training->date_to = $request->date_to;
        $training->responsibility = $request->responsibility;
        $training->save();

        return response()->json([
            'message' => 'Training information added successfully',
            'data' => $training,
        ]);
    }
    public function updateTrainingInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'training_id' => 'required',
            'training_title' => 'nullable|string',
            'training_topic' => 'nullable|string',
            'institute_name' => 'nullable|string',
            'location' => 'nullable|string',
            'duration' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'responsibility' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Find the training by user_id and training_id
        $training = Training::where('user_id', $request->user_id)
            ->where('id', $request->training_id)
            ->first();

        // If training not found, return error
        if (!$training) {
            return response()->json([
                'message' => 'Training not found'
            ], 404);
        }

        // Update training information
        $training->training_title = $request->training_title ?? $training->training_title;
        $training->training_topic = $request->training_topic ?? $training->training_topic;
        $training->institute_name = $request->institute_name ?? $training->institute_name;
        $training->location = $request->location ?? $training->location;
        $training->duration = $request->duration ?? $training->duration;
        $training->date_from = $request->date_from ?? $training->date_from;
        $training->date_to = $request->date_to ?? $training->date_to;
        $training->responsibility = $request->responsibility ?? $training->responsibility;
        $training->save();

        return response()->json([
            'message' => 'Training information updated successfully',
            'data' => $training,
        ]);
    }

    // Job Interest Section
    public function addInterestInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'work_type' => 'nullable|string',
            'work_category' => 'nullable|string',
            'work_shift' => 'nullable|string',
            'expected_pay' => 'nullable|string',
            'area' => 'nullable|string',
            'current_salary' => 'nullable|string',
            'job_title' => 'nullable|string',
            'job_type' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create a new Interest instance
        $interest = new Interest();
        $interest->user_id = $request->user_id;
        $interest->work_type = $request->work_type;
        $interest->work_category = $request->work_category;
        $interest->work_shift = $request->work_shift;
        $interest->expected_salary = $request->expected_salary;
        $interest->area = $request->area;
        $interest->current_salary = $request->current_salary;
        $interest->job_title = $request->job_title;
        $interest->job_type = $request->job_type;
        $interest->save();

        return response()->json([
            'message' => 'Interest information added successfully',
            'data' => $interest,
        ]);
    }

    public function updateInterestInfo(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'interest_id' => 'required',
            'work_type' => 'nullable|string',
            'work_category' => 'nullable|string',
            'work_shift' => 'nullable|string',
            'expected_pay' => 'nullable|string',
            'area' => 'nullable|string',
            'current_salary' => 'nullable|string',
            'job_title' => 'nullable|string',
            'job_type' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Find the interest by user_id and interest_id
        $interest = Interest::where('user_id', $request->user_id)
            ->where('id', $request->interest_id)
            ->first();

        // If interest not found, return error
        if (!$interest) {
            return response()->json([
                'message' => 'Interest not found'
            ], 404);
        }

        // Update interest information
        $interest->work_type = $request->work_type ?? $interest->work_type;
        $interest->work_category = $request->work_category ?? $interest->work_category;
        $interest->work_shift = $request->work_shift ?? $interest->work_shift;
        $interest->expected_pay = $request->expected_pay ?? $interest->expected_pay;
        $interest->area = $request->area ?? $interest->area;
        $interest->current_salary = $request->current_salary ?? $interest->current_salary;
        $interest->job_title = $request->job_title ?? $interest->job_title;
        $interest->job_type = $request->job_type ?? $interest->job_type;
        $interest->save();

        return response()->json([
            'message' => 'Interest information updated successfully',
            'data' => $interest,
        ]);
    }

    public function getProfileInfo()
    {
        $auth_user = auth()->user()->id;
        $profileInfo = User::with('candidate','education','experience','training','interest')->where('id',$auth_user)->get();
        $formatted_profileInfo = $profileInfo->map(function($profile){
            return $profile;
        });
        return response()->json([
            'message' => 'success',
            'data' => $formatted_profileInfo
        ]);
    }
}
