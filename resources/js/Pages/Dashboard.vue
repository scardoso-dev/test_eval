<template>
    <app-layout title="Dashboard">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                    <dashboard-teacher v-if="$page.props.user.roles.indexOf('ROLE_TEACHER') != -1" :evaluations="evaluations" :students="students" :evaluationsResponses="evaluationsResponses"></dashboard-teacher>
                    <dashboard-student v-if="$page.props.user.roles.indexOf('ROLE_STUDENT') != -1" :evaluations="evaluations" :evaluationsResponses="evaluationsResponses"></dashboard-student>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Welcome from '@/Jetstream/Welcome.vue'
    import DashboardTeacher from './Components/DashboardTeacher.vue'
    import DashboardStudent from './Components/DashboardStudent.vue'
    export default ({
        components: {
            AppLayout, Welcome, DashboardTeacher, DashboardStudent
        },
        props: {
            evaluations: Object,
            students: Object,
            evaluationsResponses: Object
        },
        data() {
            return {
                evaluations: this.evaluations,
                students: this.students,
                evaluationsResponses: this.evaluationsResponses
            }
        }
    })
</script>
