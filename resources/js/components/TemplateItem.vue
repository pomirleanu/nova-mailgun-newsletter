<template>
    <tr>
        <td class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            {{ id }}
        </td>
        <td class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            {{ name }}
        </td>
        <td class="px-2 py-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            {{ created_at }}
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
    name: {required: true},
    props: {
        id: {required: true},
        name: {required: true},
        address: {required: true},
        created_at: {required: true},
        deletable: {required: true},
        deleting: {required: true},
    },
}
</script>
