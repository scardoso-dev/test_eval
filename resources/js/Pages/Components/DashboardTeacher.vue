<template>
    <!-- Modal pour ajouter une évaluation -->
    <modal :show="showAddEval">
        <div class="mb-5">
            <h2 class="p-3 uppercase text-white bg-gray-700 text-center text-sm font-bold">Ajout d'une nouvelle évaluation</h2>
            <form>
                <span class="flex flex-col mt-3 w-2/3 mx-auto items-center">
                    <jet-label for="title" value="Titre de l'évaluation" />
                    <jet-input id="title" type="text" class="mt-1 block w-full" v-model="form.title" required />
                </span>

                <span class="flex flex-col mt-5 w-2/3 mx-auto items-center">
                    <jet-label for="desc" value="Description de l'évaluation" />
                    <textarea id="desc" type="text" class="mt-1 w-full h-24 px-3 py-2 text-base text-gray-700 placeholder-gray-600 border rounded-lg focus:shadow-outline" v-model="form.desc" required />
                </span>

                <span class="flex flex-col mt-5 w-2/3 mx-auto items-center">
                    <jet-label for="type" value="Type d'évaluation" />
                    <select v-model="form.type" class="mt-1 block w-full ">
                        <option disabled value="">Choisissez le type d'évaluation</option>
                        <option v-for="option in options" :key="option" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </span>
                <span v-if="form.type == 'TEXT_HAS_GAPS'" class="flex flex-col mt-5 w-2/3 mx-auto items-center">
                    <jet-label for="text" value="Texte de l'évaluation" />
                    <div class="tiptap mt-1 w-full">
                    <tiptap :min_height="300"></tiptap>
                    </div>
                </span>
            </form>
        </div>
        <div class="flex flex-row mx-auto" style="width:fit-content">
            <button type="button" class="mx-auto mr-3 mb-5 mt-3 block p-3 uppercase text-xs font-bold text-gray-700 border border-dotted border-gray-700 bg-white hover:bg-gray-700 hover:text-white hover:border-border-none focus:outline-none" @click="showAddEval = false">Annuler</button>        
            <button type="button" :class="{ 'opacity-25': processing }" :disabled="processing" class="mx-auto mb-5 mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="addEval">
                <template v-if="processing">Ajout en cours...</template>
                <template v-else>Ajouter</template>
            </button>  
        </div>
    </modal>

    <!-- Modal pour modifier une évaluation -->
    <modal :show="showUpdateEval">
        <div class="mb-5">
            <h2 class="p-3 uppercase text-white bg-gray-700 text-center text-sm font-bold">Modification d'une évaluation</h2>
            <form>
                <span class="flex flex-col mt-3 w-2/3 mx-auto items-center">
                    <jet-label for="title" value="Titre de l'évaluation" />
                    <jet-input id="title" type="text" class="mt-1 block w-full" v-model="formUpdateEval.title" required />
                </span>

                <span class="flex flex-col mt-5 w-2/3 mx-auto items-center">
                    <jet-label for="desc" value="Description de l'évaluation" />
                    <textarea id="desc" type="text" class="mt-1 w-full h-24 px-3 py-2 text-base text-gray-700 placeholder-gray-600 border rounded-lg focus:shadow-outline" v-model="formUpdateEval.desc" required />
                </span>

                <span class="flex flex-col mt-5 w-2/3 mx-auto items-center">
                    <jet-label for="type" value="Type d'évaluation" />
                    <select v-model="formUpdateEval.type" class="mt-1 block w-full ">
                        <option disabled value="">Choisissez le type d'évaluation</option>
                        <option v-for="option in options" :key="option" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </span>
                <span v-if="formUpdateEval.type == 'TEXT_HAS_GAPS'" class="flex flex-col mt-5 w-2/3 mx-auto items-center">
                    <jet-label for="text" value="Texte de l'évaluation" />
                    <div class="tiptap mt-1 w-full">
                        <tiptap :content="formUpdateEval.text" :min_height="300"></tiptap>
                    </div>
                </span>
            </form>
        </div>

        <div class="flex flex-row mx-auto" style="width:fit-content">
            <button type="button" class="mx-auto mr-3 mb-5 mt-3 block p-3 uppercase text-xs font-bold text-gray-700 border border-dotted border-gray-700 bg-white hover:bg-gray-700 hover:text-white hover:border-border-none focus:outline-none" @click="showUpdateEval = false">Annuler</button>        
            <button type="button" :class="{ 'opacity-25': processing }" :disabled="processing" class="mx-auto mb-5 mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="updateEval">
                <template v-if="processing">Modification en cours...</template>
                <template v-else>Modifier</template>
            </button>  
        </div>
    </modal>

    <!-- Modal pour supprimer une évaluation -->
    <modal :show="showDeleteEval">
        <div class="mb-5">
            <h2 class="p-3 uppercase text-white bg-gray-700 text-center text-sm font-bold">Suppression d'une évaluation</h2>
            <div class="p-3 text-center font-bold text-red-700">Attention ! En supprimant cette évaluation toutes les réponses des étudiants liées à cet examen seront supprimées</div>
            <div class="mt-5 mx-auto text-gray-700 text-center flex flex-col"><span class="text-xs uppercase font-bold mb-1">Titre de l'évaluation : </span><span>{{formDeleteEval.title}}</span></div>
            <div v-if="formDeleteEval.number_students_concerned == 0" class="mt-3 text-gray-700 text-center">Aucun participant pour cet examen</div>
            <div v-else-if="formDeleteEval.number_students_concerned == 1" class="mt-3 text-gray-700 text-center">1 étudiant est affecté à cet examen</div>
            <div v-else class="mt-3 text-gray-700 text-center">{{formDeleteEval.number_students_concerned}} étudiants sont affectés à cet examen</div>

        </div>
        <div class="flex flex-row mx-auto" style="width:fit-content">
            <button type="button" :class="{ 'opacity-25': processing }" :disabled="processing" class="mx-auto mb-5 mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-red-700 hover:bg-red-600 focus:outline-none" @click="deleteEval">
                <template v-if="processing">Suppression en cours...</template>
                <template v-else>Supprimer</template>
            </button>  
            <button type="button" class="mx-auto ml-3 mb-5 mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="showDeleteEval = false">Annuler</button>        
        </div>

    </modal>
    <!-- Modal pour ajouter des étudiants à l'évaluation -->
    <modal :show="showAddStudent">
        <div class="mb-5">
            <h2 class="p-3 uppercase text-white bg-gray-700 text-center text-sm font-bold">Affecter des étudiants à l'évaluation</h2>
            <form>
                <!--
                <h3 class="p-3 uppercase text-gray-700 text-center text-xs font-bold">Affecter des étudiants à l'évaluation</h3>
                -->
                <span class="flex flex-col mt-5 w-2/3 mx-auto">
                    <jet-label class="mt-1 text-base text-gray-700 font-bold text-center mb-3" for="students" :value="'Cochez les étudiants que vous voulez affecter :'" />
                    <div v-for="student in students" :key="student">
                        <jet-input v-if="formAddStudent.students_concerned.includes(student.id)" name="students" type="checkbox" :value="student.id" class="mx-auto mr-2" checked @click="selected(student.id)" />
                        <jet-input v-else name="students" type="checkbox" :value="student.id" class="mx-auto mr-2" @click="selected(student.id)" />
                        <span>{{student.firstname}} {{student.lastname}}</span>
                    </div>       

                </span>
            </form>
        </div>
        <div class="flex flex-row mx-auto" style="width:fit-content">
            <button type="button" class="mx-auto mr-3 mb-5 mt-3 block p-3 uppercase text-xs font-bold text-gray-700 border border-dotted border-gray-700 bg-white hover:bg-gray-700 hover:text-white hover:border-border-none focus:outline-none" @click="showAddStudent = false">Annuler</button>        
            <button type="button" :class="{ 'opacity-25': processing }" :disabled="processing" class="mx-auto mb-5 mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="addStudents">
                <template v-if="processing">Affectation en cours...</template>
                <template v-else>Affecter</template>
            </button>  
        </div>        
    </modal>


    <!-- Visualition de l'évaluation -->
    <evaluation-card v-if="showVisualizeEval" :showVisualizeEval="showVisualizeEval" :evaluation="evaluation_visualize"  @toggle-show-visualize-eval="toggleShowVisualizeEval"></evaluation-card>

    <!-- Contenu de la page -->
    <div v-if="!showVisualizeEval && evaluations.length != 0" class="content">

    <h2 class="uppercase font-bold text-gray-700 text-md text-center">Liste des évaluations</h2>
    <div class="evaluations-list flex flex-col mx-auto text-gray-700 text-sm">
        <div class="flex flex-row w-full mt-3">
            <span class="w-1/4 p-3 bg-gray-700 text-white font-bold border-r border-gray-100 text-center uppercase text-sm">Titre de l'évaluation</span>
            <span class="w-1/6 p-3 bg-gray-700 text-white font-bold border-r text-center uppercase text-sm">Type d'évaluation</span>
            <span class="w-1/6 p-3 bg-gray-700 text-white font-bold border-r text-center uppercase text-sm">Participants</span>
            <span class="w-1/6 p-3 bg-gray-700 text-white font-bold border-r text-center uppercase text-sm">Moyenne</span>
            <span class="flex-grow p-3 bg-gray-700 text-white font-bold text-center uppercase text-sm">Actions</span>
        </div>
        <div v-for="evaluation, index in evaluations" :key="evaluation">
            <div class="flex flex-row w-full">
                <span class="p-3 w-1/4 border-r border-l border-b flex items-center">{{evaluation.title}}</span>
                <span v-if="evaluation.type == 'TEXT_HAS_GAPS'" class="p-3 w-1/6 border-r border-b flex items-center">Texte à trous</span>
                <span class="p-3 w-1/6 border-r border-b flex items-center">{{evaluation.students_concerned.length}}</span>
                <span class="p-3 w-1/6 border-r border-b flex items-center">x / {{evaluation.evaluation_responses.length}}</span>
                
                <span class="flex-grow border-r border-b flex justify-end ">
                    <div class="flex flex-row mr-3" style="width:fit-content">
                        <button type="button" class="mx-auto m-3 mr-3 block p-3 rounded-lg uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowAddStudent(evaluation.id, index)"><i class="fas fa-user-plus"></i></button>
                        <button type="button" class="mx-auto m-3 mr-3 block p-3 rounded-lg uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowUpdateEval(evaluation, index)"><i class="fas fa-edit"></i></button>
                        <button type="button" class="mx-auto m-3 mr-3 block p-3 rounded-lg uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowVisualizeEval(evaluation)"><i class="fas fa-eye"></i></button>
                        <button type="button" class="mx-auto m-3 block p-3 rounded-lg uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowDeleteEval(evaluation.id,index, evaluation.title, evaluation.students_concerned.length)"><i class="fas fa-trash"></i></button>
                        
                        <!--
                        <button type="button" class="mx-auto m-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowAddEval"><i class="fas fa-ellipsis-v"></i></button>
                        -->
                    </div>

                </span>
            </div>
        </div>
    </div>


    <button type="button" class="mx-auto mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowAddEval">Ajouter une nouvelle évaluation</button>
    </div>
        <div v-show="!showVisualizeEval && evaluations.length == 0" class="content">
        <h2 class="uppercase font-bold text-gray-700 text-md text-center">Vous n'avez aucune évaluations</h2>
            <button type="button" class="mx-auto mt-3 block p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none" @click="toggleShowAddEval">Ajouter une nouvelle évaluation</button>

    </div>
