<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div v-if="tokens.length > 0" class="bg-white overflow-hidden shadow rounded-lg">
        <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
            <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap">
                <div class="ml-4 mt-2">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Authorized Applications
                    </h3>
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
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Scopes
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
                                        {{ token.client.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        <span v-if="token.scopes.length > 0">
                                            {{ token.scopes.join(', ') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <a href="#" class="text-red-600 hover:text-red-900" @click.prevent @click="revoke(token)">
                                            Revoke
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
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                tokens: []
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
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {
                this.getTokens();
            },

            /**
             * Get all of the authorized tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/tokens')
                    .then(response => {
                        this.tokens = response.data;
                    });
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/tokens/' + token.id)
                    .then(response => {
                        this.getTokens();
                    });
            }
        }
    }
</script>
