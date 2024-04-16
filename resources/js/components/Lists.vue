<template>
    <div>
        <div class="p-3 flex items-center"
             v-if="lists.length > 1">
            <SelectControl
                class="w-full md:w-1/5"
                size="lg"
                :options="getDiscs()"
                :value="activeDisk"
                @input="$emit('update:activeDisk', $event.target.value)"
            />
        </div>

        <div class="overflow-hidden overflow-x-auto relative rounded-lg">
            <table cellpadding="0" cellspacing="0" class="table-default w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide px-2 py-2">
                        {{ __('Name') }}
                    </th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide px-2 py-2">
                        {{ __('Address') }}
                    </th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide px-2 py-2">
                        {{ __('Members Count') }}
                    </th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide px-2 py-2">
                        {{ __('Created At') }}
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <list-item
                    v-if="lists.length"
                    v-for="list in lists"
                    v-bind="list"
                    :deletable="lists.length >= 1"
                    :deleting="!deleteModalOpen"
                    :key="list.id"
                    @delete="openDeleteModal(list)"
                />
                <tr v-if="lists.length <= 0">
                    <td class="text-center px-2 py-2" colspan="4">
                        {{ __('No lists present') }}
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
            <ModalHeader v-text="__('Delete list')"/>
            <ModalContent>
                <p
                    class="leading-normal"
                    v-text="__('Are you sure you want to delete the list created on :date ?', {
                        date: createdAt,
                    })"
                />
            </ModalContent>
        </DeleteResourceModal>
    </div>
</template>

<script>
import ListItem from './ListItem';

export default {
    emits: ['setModalVisibility', 'delete'],
    props: {
        lists: {required: true, type: Array}
    },

    data() {
        return {
            deleteModalOpen: false,
            deletingList: null
        };
    },

    components: {
        ListItem,
    },
    computed:{
        createdAt(){
            const dateObject = this.deletingList.created_at;
            const date = new Date(dateObject.date);

            const readableDate = date.toLocaleDateString('en-US', {
                weekday: 'long', // "Monday" for example
                year: 'numeric', // "2024"
                month: 'long',   // "April"
                day: 'numeric'   // "10"
            });

            const readableTime = date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });

            return `${readableDate} / ${readableTime}`;
        },
    },
    methods: {

        openDeleteModal(list) {
            this.$emit('setModalVisibility', true);
            this.deleteModalOpen = true;
            this.deletingList = list;
        },

        closeDeleteModal() {
            this.$emit('setModalVisibility', false);
            this.deleteModalOpen = false;
            this.deletingList = null;
        },

        confirmDelete() {
            this.$emit('setModalVisibility', false);
            this.deleteModalOpen = false;

            this.$emit('delete', {
                list: this.deletingList,
            });
        },
    },
};
</script>
