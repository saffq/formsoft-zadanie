<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import EditInvoiceForm from './Edit.vue';
import CreateInvoiceForm from './Create.vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Invoice {
    id: number;
    invoice_number: string;
    seller_nip: string;
    buyer_nip: string;
    product_name: string;
    net_amount: string;
    issuance_date: string;
    created_at: string;
    updated_at: string;
    created_at_formatted?: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface InvoicesPagination {
    data: Invoice[];
    links: PaginationLink[];
}

interface Props {
    invoices: InvoicesPagination;
    filters: {
        search: string;
    };
    success?: string;
}

const props = defineProps<Props>();


const searchQuery = ref(props.filters.search || '');
watch(
    () => props.filters.search,
    (newVal) => (searchQuery.value = newVal || '')
);

const filterInvoices = () => {
    router.get(
        route('invoices.index'),
        { search: searchQuery.value },
        { preserveState: true, replace: true }
    );
};

const editModalOpen = ref(false);
const selectedInvoice = ref<Invoice | null>(null);

const openEditModal = (invoice: Invoice) => {
    selectedInvoice.value = invoice;
    editModalOpen.value = true;
};

const closeEditModal = () => {
    editModalOpen.value = false;
    selectedInvoice.value = null;
};

const createModalOpen = ref(false);
const openCreateModal = () => {
    createModalOpen.value = true;
};

const closeCreateModal = () => {
    createModalOpen.value = false;
};

const deleteModalOpen = ref(false);
const invoiceToDelete = ref<Invoice | null>(null);

const openDeleteModal = (invoice: Invoice) => {
    invoiceToDelete.value = invoice;
    deleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    deleteModalOpen.value = false;
    invoiceToDelete.value = null;
};

const confirmDelete = () => {
    if (invoiceToDelete.value) {
        router.delete(route('invoices.destroy', invoiceToDelete.value.id), {
            onSuccess: () => closeDeleteModal(),
        });
    }
};
</script>

<template>
    <AppLayout>
        <div class="container mx-auto py-8 px-4">
            <Head title="Lista Faktur" />
            <h1 class="text-2xl font-bold mb-4 hover:animate-none">Lista Faktur</h1>

            <div v-if="props.success" class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ props.success }}
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between mb-6 space-y-4 sm:space-y-0">
                <form @submit.prevent="filterInvoices" class="flex flex-1 mr-0 sm:mr-4">
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Szukaj faktury..."
                        class="w-full border border-gray-300 rounded-l-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <button
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600 transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Szukaj
                    </button>
                </form>
                <button
                    @click="openCreateModal"
                    class="bg-green-500 text-white px-4 py-2 rounded-md shadow hover:bg-green-600 transition focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                    Dodaj fakturę
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border border-gray-300 text-left">Nr Faktury</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">NIP Kupującego</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">NIP Sprzedawcy</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">Nazwa produktu</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">Kwota Netto</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">Data Wystawienia</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">Data Edycji</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">Akcje</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="invoice in props.invoices.data"
                        :key="invoice.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-4 py-2 border border-gray-300">{{ invoice.invoice_number }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ invoice.buyer_nip }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ invoice.seller_nip }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ invoice.product_name }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ invoice.net_amount }} zł</td>
                        <td class="px-4 py-2 border border-gray-300">{{ invoice.issuance_date }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ invoice.updated_at_formatted }}</td>
                        <td class="px-4 py-2 border border-gray-300 flex flex-col sm:flex-row sm:space-x-2">
                            <button
                                @click="openEditModal(invoice)"
                                class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            >
                                Edytuj
                            </button>
                            <button
                                @click="openDeleteModal(invoice)"
                                class="inline-flex items-center justify-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition mt-2 sm:mt-0"
                            >
                                Usuń
                            </button>
                        </td>
                    </tr>
                    <tr v-if="props.invoices.data.length === 0">
                        <td colspan="8" class="px-4 py-2 text-center border border-gray-300">
                            Brak wyników
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-center">
                <nav class="flex flex-wrap items-center justify-center gap-2">
                    <template v-for="link in props.invoices.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="link.active
          ? 'bg-green-950 text-white px-2 sm:px-4 py-2 rounded-md shadow hover:bg-green-950 transition focus:outline-none focus:ring-2 focus:ring-green-950'
          : 'bg-blue-500 text-white px-2 sm:px-4 py-2 rounded-md shadow hover:bg-blue-600 transition focus:outline-none focus:ring-2 focus:ring-blue-500'"
                            v-html="link.label"
                        ></Link>
                        <span
                            v-else
                            class="bg-gray-500 text-white px-2 sm:px-4 py-2 rounded-md shadow transition focus:outline-none focus:ring-2"
                            v-html="link.label"
                        ></span>
                    </template>
                </nav>
            </div>

            <transition name="fade">
                <div v-if="editModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="absolute inset-0 bg-black opacity-50" @click="closeEditModal"></div>
                    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full z-10 p-6 relative">
                        <button
                            @click="closeEditModal"
                            class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-2xl leading-none focus:outline-none"
                        >
                            &times;
                        </button>
                        <h2 class="text-xl font-bold mb-4">Edytuj fakturę</h2>
                        <div v-if="selectedInvoice">
                            <EditInvoiceForm :invoice="selectedInvoice" @close="closeEditModal" />
                        </div>
                    </div>
                </div>
            </transition>

            <transition name="fade">
                <div v-if="createModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="absolute inset-0 bg-black opacity-50" @click="closeCreateModal"></div>
                    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full z-10 p-6 relative">
                        <button
                            @click="closeCreateModal"
                            class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-2xl leading-none focus:outline-none"
                        >
                            &times;
                        </button>
                        <h2 class="text-xl font-bold mb-4">Dodaj nową fakturę</h2>
                        <CreateInvoiceForm @close="closeCreateModal" />
                    </div>
                </div>
            </transition>

            <transition name="fade">
                <div v-if="deleteModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="absolute inset-0 bg-black opacity-50" @click="closeDeleteModal"></div>
                    <div class="bg-white rounded-lg shadow-lg max-w-md w-full z-10 p-6 relative">
                        <button
                            @click="closeDeleteModal"
                            class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-2xl leading-none focus:outline-none"
                        >
                            &times;
                        </button>
                        <h2 class="text-xl font-bold mb-4">Potwierdzenie usunięcia</h2>
                        <p class="mb-4">
                            Czy jesteś pewien, że chcesz usunąć fakturę o numerze
                            <strong v-if="invoiceToDelete">{{ invoiceToDelete.invoice_number }}</strong>?
                        </p>
                        <div class="flex justify-end space-x-4">
                            <button
                                @click="closeDeleteModal"
                                class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded focus:outline-none"
                            >
                                Anuluj
                            </button>
                            <button
                                @click="confirmDelete"
                                class="px-4 py-2 bg-red-500 text-white hover:bg-red-600 rounded focus:outline-none focus:ring-2 focus:ring-red-500 transition"
                            >
                                Usuń
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </AppLayout>
</template>
