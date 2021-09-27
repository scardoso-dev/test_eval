<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Models\EvaluationsResponse;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * Fonction pour ajouter une nouvelle évaluation
     * @param Request $request Requête
     * @return Response Retourne une response avec un 'message', des informations base de données modifié préablement et un statut (200, 200, etc..)
     */
    public function add(Request $request) {
        $user = Auth::guard()->user();
        $eval = Evaluation::create([
            'creator_id' => $user->id,
            'type' => $request->type,
            'title' => $request->title,
            'desc' => $request->desc,
            'text' => $request->text,
            'students_concerned' => [],
            'evaluation_responses' => $request->responses,
        ]);
        $eval->save();
        if($eval) {
            $evaluations = Evaluation::where('creator_id', '=', $user->id)->get();
            return response(['message' => 'Évaluation ajoutée avec succès', 'eval' => $eval], 201);
        }
    }

    /**
     * Fonction pour assigner des étudiants à une évaluation
     * @param Request $request Requête
     * @return Response Retourne une response avec un 'message', des informations base de données modifié préablement et un statut (200, 200, etc..)
     */
    public function addStudents(Request $request) {
        $user = Auth::guard()->user();
        $eval = Evaluation::find($request->evaluation_id);
        if($eval) {            
            $eval->students_concerned = $request->input('students_concerned');
            $eval->save();
            foreach ($request->students_concerned as $student_id) {
                $evalResponse = EvaluationsResponse::where('evaluation_id', '=', $request->evaluation_id)->where('student_concerned_id', '=', $student_id)->first();
                if($evalResponse == null) {   
                    $evalResponse = EvaluationsResponse::create([
                        'evaluation_id' => $request->evaluation_id,
                        'student_concerned_id' => $student_id,
                        'responses' => $request->responses,
                    ]);
                    $evalResponse->save();
                }
            }
            // Suppression des students qui ne sont plus assigné à l'évaluation (en cas d'update)
            $evaluationsResponses = EvaluationsResponse::where('evaluation_id', '=', $request->evaluation_id)->get();
            foreach ($evaluationsResponses as $evaluationsResponse) {
                if(!in_array($evaluationsResponse->student_concerned_id, $eval->students_concerned)) {
                    $evaluationsResponse->delete();
                }
            }
            // --------------------------- //

            $evaluations = Evaluation::where('creator_id', '=', $user->id)->with('students_responses')->get();
            return response(['message' => 'Étudiants ajoutés avec succès', 'evaluations' => $evaluations], 201);
        }
    }

    /**
     * Fonction pour modifier une évaluation, les étudiants liés à l'évalution se retrouve avec une response à cette dernière réinitialisée 
     * @param Request $request Requête
     * @return Response Retourne une response avec un 'message', des informations base de données modifié préablement et un statut (200, 200, etc..)
     */ 
    public function updateEval(Request $request) {
        $user = Auth::guard()->user();
        $eval = Evaluation::find($request->evaluation_id);
        if($eval && $eval->creator_id == $user->id) {
            $eval->type = $request->type;
            $eval->title = $request->title;
            $eval->desc = $request->desc;
            $eval->text = $request->text;
            $eval->evaluation_responses = $request->responses;
            $eval->save();
        }
        $evaluations = Evaluation::where('creator_id', '=', $user->id)->with('students_responses')->get();
        return response(['message' => 'Modification apportées avec succès', 'evaluations' => $evaluations], 201);
    }

    /**
     * Fonction pour supprimer une évaluation et les responses des étudiants
     * @param Request $request Requête
     * @return Response Retourne une response avec un 'message', des informations base de données modifié préablement et un statut (200, 200, etc..)
     */     
    public function deleteEval(Request $request) {
        $user = Auth::guard()->user();
        $evaluation = Evaluation::find($request->id);
        if($evaluation) {
            EvaluationsResponse::where('evaluation_id', '=', $evaluation->id)->delete();
            $evaluation->delete();
            return response(['message' => 'Évaluation supprimée avec succès'], 201);
        }
    }

 
}
