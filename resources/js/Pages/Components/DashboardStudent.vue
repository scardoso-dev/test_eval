<template>
    <!-- Modal pour passer l'examen -->
    <modal :show="showAddResponse">
        <div class="mb-5">
            <h2 class="p-3 uppercase text-white bg-gray-700 text-center text-sm font-bold">Passer l'examen</h2>
            <form> 
                <h3 class="p-1 mt-3 uppercase text-gray-700 text-center text-xs font-bold">{{formAddResponse.title}}</h3>
                <p class="p-3 text-center text-sm text-gray-700 italic">{{formAddResponse.desc}}</p>
                <span v-if="formAddResponse.type == 'TEXT_HAS_GAPS'" class="flex flex-col mt-5 w-full p-5 mx-auto items-center">
                    <div class="text-md text-gray-700 text-justify">
		                <v-runtime-template :template="formAddResponse.text" :parent="parentComponent"></v-runtime-template>
                    </div>
                </span>
            </form>
        </div>
        <div class="flex flex-row mx-auto" style="width:fit-content">
            <button type="button" class="mx-auto mr-3 mb-5 mt-3 block p-3 uppercase text-xs font-bold text-gray-700 border border-dotted border-gray-700 bg-white hover:bg-gray-700 hover:text-white hover:border-border-none focus:outline-none" @click="showAddResponse = false">Annuler</button>        
            <button type="button" :class="{ 'opacity-25': processing }" :disabled="processing" class="mx-auto mb-5 mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="addEval">
                <template v-if="processing">Envoi en cours...</template>
                <template v-else>Envoyer mes réponses</template>
            </button>  
        </div>                

    </modal>

    <div v-if="evaluationsResponses != null">
        <h2 class="uppercase font-bold text-gray-700 text-md text-center">Liste des évaluations</h2>
            <div class="evaluations-list flex flex-col mx-auto">
                <div class="flex flex-row w-full mt-3 text-sm uppercase">
                    <span class="w-1/4 p-3 bg-gray-700 text-white font-bold border-r border-gray-100 text-center">Titre de l'évaluation</span>
                    <span class="w-1/5 p-3 bg-gray-700 text-white font-bold border-r text-center">Status</span>
                    <span class="w-1/6 p-3 bg-gray-700 text-white font-bold border-r text-center">Note</span>
                    <span class="w-1/6 p-3 bg-gray-700 text-white font-bold border-r text-center">Moyenne générale</span>
                    <span class="p-3 bg-gray-700 text-white font-bold text-center flex-grow">Actions</span>
                </div>
                <div v-for="evaluationsResponse, index in evaluationsResponses" :key="evaluationsResponse">
                    <div class="flex flex-row w-full text-sm">
                        <span class="p-3 w-1/4 border-r border-l border-b flex items-center">{{evaluationsResponse.evaluation.title}}</span>
                        <span v-if="evaluationsResponse.state == 'waiting'" class="p-3 w-1/5 border-r border-b flex items-center">À faire</span>
                        <span v-if="evaluationsResponse.state == 'awaiting_evaluation'" class="p-3 w-1/5 border-r border-b flex items-center">En attente d'évaluation</span>
                        <span v-if="evaluationsResponse.state == 'evaluated'" class="p-3 w-1/5 border-r border-b flex items-center">Évalué</span>

                        <span class="w-1/6 p-3 border-r border-b flex items-center " v-if="evaluationsResponse.rating != null">{{evaluationsResponse.rating}} / {{evaluationsResponse.evaluation.evaluation_responses.length}}</span>
                        <span v-else class="w-1/6 p-3 border-r border-b flex items-center ">Indéfini</span>
                        
                        <span class="w-1/6 p-3 border-r border-b flex items-center ">Non disponible</span>

                        <span class="border-r border-b flex justify-end flex-grow">
                            <div class="flex flex-row mr-3" style="width:fit-content">
                                <button v-if="evaluationsResponse.state == 'waiting' " type="button" class="mx-auto m-3 mr-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowAddResponse(evaluationsResponse,index)">Passer le test</button>
                                <button v-else-if="evaluationsResponse.state == 'awaiting_evaluation'" type="button" class="mx-auto m-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowAddResponse(evaluationsResponse, index)">Modifier mes responses</button>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
    </div>
    <div v-else>
        <h2 class="uppercase font-bold text-gray-700 text-md text-center">Aucun examen disponible</h2>
    </div>
    <!--
    <button type="button" class="mx-auto mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowAddEval">Ajouter une nouvelle évaluation</button>
    -->
</template>

