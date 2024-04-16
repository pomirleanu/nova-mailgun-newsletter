<template>
    <div>

        <div class="overflow-hidden overflow-x-auto relative rounded-lg">
            <table cellpadding="0" cellspacing="0" class="table-default w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide px-2 py-2">
                        {{ __('Id') }}
                    </th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide px-2 py-2">
                        {{ __('Name') }}
                    </th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide px-2 py-2">
                        {{ __('Created At') }}
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <template-item
                    v-for="template in templates"
                    v-bind="template"
                    :key="template.id"
                    :deletable="templates.length >= 1"
                    :deleting="!deleteModalOpen"
                    @delete="openDeleteModal(template)"
                />
                <tr v-if="templates.length <= 0">
                    <td class="text-center px-2 py-2" colspan="4">
                        {{ __('No templates present') }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <DeleteResourceModal
            mode="delete"
            :show="deleteModalOpen"
            @close="closeDeleteModal"
            @confirm="confirmDelete"
        >
            <ModalHeader v-text="__('Delete template')"/>
            <ModalContent>
                <p
                    class="leading-normal"
                    v-text="__('Are you sure you want to delete the template  :name ?', {
                        name: deletingTemplate.name,
                    })"
                />
            </ModalContent>
        </DeleteResourceModal>
    </div>
</template>

<script>
import TemplateItem from './TemplateItem.vue';

export default {
    emits: [],
    props: {
        templates: {required: true, type: Array}
    },

    components: {
        TemplateItem,
    },

    data() {
        return {
            deleteModalOpen: false,
            deletingTemplate: null
        };
    },

    methods:{
        openDeleteModal(template) {
            this.$emit('setModalVisibility', true);
            this.deleteModalOpen = true;
            this.deletingTemplate = template;
        },

        closeDeleteModal() {
            this.$emit('setModalVisibility', false);
            this.deleteModalOpen = false;
            this.deletingTemplate = null;
        },

        confirmDelete() {
            this.$emit('setModalVisibility', false);
            this.deleteModalOpen = false;

            this.$emit('delete', {
                template: this.deletingTemplate,
            });
        },
    }
};
</script>
