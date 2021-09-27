<template>
    <Head title="Welcome" />
    
    <div class="relative flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.user" :href="route('dashboard')" class="text-sm text-gray-700 underline">
                Dashboard
            </Link>
            <!--
            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 underline">
                    Connexion
                </Link>

                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
                    Inscription
                </Link>
            </template>
            -->
        </div>
    </div>
    
        <div class="bg-white shadow w-3/4 mt-3 text-gray-700 mx-auto h-full p-5">
            <h1 class="text-xl uppercase font-bold p-3 text-center">Bienvenue sur la page d'évalution</h1>
            <div v-if="$page.props.user == null" class="flex justify-center flex-col items-center">
                <p>Veuillez vous connecter pour accéder à votre espace dédié</p>
                <Link :href="route('login')" :class="'mx-auto mt-3 block rounded-xl p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none'">
                    Connexion
                </Link>
            </div>
            <div v-else class="flex justify-center flex-col items-center">
                <Link :href="route('dashboard')" :class="'mx-auto mt-3 block rounded-xl p-3 uppercase text-xs font-bold text-white border border-gray-100 bg-gray-700 hover:bg-gray-600 focus:outline-none'">
                    Accéder à mon espace
                </Link>
            </div>

        </div>
    

</template>

<style scoped>
    .bg-gray-100 {
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
    }

    .bg-gray-700 {
        background-color: #4a5568;
        background-color: rgba(74, 85, 104, var(--tw-bg-opacity));
    }

    .border-gray-200 {
        border-color: #edf2f7;
        border-color: rgba(237, 242, 247, var(--tw-border-opacity));
    }

    .text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }

    .text-gray-500 {
        color: #a0aec0;
        color: rgba(160, 174, 192, var(--tw-text-opacity));
    }

    .text-gray-600 {
        color: #718096;
        color: rgba(113, 128, 150, var(--tw-text-opacity));
    }

    .text-gray-700 {
        color: #4a5568;
        color: rgba(74, 85, 104, var(--tw-text-opacity));
    }

    .text-gray-900 {
        color: #1a202c;
        color: rgba(26, 32, 44, var(--tw-text-opacity));
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-800 {
            background-color: #2d3748;
            background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
        }

        .dark\:bg-gray-900 {
            background-color: #1a202c;
            background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
        }

        .dark\:border-gray-700 {
            border-color: #4a5568;
            border-color: rgba(74, 85, 104, var(--tw-border-opacity));
        }

        .dark\:text-white {
            color: #fff;
            color: rgba(255, 255, 255, var(--tw-text-opacity));
        }

        .dark\:text-gray-400 {
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--tw-text-opacity));
        }
    }
</style>

<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Head,
            Link,
        },

        props: {
            canLogin: Boolean,
            canRegister: Boolean,
            laravelVersion: String,
            phpVersion: String,
        }
    })
</script>