<script>
import Modal from '@/Jetstream/Modal.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Tiptap from '@/Pages/Components/Tiptap.vue'
import VRuntimeTemplate from "vue3-runtime-template";

    export default ({
        components: {
            Modal, JetInput, JetLabel, Tiptap, VRuntimeTemplate
        },
        props: [
            'evaluationsResponses'
        ],
        data() {
            return {
                user: this.$page.props.user,
                evaluationsResponses: this.evaluationsResponses,
                showAddResponse: false,
                processing: false,
                pos: 0,
                parentComponent: this,
                formAddResponse: {
                    evaluation_id: '',
                    title: '',
                    desc: '',
                    type: '',
                    text: '',
                    student_concerned_id: '',
                    responses: [],
                },
                options: [
                {text: 'Texte à trous', value: 'TEXT_HAS_GAPS'},
                ],
            }
        },
        mounted() {
            console.log(this.formAddResponse.responses)
        },
        methods: {
            // Méthode pour ajouter un mot ou une expression
            addWord(index) {
                const value = this.formAddResponse.responses[index].value;
                if(value == '' || value == null) {
                    this.formAddResponse.responses[index].showButtonAdd = !this.formAddResponse.responses[index].showButtonAdd;
                }
                else {
                    this.formAddResponse.responses[index].showButtonUpdate = !this.formAddResponse.responses[index].showButtonUpdate;     
                }
                this.formAddResponse.responses[index].showInput = !this.formAddResponse.responses[index].showInput;
                
                //this.evaluationsResponses[index].responses[index] = value;
                //this.formAddResponse.responses[index].value = value;

            },
            // Méthode pour envoyer sa réponse à l'évaluation au serveur
            addEval() {
                this.processing = true;
                const old_responses = this.formAddResponse.responses;
                let responses = [];
                this.formAddResponse.responses.forEach(response => {
                    responses.push(response.value);
                });
                this.formAddResponse.responses = responses;
                axios.post(route('evaluation_response.add'), this.formAddResponse,
                {
                })
                .then((response) => {
                    if (response.status === 201) {
                        this.evaluationsResponses = response.data.evaluationsResponses;
                        this.processing = false;
                        this.showAddResponse = false;
                    } 
                    else {
                    alert("Une erreur est survenue");
                        this.processing = false;
                        this.showAddResponse = false;
                    }
                })         
                        
            },
            // Gestion de la modal avec préremplissage des champs dans le formulaire 
            toggleShowAddResponse(evaluationsResponse,index) {
                this.formAddResponse.evaluation_id = evaluationsResponse.evaluation.id;
                this.formAddResponse.student_concerned_id = this.user.id;
                this.formAddResponse.title = evaluationsResponse.evaluation.title;
                this.formAddResponse.desc = evaluationsResponse.evaluation.desc;
                this.formAddResponse.type = evaluationsResponse.evaluation.type;
                this.formAddResponse.text = evaluationsResponse.evaluation.text;
                var old_responses = evaluationsResponse.responses;
                
                console.log(this.formAddResponse.responses)
                // Parcours des responses pour les extraires du texte et insérer un bouton
                this.evaluationsResponses[index].evaluation.evaluation_responses.forEach(response => {
                    if(this.evaluationsResponses[index].responses[this.pos] == '' || this.evaluationsResponses[index].responses[this.pos] == null) {
                        this.formAddResponse.responses[this.pos] = {showInput : false, showButtonAdd: true, showButtonUpdate: false, value : ""};
                    }
                    else {
                        this.formAddResponse.responses[this.pos] = {showInput : false, showButtonAdd: false, showButtonUpdate: true, value : old_responses[this.pos]};
                    }
                    // Pour les expressions
                    if(response.includes(' ')) {
                        // On ajoute la position à la réponse, pour différencier les doublons de (bonnes) réponses.
                        this.formAddResponse.text = this.formAddResponse.text.replace('<strong>'+response+'</strong>', '<strong>'+response+this.pos+'</strong>');
                        this.formAddResponse.text = this.formAddResponse.text.split('<strong>'+response+this.pos+'</strong>').join("<input v-on:keydown.enter.prevent='addWord("+this.pos+")' v-show='formAddResponse.responses["+this.pos+"].showInput' type='text' v-model='formAddResponse.responses["+this.pos+"].value'></input><button v-show='formAddResponse.responses["+this.pos+"].showButtonAdd' class='p-1 bg-red-700 text-white font-bold focus:outline-none' type='button' v-on:click='addWord("+this.pos+")'>Expression</button><button v-show='formAddResponse.responses["+this.pos+"].showButtonUpdate' class='p-1 bg-blue-700 text-white font-bold focus:outline-none' type='button' v-on:click='addWord("+this.pos+")'>{{formAddResponse.responses["+this.pos+"].value}}</button><button v-show='formAddResponse.responses["+this.pos+"].showInput' type='button' class='font-bold p-1 uppercase text-white text-xs bg-gray-700 focus:outline-none' v-on:click='addWord("+this.pos+")' style='padding:11px;'>Ajouter</button>"); 
                    }
                    // Pour les mots
                    else {
                        // On ajoute la position à la réponse, pour différencier les doublons de (bonnes) réponses.
                        this.formAddResponse.text = this.formAddResponse.text.replace('<strong>'+response+'</strong>', '<strong>'+response+this.pos+'</strong>');
                        this.formAddResponse.text = this.formAddResponse.text.split('<strong>'+response+this.pos+'</strong>').join("<input id="+this.pos+" v-on:keydown.enter.prevent='addWord("+this.pos+")' v-show='formAddResponse.responses["+this.pos+"].showInput' type='text' v-model='formAddResponse.responses["+this.pos+"].value'></input><button v-show='formAddResponse.responses["+this.pos+"].showButtonAdd' class='p-1 bg-red-700 text-white font-bold focus:outline-none' type='button' v-on:click='addWord("+this.pos+")'>Mot</button><button v-show='formAddResponse.responses["+this.pos+"].showButtonUpdate' class='p-1 bg-blue-700 text-white font-bold focus:outline-none' type='button' v-on:click='addWord("+this.pos+")'>{{formAddResponse.responses["+this.pos+"].value}}</button><button v-show='formAddResponse.responses["+this.pos+"].showInput' type='button' class='font-bold p-1 uppercase text-white text-xs bg-gray-700 focus:outline-none' v-on:click='addWord("+this.pos+")' style='padding:11px'>Ajouter</button>"); 
                    }
                    this.pos++;
                });
                this.pos = 0;
                this.showAddResponse = !this.showAddResponse;

            },
            
        },

    })
</script>
<style>
    .tiptap {
        border: 1px solid darkgrey;
         margin-top: 0.5rem;
    }
</style>