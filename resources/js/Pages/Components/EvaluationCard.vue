<template>
    <!-- Visualition de l'évaluation -->
        <button type="button" class="mb-3 p-2 rounded-lg bg-gray-700 text-white font-bold border-gray-700 focus:outline-none" v-if="showVisualizeEval" @click="toggleShowVisualizeEval"><i class="fas fa-reply"></i></button>
        <div class="mb-5 border-gray-700 border border-t-0">
            <h2 class="p-5 uppercase text-white bg-gray-700 text-center text-sm font-bold">{{evaluation.title}}</h2>
            <div v-if="evaluation.type == 'TEXT_HAS_GAPS'" class="mt-3 mx-auto text-center ">Évaluation de type : Texte à trous</div>
            <div v-else class="mt-3 mx-auto text-center">Évaluation de type : {{evaluation.type}}</div>
            <div class="mt-3 mb-5 w-3/4 mx-auto text-center italic">{{evaluation.desc}}</div>
            <div class="text mx-auto text-justify mb-10" style="width: 70%;">
	            <v-runtime-template :template="evaluation.text" :parent="parentComponent"></v-runtime-template>
            </div>

            <!-- Liste des participants-->
            <div v-if="evaluation.students_responses.length != 0">
            <h3 class="p-5 uppercase bg-gray-200 text-center text-xs font-bold mx-auto border-gray-700 border-t">Liste des participants</h3>
            <div class="flex flex-row">
                <div class="p-3 uppercase text-white bg-gray-600 text-center text-sm font-bold w-1/6 border-gray-100 border-r">Prénoms</div>
                <div class="p-3 uppercase text-white bg-gray-600 text-center text-sm font-bold w-1/6 border-r">Noms</div>
                <div class="p-3 uppercase text-white bg-gray-600 text-center text-sm font-bold w-1/5 border-r">Status</div>
                <div class="p-3 uppercase text-white bg-gray-600 text-center text-sm font-bold w-1/6 border-r">Note</div>
                <div class="p-3 uppercase text-white bg-gray-600 text-center text-sm font-bold flex-grow ">Actions</div>
            </div>

            <div class="text-gray-700">
                <div class="flex flex-row text-gray-700 text-sm items-center" v-for="students_response in evaluation.students_responses" :key="students_response">
                    <div class="p-2 w-1/6 border-gray-100 border-r border-b">{{students_response.student.firstname}}</div>
                    <div class="p-2 w-1/6 border-r border-b">{{students_response.student.lastname}}</div>
                    <div v-if="students_response.state == 'waiting'" class="p-2 w-1/5 border-r border-b">Aucune copie reçue</div>
                    <div v-else-if="students_response.state == 'awaiting_evaluation'" class="p-2 w-1/5 border-r border-b">En attente d'évaluation</div>
                    <div v-else-if="students_response.state == 'evaluated'" class="p-2 w-1/5 border-r border-b">Évalué</div>

                    <div v-if="students_response.rating != null " class="p-2 w-1/6 border-r border-b">{{students_response.rating}} / {{evaluation.evaluation_responses.length}}</div>
                    <div v-else class="p-2 w-1/6 border-r border-b">Aucune</div>
                    <span class="flex-grow border-r border-b flex justify-end p-1">
                        <div class="flex flex-row mr-3" style="width:fit-content">
                            <button type="button" class="mx-auto mr-3 block p-2 rounded-lg uppercase text-xs font-bold text-white bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowVisualizeEval(evaluation)"><i class="fas fa-eye"></i></button>
                        </div>
                    </span>                    
                </div>
            </div>
        </div>
        <div v-else>
            <h3 class="p-3 uppercase bg-gray-200 text-center text-xs font-bold mx-auto border-gray-700 border-t">Aucun participant</h3>
        </div>
        </div>
        <button v-if="canAskAutomaticCorrection" type="button" :class="{ 'opacity-25': processing }" :disabled="processing" class="mx-auto mb-5 mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="autoCorrection">
          <template v-if="processing">Correction automatique en cours...</template>
          <template v-else>Correction automatique</template>
        </button>

</template>

<script>
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Tiptap from '@/Pages/Components/Tiptap.vue'
import VRuntimeTemplate from "vue3-runtime-template";

    export default ({
        components: {
            JetInput, JetLabel, Tiptap, VRuntimeTemplate
        },
        props: {
            evaluation: Object, 
            showVisualizeEval: Boolean,
            toggleShowVisualizeEval: Function,
        },
        data() {
            return {
                evaluation: this.evaluation,
                showVisualizeEval: this.showVisualizeEval,
                parentComponent: this,
                canAskAutomaticCorrection: false, 
                formAutoCorrection: {
                    evaluation_id: this.evaluation.id,
                }
            }
        },
        mounted() {
            const states = this.evaluation.students_responses.map(el => {
                if(el.state == 'awaiting_evaluation') {
                  this.canAskAutomaticCorrection = true;
                }
            });
        },
        methods: { 
            toggleShowVisualizeEval() {
                this.$emit('toggleShowVisualizeEval', null)
            },
            autoCorrection() {
                self=this;
                this.processing = true;
                axios.post(route('evaluation_response.autoCorrection'), this.formAutoCorrection,
                {
                })
                .then((response) => {
                    if (response.status === 201) {
                        console.log(response.data.evaluation)
                        this.evaluation = response.data.evaluation;
                        //this.$page.props.evaluations = response.data.evaluations;
                        //this.$page.props.evaluationsResponses = response.data.evaluationsResponses;
                        this.canAskAutomaticCorrection = false;
                        this.processing = false;
                    } 
                    else {
                    alert("Une erreur est survenue");
                        this.processing = false;
                    }
                })
            }
            


        },


    })
</script>
<style>
    .border-orange-400 {
        border-color: #DC7633;
    }
    .tiptap {
   border: 1px solid darkgrey;
   margin-top: 0.5rem;
 }
</style>
