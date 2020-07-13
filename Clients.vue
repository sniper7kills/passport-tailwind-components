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
                            OAuth Clients
                        </h3>
                    </div>
                    <div class="ml-4 mt-2 flex-shrink-0">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button @click="showCreateClientForm" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700">
                                Create New Client
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <p class="mb-0" v-if="clients.length === 0">
                    You have not created any OAuth clients.
                </p>

                <div class="flex flex-col" v-if="clients.length > 0">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Client ID
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Secret
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">

                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(client, index) in clients" v-bind:key="index"
                                        :class="index % 2 == 0 ? 'bg-white' : 'bg-gray-50'"
                                    >
                                        <!-- ID -->
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                            {{ client.id }}
                                        </td>

                                        <!-- Name -->
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ client.name }}
                                        </td>

                                        <!-- Secret -->
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            <code>{{ client.secret ? client.secret : '-' }}</code>
                                        </td>

                                        <!-- Edit Button -->
                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900" tabindex="-1" @click.prevent @click="edit(client)">
                                                Edit
                                            </a>
                                        </td>

                                        <!-- Delete Button -->
                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <a href="#" class="text-red-600 hover:text-red-900" @click.prevent @click="destroy(client)">
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



        <!-- Create Client Modal -->
        <div v-if="this.showCreateModal" class="fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">
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
                <div v-if="this.showCreateModal" class="fixed inset-0 transition-opacity">
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
                <div v-if="this.showCreateModal" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Create Client
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="mt-2">
                            <div class="mb-2 text-sm text-red-600" v-if="createForm.errors.length > 0">
                                <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                                <ul>
                                    <li v-for="(error, key) in createForm.errors" :key="key">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                            
                        <form role="form">
                            <!-- Name -->
                            <div>
                                <label for="create-client-name" class="block text-sm font-medium leading-5 text-gray-700">
                                    Name
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">          
                                    <input id="create-client-name" @keyup.enter="store" v-model="createForm.name" type="text" class="form-input block w-full sm:text-sm sm:leading-5" />
                                </div>
                                <p class="mt-2 text-sm text-gray-500" id="create-client-name-description">
                                    Something your users will recognize and trust.
                                </p>
                            </div>

                            <!-- Redirect URL -->
                            <div>
                                <label for="create-redirect-url" class="block text-sm font-medium leading-5 text-gray-700">
                                    Redirect URL
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">          
                                    <input id="create-redirect-url" @keyup.enter="store" v-model="createForm.redirect" type="text" class="form-input block w-full sm:text-sm sm:leading-5" />
                                </div>
                                <p class="mt-2 text-sm text-gray-500" id="create-redirect-url-description">
                                    Your application's authorization callback URL.
                                </p>
                            </div>

                            <!-- Confidential -->
                            <div>
                                <label for="create-confidential" class="block text-sm font-medium leading-5 text-gray-700">
                                    Confidential
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">          
                                    <input id="create-confidential" @keyup.enter="store" v-model="createForm.confidential" type="hidden" />
                                    <span role="checkbox" @click="createForm.confidential = !createForm.confidential" tabindex="0" :aria-checked="createForm.confidential" :class="createForm.confidential ? 'bg-green-200 ' : 'bg-red-200 '" class="border-2 border-transparent cursor-pointer ease-in-out flex-shrink-0 focus:outline-none focus:shadow-outline h-6 inline-flex relative rounded-full transition-colors w-11">
                                        <!-- On: "translate-x-5", Off: "translate-x-0" --> 
                                        <span aria-hidden="true" :class="createForm.confidential ? 'translate-x-5' : 'translate-x-0'" class="relative inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"> 
                                            <!-- On: "opacity-0 ease-out duration-100", Off: "opacity-100 ease-in duration-200" -->
                                            <span :class="createForm.confidential ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'" class=" absolute inset-0 h-full w-full flex items-center justify-center transition-opacity">
                                                <svg class="h-3 w-3 text-red-400" fill="none" viewBox="0 0 12 12">
                                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                            <!-- On: "opacity-100 ease-in duration-200", Off: "opacity-0 ease-out duration-100" -->
                                            <span :class="createForm.confidential ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'" class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity">
                                                <svg class="h-3 w-3 text-green-600" fill="currentColor" viewBox="0 0 12 12">
                                                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"/>
                                                </svg>
                                            </span>
                                        </span>
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-gray-500" id="create-confidential-description">
                                    Require the client to authenticate with a secret. Confidential clients can hold credentials in a secure way without exposing them to unauthorized parties. Public applications, such as native desktop or JavaScript SPA applications, are unable to hold secrets securely.
                                </p>
                            </div>
                        </form>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 text-right">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="button" @click="closeCreateForm" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-50 active:text-gray-800">
                                Close
                            </button>
                        </span>
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="button" @click="store" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-50 active:text-gray-800">
                                Create
                            </button>
                        </span>
                    </div>
                </div>
            </transition>
        </div>


        <!-- Edit Client Modal -->
        <div v-if="this.showEditModal" class="fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">
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
                <div v-if="this.showEditModal" class="fixed inset-0 transition-opacity">
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
                <div v-if="this.showEditModal" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Create Client
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="mt-2">
                            <div class="mb-2 text-sm text-red-600" v-if="editForm.errors.length > 0">
                                <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                                <ul>
                                    <li v-for="(error, key) in editForm.errors" :key="key">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                            
                        <form role="form">
                            <!-- Name -->
                            <div>
                                <label for="create-client-name" class="block text-sm font-medium leading-5 text-gray-700">
                                    Name
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">          
                                    <input id="create-client-name" @keyup.enter="store" v-model="editForm.name" type="text" class="form-input block w-full sm:text-sm sm:leading-5" />
                                </div>
                                <p class="mt-2 text-sm text-gray-500" id="create-client-name-description">
                                    Something your users will recognize and trust.
                                </p>
                            </div>

                            <!-- Redirect URL -->
                            <div>
                                <label for="create-redirect-url" class="block text-sm font-medium leading-5 text-gray-700">
                                    Redirect URL
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">          
                                    <input id="create-redirect-url" @keyup.enter="store" v-model="editForm.redirect" type="text" class="form-input block w-full sm:text-sm sm:leading-5" />
                                </div>
                                <p class="mt-2 text-sm text-gray-500" id="create-redirect-url-description">
                                    Your application's authorization callback URL.
                                </p>
                            </div>
                        </form>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 text-right">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="button" @click="closeEditForm" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-50 active:text-gray-800">
                                Close
                            </button>
                        </span>
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="button" @click="update" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-50 active:text-gray-800">
                                Save
                            </button>
                        </span>
                    </div>
                </div>
            </transition>
        </div>


        <!-- Client Secret Modal -->
        <div v-if="this.showClientSecretModal" class="fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">
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
                <div v-if="this.showClientSecretModal" class="fixed inset-0 transition-opacity">
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
                <div v-if="this.showClientSecretModal" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Client Secret
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <p class="mt-2 text-sm text-gray-500" id="create-confidential-description">
                            Here is your new client secret. This is the only time it will be shown so don't lose it!
                            You may now use this secret to make API requests.
                        </p>
                        <input type="text" class="block duration-150 ease-in-out form-textarea mt-1 transition w-full sm:leading-5 sm:text-sm" v-model="clientSecret">
                    </div>
                    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 text-right">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="button" @click="closeClientSecretModal" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-50 active:text-gray-800">
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
                clients: [],

                showClientSecretModal: false,
                clientSecret: null,

                showCreateModal: false,
                createForm: {
                    errors: [],
                    name: '',
                    redirect: '',
                    confidential: true
                },

                showEditModal: false,
                editForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                }
            };
        },

        created() {
            const handleEscape = (e) => {
                if(e.key === 'Esc' || e.key === 'Escape'){
                    this.showCreateModal = false;
                    this.showEditModal = false;
                }
            }

            document.addEventListener('keydown', handleEscape);

            this.$once('hook:beforeDestroy', () => {
                document.removeEventListener('keydown', handleEscape);
            })
        },
        mounted() {
            this.getClients();
        },

        methods: {
            showCreateClientForm() {
                this.showCreateModal=true;
            },
            closeCreateForm() {
                this.showCreateModal=false;
                this.createForm = {
                    errors: [],
                    name: '',
                    redirect: '',
                    confidential: true
                };
            },
            closeEditForm() {
                this.showEditModal=false;
                this.editForm = {
                    errors: [],
                    name: '',
                    redirect: ''
                };
            },
            closeClientSecretModal() {
                this.showClientSecretModal=false;
                this.clientSecret = null;
            },
            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                        .then(response => {
                            this.clients = response.data;
                        });
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post',
                    '/oauth/clients',
                    this.createForm,
                    '#modal-create-client'
                );
                this.closeCreateForm();
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.editForm.id = client.id;
                this.editForm.name = client.name;
                this.editForm.redirect = client.redirect;

                this.showEditModal = true;
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put',
                    '/oauth/clients/' + this.editForm.id,
                    this.editForm,
                    '#modal-edit-client'
                );
                this.showEditModal = false;
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form, modal) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getClients();

                        form.name = '';
                        form.redirect = '';
                        form.errors = [];

                        if (response.data.plainSecret) {
                            this.showClientSecret(response.data.plainSecret);
                        }
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Show the given client secret to the user.
             */
            showClientSecret(clientSecret) {
                this.clientSecret = clientSecret;
                this.showClientSecretModal = true;
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                axios.delete('/oauth/clients/' + client.id)
                        .then(response => {
                            this.getClients();
                        });
            }
        }
    }
</script>
