```vue
<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Barang {
    id_barang: number
    barcode: string
    nama: string
    satuan: string
    harga_beli: number
    harga_jual: number
    stok: number
}

const barang = ref<Barang[]>([])
const loading = ref(true)

const ambilBarang = async () => {
    const token = localStorage.getItem('token')

    try {
        const response = await fetch('/api/barang', {
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${token}`,
            },
        })

        if (!response.ok) {
            throw new Error('Gagal mengambil data barang')
        }

        barang.value = await response.json()
    } catch (error) {
        console.error(error)
        alert('Gagal mengambil data barang')
    } finally {
        loading.value = false
    }
}

const formatRupiah = (nilai: number | string) => {
    return Number(nilai).toLocaleString('id-ID')
}

onMounted(() => {
    ambilBarang()
})
</script>

<template>
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Data Barang
            </h1>

            <p class="text-gray-500">
                Daftar barang yang tersedia
            </p>
        </div>

        <div
            v-if="loading"
            class="text-gray-500"
        >
            Memuat data barang...
        </div>

        <div
            v-else
            class="overflow-x-auto rounded-lg border bg-white"
        >
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Barcode</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Satuan</th>
                        <th class="px-4 py-3">Harga Beli</th>
                        <th class="px-4 py-3">Harga Jual</th>
                        <th class="px-4 py-3">Stok</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(item, index) in barang"
                        :key="item.id_barang"
                        class="border-t"
                    >
                        <td class="px-4 py-3">
                            {{ index + 1 }}
                        </td>

                        <td class="px-4 py-3">
                            {{ item.barcode }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ item.nama }}
                        </td>

                        <td class="px-4 py-3">
                            {{ item.satuan }}
                        </td>

                        <td class="px-4 py-3">
                            Rp {{ formatRupiah(item.harga_beli) }}
                        </td>

                        <td class="px-4 py-3">
                            Rp {{ formatRupiah(item.harga_jual) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ item.stok }}
                        </td>
                    </tr>

                    <tr v-if="barang.length === 0">
                        <td
                            colspan="7"
                            class="px-4 py-6 text-center text-gray-500"
                        >
                            Belum ada data barang
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
```
