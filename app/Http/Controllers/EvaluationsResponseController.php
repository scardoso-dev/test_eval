<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Models\EvaluationsResponse;
use Illuminate\Support\Facades\Auth;

class EvaluationsResponseController extends Controller
{
    /**
     * Fonction permettant d'ajouter une nouvelle réponse à une évaluation
     * @param Request $request Requête
     * @return Response Retourne une response avec un 'message', des informations base de données modifié préablement et un statut (200, 200, etc..)
     */
    public function add(Request $request) {
        $user = Auth::guard()->user();

        $evalResponse = EvaluationsResponse::where('evaluation_id','=', $request->evaluation_id)->where('student_concerned_id', '=', $user->id)->first();
        if($evalResponse != null) {            
            $evalResponse->responses = $request->input('responses');
            $evalResponse->state = 'awaiting_evaluation';
            $evalResponse->save();
            $evaluationsResponses = EvaluationsResponse::where('student_concerned_id', '=', $user->id)->with('evaluation')->get();
            return response(['message' => 'Réponse à l\'évaluation envoyé avec succès', 'evaluationsResponses' => $evaluationsResponses], 201);
        }
        
    }
    
    /**
     * Fonction permettant de corriger automatique les responses à l'évaluation (seulement si le statut est en attente d'évaluation)
     * @param Request $request Requête
     * @return Response Retourne une response avec un 'message', des informations base de données modifié préablement et un statut (200, 200, etc..)
     */
    public function autoCorrection(Request $request) {
        $user = Auth::guard()->user();
        $evaluation = Evaluation::find($request->evaluation_id);

        if($evaluation && $evaluation->creator_id == $user->id) {
            $evaluation_responses = $evaluation->evaluation_responses; 
            $responses = EvaluationsResponse::where('evaluation_id', '=', $evaluation->id)->where('state', '=', 'awaiting_evaluation')->get();
            foreach ($responses as $evaluationResponse) {
                $rating = 0;
                $student_responses = $evaluationResponse->responses;
                $student_concerned_id = $evaluationResponse->student_concerned_id;
                for ($i=0; $i < count($student_responses); $i++) { 
                    $response_student = stringWithoutAccentsTolower($student_responses[$i]);
                    $response_teacher = stringWithoutAccentsTolower($evaluation_responses[$i]);
                    if($response_teacher == $response_student) {
                        $rating++;
                    }
                }
                $evalResponse = EvaluationsResponse::where('student_concerned_id', '=', $student_concerned_id)->where('evaluation_id', '=', $evaluation->id)->first();
                $evalResponse->rating = $rating;
                $evalResponse->state = 'evaluated';
                $evalResponse->save();
            }
            $evaluation = Evaluation::where('id', '=', $request->evaluation_id)->with('students_responses')->first();
            $evaluations = Evaluation::where('creator_id', '=', $user->id)->with('students_responses')->get();
            $evaluationsResponses = EvaluationsResponse::where('evaluation_id', '=', $request->evaluation_id)->with('evaluation')->get();
            return response(['message' => 'Correction automatique fait avec succès', 'evaluation' => $evaluation], 201);
        }
    }
    

}
    
// Remplace tous les accents par leur équivalent sans accent.
    function stringWithoutAccentsTolower(String $chaine)
    {
        //$array_to_string = implode('',$array); 
        $string_without_spaces = str_replace(' ','',$chaine);
        $string_without_accents= strtr($string_without_spaces, "ÀÁÂàÄÅàáâàäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏ" ."ìíîïÙÚÛÜùúûüÿÑñ","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
        return strtolower($string_without_accents);
    } 