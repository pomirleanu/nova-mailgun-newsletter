<template>
    <LoadingView :loading="initialLoading">
        <div>
            <Head :title="__('Mailgun Newsletter')"/>

            <div class="flex mb-6 items-center justify-between">
                <Heading>
                    {{ __('Mailgun Newsletter') }}
                </Heading>

                <div class="flex items-center justify-end space-x-2">

                </div>
            </div>
            <LoadingCard :loading="loading">
                <div class="overflow-hidden overflow-x-auto relative rounded-lg">
                    <mail-lists
                        :lists="activeLists"
                        @delete="deleteList"
                        @refresh="refreshList"
                        @setModalVisibility="setModalVisibility"
                    />
                </div>
            </LoadingCard>

            <div class="py-4">
                <LoadingCard :loading="loading">
                    <templates
                        :templates="templates"
                        @setModalVisibility="setModalVisibility"
                        @delete="deleteTemplate"
                    />
                </LoadingCard>
            </div>
        </div>
    </LoadingView>
</template>
<script>
import MailLists from "../components/Lists.vue";
import api from '../api';
import Templates from "../components/Templates.vue";

export default {
    components: {
        Templates,
        MailLists,
    },
    mounted() {
        //
    },
    data: () => ({
        loading: true,
        initialLoading: true,
        modalVisibility: false,
        poller: null,
        activeLists: [],
        templates:[]
    }),
    async created() {
        this.initialLoading = false;

        await this.updateLists();
        await this.updateTemplates();

        this.startPolling();
        this.loading = false;
    },
    beforeUnmount() {
        if (this.poller) {
            window.clearInterval(this.poller);
        }
    },
    methods: {
        updateLists() {
            return api.getLists().then(response => {
                this.activeLists = response.lists;
            });
        },

        refreshList({list, userType}) {
            return api.refreshList({list, userType});
        },

        deleteList({list}) {
            const listAddress = list.address;
            api.deleteList(listAddress).then(() => {
                Nova.success(this.__('The list has been deleted'));
                this.updateLists();
            }).catch(error => {
                Nova.error(this.__('Failed to delete the list'));
            });
        },

        updateTemplates(){
            return api.getTemplates().then(response =>{
                this.templates = response.templates;
                console.log(this.templates);
            });
        },

        deleteTemplate({template}){
            const templateName = template.name;
            api.deleteTemplate(templateName).then(() => {
                Nova.success(this.__('The template has been deleted'));
                this.updateTemplates();
            }).catch(error => {
                Nova.error(this.__('Failed to delete the template'));
            });
        },

        startPolling() {
            if (Nova.config('nova_mailgun_newsletter_tool').polling) {
                this.poller = window.setInterval(() => {
                    if (!this.modalVisibility) {

                    }
                }, Nova.config('nova_mailgun_newsletter_tool').polling_interval * 1000);
            }
        },

        setModalVisibility(state) {
            this.modalVisibility = state;
        },
    },
}
</script>

<style>
/* Scoped Styles */
</style>
