<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap">
                    <div class="ml-4 mt-2">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Personal Access Tokens
                        </h3>
                    </div>
                    <div class="ml-4 mt-2 flex-shrink-0">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button @click="showCreateTokenForm" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700">
                                Create New Token
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <p class="mb-0" v-if="tokens.length === 0">
                    You have not created any personal access tokens.
                </p>

                <div class="flex flex-col" v-if="tokens.length > 0">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(token, index) in tokens" :key="index"
                                        :class="index % 2 == 0 ? 'bg-white' : 'bg-gray-50'"
                                    >
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                            {{ token.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <a href="#" class="text-red-600 hover:text-red-900" @click.prevent @click="revoke(token)">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Token Modal -->
        <div v-if="this.showCreateTokenModal" class="z-20 fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">
            <!--
                Background overlay, show/hide based on modal state.

                Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
                Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <transition 
                enter-active-class="ease-out duration-300"
                enter-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="this.showCreateTokenModal" class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
            </transition>

            <!--
                Modal panel, show/hide based on modal state.

                Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
                Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->
            <transition
                enter-active-class="ease-out duration-300"
                enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="ease-in duration-200"
                leave-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div v-if="this.showCreateTokenModal" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Create Token
                        </h3>
                    </div>
                    <form role="form" @submit.prevent="store">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="mt-2">
                                <div class="mb-2 text-sm text-red-600" v-if="form.errors.length > 0">
                                    <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                                    <ul>
                                        <li v-for="(error, key) in editForm.errors" :key="key">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Name -->
                            <div>
                                <label for="create-client-name" class="block text-sm font-medium leading-5 text-gray-700">
                                    Name
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">          
                                    <input id="create-token-name" v-model="form.name" type="text" class="form-input block w-full sm:text-sm sm:leading-5" />
                                </div>
                            </div>
                            <!-- Scopes -->
                            <div v-for="(scope,index) in scopes" :key="index">
                                <label for="scope" class="block text-sm font-medium leading-5 text-gray-700">
                                    {{scope.id}}
                                </label>
                                <span role="checkbox" @click="toggleScope(scope.id)" tabindex="0" :aria-checked="form.scopes.includes(scope.id)" :class="form.scopes.includes(scope.id) ? 'bg-green-200 ' : 'bg-red-200 '" class="border-2 border-transparent cursor-pointer ease-in-out flex-shrink-0 focus:outline-none focus:shadow-outline h-6 inline-flex relative rounded-full transition-colors w-11">
                                    <!-- On: "translate-x-5", Off: "translate-x-0" --> 
                                    <span aria-hidden="true" :class="form.scopes.includes(scope.id) ? 'translate-x-5' : 'translate-x-0'" class="relative inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"> 
                                        <!-- On: "opacity-0 ease-out duration-100", Off: "opacity-100 ease-in duration-200" -->
                                        <span :class="form.scopes.includes(scope.id) ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'" class=" absolute inset-0 h-full w-full flex items-center justify-center transition-opacity">
                                            <svg class="h-3 w-3 text-red-400" fill="none" viewBox="0 0 12 12">
                                                <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <!-- On: "opacity-100 ease-in duration-200", Off: "opacity-0 ease-out duration-100" -->
                                        <span :class="form.scopes.includes(scope.id) ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'" class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity">
                                            <svg class="h-3 w-3 text-green-600" fill="currentColor" viewBox="0 0 12 12">
                                                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"/>
                                            </svg>
                                        </span>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-4 sm:px-6 text-right">
                            <span class="inline-flex rounded-md shadow-sm">
                                <button type="button" @click="closeCreateAccessTokenModal" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-50 active:text-gray-800">
                                    Close
                                </button>
                            </span>
                            <span class="inline-flex rounded-md shadow-sm">
                                <button type="button" @click="store" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-50 active:text-gray-800">
                                    Create
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </transition>
        </div>

        <!-- Access Token Modal -->
        <div v-if="this.showAccessTokenModal" class="fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">
            <!--
                Background overlay, show/hide based on modal state.

                Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
                Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <transition 
                enter-active-class="ease-out duration-300"
                enter-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="this.showAccessTokenModal" class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
            </transition>

            <!--
                Modal panel, show/hide based on modal state.

                Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
                Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->
            <transition
                enter-active-class="ease-out duration-300"
                enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="ease-in duration-200"
                leave-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div v-if="this.showAccessTokenModal" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Personal Access Token
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <p class="mt-2 text-sm text-gray-500" id="create-confidential-description">
                            Here is your new personal access token. This is the only time it will be shown so don't lose it!
                            You may now use this token to make API requests.
                        </p>
                        <textarea v-model="accessToken" rows="10" class="block duration-150 ease-in-out form-textarea mt-1 transition w-full sm:leading-5 sm:text-sm"></textarea>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 text-right">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="button" @click="closeAccessTokenModal" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-50 active:text-gray-800">
                                Close
                            </button>
                        </span>
                    </div>
                </div>
            </transition>
        </div>

    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                showAccessTokenModal: false,
                accessToken: null,

                tokens: [],
                scopes: [],

                showCreateTokenModal: false,
                form: {
                    name: '',
                    scopes: [],
                    errors: []
                }
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getTokens();
                this.getScopes();
            },

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/personal-access-tokens')
                        .then(response => {
                            this.tokens = response.data;
                        });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                axios.get('/oauth/scopes')
                        .then(response => {
                            this.scopes = response.data;
                        });
            },

            /**
             * Show the form for creating new tokens.
             */
            showCreateTokenForm() {
                this.showCreateTokenModal = true
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.accessToken = null;

                this.form.errors = [];

                axios.post('/oauth/personal-access-tokens', this.form)
                        .then(response => {
                            this.form.name = '';
                            this.form.scopes = [];
                            this.form.errors = [];

                            this.tokens.push(response.data.token);

                            this.showAccessToken(response.data.accessToken);
                        })
                        .catch(error => {
                            if (typeof error.response.data === 'object') {
                                this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                            } else {
                                this.form.errors = ['Something went wrong. Please try again.'];
                            }
                        });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if (this.scopeIsAssigned(scope)) {
                    this.form.scopes = _.reject(this.form.scopes, s => s == scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return _.indexOf(this.form.scopes, scope) >= 0;
            },

            /**
             * Show the given access token to the user.
             */
            showAccessToken(accessToken) {
                this.showCreateTokenModal = false;

                this.accessToken = accessToken;

                this.showAccessTokenModal = true;
            },

            closeAccessTokenModal() {
                this.accessToken = null;
                this.showAccessTokenModal = false;
            },

            closeCreateAccessTokenModal() {
                this.form = {
                    name: '',
                    scopes: [],
                    errors: []
                }
                this.showCreateTokenModal = false;
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/personal-access-tokens/' + token.id)
                        .then(response => {
                            this.getTokens();
                        });
            }
        }
    }
</script>