</template>

<script>
import Modal from '@/Jetstream/Modal.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Tiptap from '@/Pages/Components/Tiptap.vue'
import EvaluationCard from '@/Pages/Components/EvaluationCard.vue'
    export default ({
        components: {
            Modal, JetInput, JetLabel, Tiptap, EvaluationCard
        },
        props: {
            evaluations: Object,
            students: Object,
        },
        data() {
            return {
                evaluations: this.evaluations,
                evaluation_visualize: null,
                students: this.students,
                showAddEval: false,
                showUpdateEval: false,
                showDeleteEval: false,
                showAddStudent: false,
                showVisualizeEval: false,
                processing: false,
                form: {
                    title: '',
                    desc: '',
                    type: '',
                    text: '',
                    responses: [],
                },
                formUpdateEval: {
                    title: '',
                    desc: '',
                    type: '',
                    text: '',
                    responses: [],
                    index: null,
                    evaluation_id: null,
                },
                formDeleteEval: {
                    id: null,
                    index: null,
                    title: '',
                    number_students_concerned: null,
                },
                formAddStudent: {
                    evaluation_id: '',
                    students_concerned: [],
                    responses: [],
                },

                options: [
                {text: 'Texte à trous', value: 'TEXT_HAS_GAPS'},
            ],
            }
        },
        methods: {
            // Gestion des modals avec préremplissage des formulaires avant envoi au serveur
            toggleShowVisualizeEval(evaluation) {
                if(evaluation != null) {
                    this.evaluation_visualize = evaluation;
                }
                this.showVisualizeEval = !this.showVisualizeEval;
            },
            toggleShowAddEval() {
                this.showAddEval = !this.showAddEval;
            },
            toggleShowUpdateEval(evaluation, index) {
                this.formUpdateEval.title = evaluation.title;
                this.formUpdateEval.desc = evaluation.desc;
                this.formUpdateEval.type = evaluation.type;
                this.formUpdateEval.text = evaluation.text;
                this.formUpdateEval.responses = evaluation.responses;
                this.formUpdateEval.index = index;
                this.formUpdateEval.evaluation_id = evaluation.id;
                this.showUpdateEval = !this.showUpdateEval;
            },
            toggleShowDeleteEval(evaluation_id, index, title, number_students_concerned) {
                this.formDeleteEval.id = evaluation_id;
                this.formDeleteEval.index = index;
                this.formDeleteEval.title = title;
                this.formDeleteEval.number_students_concerned = number_students_concerned;
                this.showDeleteEval = !this.showDeleteEval;
            },
            toggleShowAddStudent(eval_id, index) {
                if(this.showAddStudent == false) {
                    this.formAddStudent.evaluation_id = eval_id;
                    this.formAddStudent.students_concerned = this.evaluations[index].students_concerned;
                    for (let i = 0; i < this.evaluations[index].evaluation_responses.length; i++) {
                        this.formAddStudent.responses.push('');
                    } 
                }
                this.showAddStudent = !this.showAddStudent;
            },
            // -------------------------------------------------------------------------- //

            // Méthode permettant d'ajouter une nouvelle évaluation
            addEval() {
                // Récupération du contenu de tiptap pour pouvoir l'insérer dans la variable 'text' du formulaire
                var editor = document.getElementsByClassName('contentEditor')[0];
                var content = editor.getAttribute('content');
                this.form.text = content.toString();
                // ----------------------------------------------- //

                // On insère les responses dans un tableau (Le text des balises strong)
                var responses = document.createElement('div');
                responses.innerHTML = this.form.text;
                responses = responses.getElementsByTagName('strong');
                
                for (let index = 0; index < responses.length; index++) {
                    const element = responses[index].innerText;
                    this.form.responses.push(element); 
                }
                // ----------------------------------------------- //
                       
                this.processing = true;
                axios.post(route('evaluation.add'), this.form,
                {
                })
                .then((response) => {
                    if (response.status === 201) {
                        this.evaluations.push(response.data.eval);
                        this.processing = false;
                        this.showAddEval = false;
                    } 
                    else {
                    alert("Une erreur est survenue");
                        this.processing = false;
                        this.showAddEval = false;
                    }
                })
                this.form.title = '';
                this.form.type = '';
                this.form.desc = '';                
            },

            // Méthode permettant d'ajouter/supprimer un étudiant dans la liste des étudiants concerné par l'évaluation (avant envoi serveur) 
            selected(student_id) {
            if(this.formAddStudent.students_concerned.includes(student_id)){
                this.formAddStudent.students_concerned =_.without(this.formAddStudent.students_concerned,student_id);
            }else{
                this.formAddStudent.students_concerned.push(student_id);
            }  
            },

            // Méthode permettant d'ajouter/supprimer des étudiants à l'évaluations
            addStudents() {    
                this.processing = true;
                axios.post(route('evaluation.addStudents'), this.formAddStudent,
                {
                })
                .then((response) => {
                    if (response.status === 201) {
                        this.evaluations = response.data.evaluations;
                        this.processing = false;
                        this.showAddStudent = false;
                    } 
                    else {
                    alert("Une erreur est survenue");
                        this.processing = false;
                        this.showAddStudent = false;
                    }
                })                       
            },

            // Méthode permettant de modifier une évaluation (déjà existante)
            updateEval() {
                // Récupération du contenu de tiptap pour pouvoir l'insérer dans la variable 'text' du formulaire
                var editor = document.getElementsByClassName('contentEditor')[0];
                var content = editor.getAttribute('content');
                this.formUpdateEval.text = content.toString();
                // ----------------------------------------------- //

                // On insère les responses dans un tableau (Le text des balises strong)
                var responses = document.createElement('div');
                responses.innerHTML = this.formUpdateEval.text;
                responses = responses.getElementsByTagName('strong');

                var responsesUpdate = [];
                for (let index = 0; index < responses.length; index++) {
                    const element = responses[index].innerText;
                    responsesUpdate.push(element); 

                }
                this.formUpdateEval.responses = responsesUpdate;
                // ----------------------------------------------- //
                
                this.processing = true;
                axios.post(route('evaluation.update'), this.formUpdateEval,
                {
                })
                .then((response) => {
                    if (response.status === 201) {
                        this.evaluations = response.data.evaluations;
                        this.processing = false;
                        this.showUpdateEval = false;
                    } 
                    else {
                    alert("Une erreur est survenue");
                        this.processing = false;
                        this.showUpdateEval = false;
                    }
                })   
                   
            }, 
            
            // Méthode permettant de supprimer une évalution
            deleteEval() {
                this.processing = true;
                axios.post(route('evaluation.deleteEval'), this.formDeleteEval,
                {
                })
                .then((response) => {
                    if (response.status === 201) {
                        this.evaluations.splice(this.formDeleteEval.index, this.formDeleteEval.index+1);
                        this.processing = false;
                        this.showDeleteEval = false;
                    } 
                    else {
                    alert("Une erreur est survenue");
                        this.processing = false;
                        this.showDeleteEval = false;
                    }
                })   
            },
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