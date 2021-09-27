<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Models\EvaluationsResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Function renvoyant les éléments necessaire pour le dahsboard, selon le rôle de l'utilisateur connecté
     * @param Request $request Requête
     * @return Inertia Renvoi un rendu inertia avec des données 
     */
    public function index(Request $request) {
        $user = Auth::guard()->user();
        if($user->hasRole('ROLE_TEACHER')) {
            $evaluations = Evaluation::where('creator_id', '=', $user->id)->with('students_responses')->get();

            
            /*
            $prestataires = UserService::with("user_service_ratings")->withCount(['user_service_ratings as average_rating' => function($query) use($sortDirection) {
                $query->select(UserServiceRating::raw('coalesce(avg(note),0)'));
              }])->orderBy('average_rating', $sortDirection)->get();
              */
              /*
            $evaluations = Evaluation::where('creator_id', '=', $user->id)->with('students_responses')->withCount(['students_responses as student' => function ($query) {
                //$query->select(EvaluationsResponse::with('student'));
            }])->get();
        */
            
            $students = User::all()->where('roles', '=', ['ROLE_STUDENT']);
            //dd($students);
            return Inertia::render("Dashboard", compact('evaluations', 'students'));
        }
        elseif ($user->hasRole('ROLE_STUDENT')) {
            //$evaluations = Evaluation::where('students_concerned', 'LIKE', '%'.$user->id.'%')->with('responses')->get();
            $evaluationsResponses = EvaluationsResponse::where('student_concerned_id', '=', $user->id)->with('evaluation')->get();
            return Inertia::render("Dashboard", compact('evaluationsResponses'));
        }
    }
}
