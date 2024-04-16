<template>
    <tr :class="{ 'is-deleting': deleting }">
        <td class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            {{ name }}
        </td>
        <td class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            {{ address }}
        </td>
        <td class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            {{ members_count }}
        </td>
        <td class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            {{ createdAt }}
        </td>
        <td class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900 text-right">
            <button
                :title="__('Delete')"
                class="appearance-none mr-3"
                :class="deletable ? 'text-70 hover:text-primary' : 'cursor-default text-40'"
                :disabled="!deletable"
                @click.prevent="$emit('delete')"
            >
                <icon type="trash" view-box="0 0 24 24" width="20" height="20"/>
            </button>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        name: {required: true},
        address: {required: true},
        members_count: {required: true},
        created_at: {required: true},
        deletable: {required: true},
        deleting: {required: true},
    },

    computed: {
        createdAt(){
            const dateObject = this.created_at;
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
        }
    },
};
</script>

<style scoped>
.is-deleting td {
    color: var(--60);
}
</style>
