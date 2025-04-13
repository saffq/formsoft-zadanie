<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineEmits } from 'vue';

const emit = defineEmits(['close']);

const form = useForm({
    invoice_number: '',
    seller_nip: '',
    buyer_nip: '',
    product_name: '',
    net_amount: '',
    issuance_date: '',
});
const formatNetAmount = () => {
    const match = form.net_amount.toString().match(/^\d*\.?\d{0,2}/);
    form.net_amount = (match && match[0]) || '';
};
const validateDate = (e: Event) => {
    let value = (e.target as HTMLInputElement).value;
    value = value.replace(/[^0-9-]/g, '');
    const parts = value.split('-');
    if (parts[0]) {
        parts[0] = parts[0].slice(0, 4);
    }
    if (parts[1]) {
        parts[1] = parts[1].slice(0, 2);
    }
    if (parts[2]) {
        parts[2] = parts[2].slice(0, 2);
    }
    form.issuance_date = parts.join('-');
};
const submit = () => {
    form.post(route('invoices.store'), {
        onSuccess: () => {
            form.reset();
            emit('close');
        }
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label for="invoice_number" class="block text-sm font-medium text-gray-700">
                Numer faktury
            </label>
            <input
                id="invoice_number"
                type="text"
                required
                v-model="form.invoice_number"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500"
            />
            <div v-if="form.errors.invoice_number" class="text-red-600 text-sm">
                {{ form.errors.invoice_number }}
            </div>
        </div>

        <div>
            <label for="buyer_nip" class="block text-sm font-medium text-gray-700">
                NIP kupującego
            </label>
            <input
                id="buyer_nip"
                type="text"
                required
                v-model="form.buyer_nip"
                @input="form.buyer_nip = form.buyer_nip.replace(/\D/g, '').slice(0, 10)"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500"
            />
            <div v-if="form.errors.buyer_nip" class="text-red-600 text-sm">
                {{ form.errors.buyer_nip }}
            </div>
        </div>

        <div>
            <label for="seller_nip" class="block text-sm font-medium text-gray-700">
                NIP sprzedawcy
            </label>
            <input
                id="seller_nip"
                required
                type="text"
                v-model="form.seller_nip"
                @input="form.seller_nip = form.seller_nip.replace(/\D/g, '').slice(0, 10)"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500"
            />
            <div v-if="form.errors.seller_nip" class="text-red-600 text-sm">
                {{ form.errors.seller_nip }}
            </div>
        </div>

        <div>
            <label for="product_name" class="block text-sm font-medium text-gray-700">
                Nazwa produktu
            </label>
            <input
                id="product_name"
                type="text"
                required
                v-model="form.product_name"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500"
            />
            <div v-if="form.errors.product_name" class="text-red-600 text-sm">
                {{ form.errors.product_name }}
            </div>
        </div>

        <div>
            <label for="net_amount" class="block text-sm font-medium text-gray-700">
                Kwota netto
            </label>
            <input
                id="net_amount"
                type="number"
                v-model="form.net_amount"
                required
                step="0.01"
                min="0"
                @blur="formatNetAmount"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500"
            />
            <div v-if="form.errors.net_amount" class="text-red-600 text-sm">
                {{ form.errors.net_amount }}
            </div>
        </div>

        <div>
            <label for="issuance_date" class="block text-sm font-medium text-gray-700">
                Data wystawienia
            </label>
            <input
                id="issuance_date"
                type="date"
                v-model="form.issuance_date"
                @input="validateDate"
                maxlength="10"
                required
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500"
            />
            <div v-if="form.errors.issuance_date" class="text-red-600 text-sm">
                {{ form.errors.issuance_date }}
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button
                type="submit"
                class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-150 focus:outline-none focus:ring-2 focus:ring-green-500"
                :disabled="form.processing"
            >
                Dodaj fakturę
            </button>
            <button
                type="button"
                @click="$emit('close')"
                class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded focus:outline-none"
            >
                Anuluj
            </button>
        </div>
    </form>
</template>
