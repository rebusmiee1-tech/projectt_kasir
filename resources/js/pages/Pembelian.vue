<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

interface Pembelian {
    id_pembelian: number
    nomor_faktur: string
    tanggal_faktur: string
    total_bayar: number | string
    status_pembelian: string
    jenis_transaksi: string
    cara_bayar: string | null
    supplier?: {
        nama: string
    }
    user?: {
        nama_lengkap: string
    }
}

const pembelian = ref<Pembelian[]>([])
const loading = ref(true)

const ambilPembelian = async () => {
    const token = localStorage.getItem('token')

    try {
        const response = await fetch('/api/pembelian', {
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${token}`,
            },
        })

        if (!response.ok) {
            throw new Error('Gagal mengambil data pembelian')
        }

        pembelian.value = await response.json()
    } catch (error) {
        console.error(error)
        alert('Gagal mengambil data pembelian')
    } finally {
        loading.value = false
    }
}

const formatRupiah = (nilai: number | string) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(nilai))
}

const formatTanggal = (tanggal: string) => {
    if (!tanggal || tanggal === '0000-00-00 00:00:00') {
        return '-'
    }

    return new Date(tanggal).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    })
}

onMounted(() => {
    ambilPembelian()
})
</script>

<template>
    <Head title="Pembelian POS Sekolah" />

    <div class="min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="fixed left-0 top-0 hidden h-screen w-64 bg-slate-900 text-white lg:block">

            <div class="flex h-20 items-center border-b border-slate-700 px-6">
                <div>
                    <h1 class="text-xl font-bold">
                        🏫 POS Sekolah
                    </h1>

                    <p class="text-xs text-slate-400">
                        Sistem Kasir
                    </p>
                </div>
            </div>

            <nav class="p-4">

                <p class="mb-3 px-3 text-xs font-semibold uppercase text-slate-500">
                    Menu Utama
                </p>

                <!-- DASHBOARD -->
                <a
                    href="/dashboard"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>📊</span>
                    Dashboard
                </a>

                <!-- KASIR -->
                <a
                    href="/kasir"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>🛒</span>
                    Kasir / Transaksi
                </a>

                <!-- PEMBELIAN AKTIF -->
                <a
                    href="/pembelian"
                    class="mb-2 flex items-center gap-3 rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium"
                >
                    <span>📦</span>
                    Pembelian
                </a>

                <!-- PRODUK -->
                <a
                    href="/barang"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>🏷️</span>
                    Produk
                </a>

                <!-- PELANGGAN -->
                <a
                    href="/pelanggan"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>👥</span>
                    Pelanggan
                </a>

                <!-- SUPPLIER -->
                <a
                    href="/supplier"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>🚚</span>
                    Supplier
                </a>

                <!-- USER -->
                <a
                    href="/user-pos"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>👤</span>
                    User
                </a>

                <p class="mb-3 mt-8 px-3 text-xs font-semibold uppercase text-slate-500">
                    Lainnya
                </p>

                <!-- LAPORAN -->
                <a
                    href="#"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>📈</span>
                    Laporan
                </a>

                <!-- PENGATURAN -->
                <a
                    href="#"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>⚙️</span>
                    Pengaturan
                </a>

            </nav>
        </aside>

        <!-- CONTENT -->
        <main class="lg:ml-64">

            <!-- HEADER -->
            <header class="sticky top-0 z-10 flex h-20 items-center justify-between border-b bg-white px-6 shadow-sm md:px-8">

                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        Pembelian
                    </h2>

                    <p class="text-sm text-gray-500">
                        Daftar transaksi pembelian
                    </p>
                </div>

                <div class="flex items-center gap-4">

                    <button
                        class="rounded-lg border px-3 py-2 text-gray-500 hover:bg-gray-50"
                    >
                        🔔
                    </button>

                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold text-gray-800">
                            Administrator
                        </p>

                        <p class="text-xs text-gray-500">
                            Admin
                        </p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                        A
                    </div>

                </div>

            </header>

            <!-- PEMBELIAN BODY -->
            <div class="p-6 md:p-8">

                <!-- JUDUL -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Data Pembelian
                    </h1>

                    <p class="mt-1 text-gray-500">
                        Daftar seluruh transaksi pembelian barang.
                    </p>
                </div>

                <!-- CARD -->
                <div class="rounded-2xl bg-white p-6 shadow-sm">

                    <!-- HEADER TABLE -->
                    <div class="mb-6 flex items-center justify-between">

                        <div>
                            <h2 class="text-lg font-bold text-gray-800">
                                Transaksi Pembelian
                            </h2>

                            <p class="text-sm text-gray-500">
                                Data pembelian yang tersimpan
                            </p>
                        </div>

                        <button
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        >
                            + Tambah Pembelian
                        </button>

                    </div>

                    <!-- LOADING -->
                    <div
                        v-if="loading"
                        class="flex h-48 items-center justify-center text-gray-400"
                    >
                        Memuat data pembelian...
                    </div>

                    <!-- TABLE -->
                    <div
                        v-else
                        class="overflow-x-auto"
                    >

                        <table class="w-full">

                            <thead>
                                <tr class="border-b text-left">

                                    <th class="px-4 py-4 text-sm font-semibold text-gray-600">
                                        No
                                    </th>

                                    <th class="px-4 py-4 text-sm font-semibold text-gray-600">
                                        No. Faktur
                                    </th>

                                    <th class="px-4 py-4 text-sm font-semibold text-gray-600">
                                        Supplier
                                    </th>

                                    <th class="px-4 py-4 text-sm font-semibold text-gray-600">
                                        Tanggal
                                    </th>

                                    <th class="px-4 py-4 text-sm font-semibold text-gray-600">
                                        Total
                                    </th>

                                    <th class="px-4 py-4 text-sm font-semibold text-gray-600">
                                        Status
                                    </th>

                                    <th class="px-4 py-4 text-sm font-semibold text-gray-600">
                                        Jenis
                                    </th>

                                </tr>
                            </thead>

                            <tbody>

                                <tr
                                    v-for="(item, index) in pembelian"
                                    :key="item.id_pembelian"
                                    class="border-b last:border-0 hover:bg-gray-50"
                                >

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ index + 1 }}
                                    </td>

                                    <td class="px-4 py-4 text-sm font-semibold text-gray-800">
                                        {{ item.nomor_faktur }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ item.supplier?.nama ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ formatTanggal(item.tanggal_faktur) }}
                                    </td>

                                    <td class="px-4 py-4 text-sm font-semibold text-gray-800">
                                        {{ formatRupiah(item.total_bayar) }}
                                    </td>

                                    <td class="px-4 py-4">

                                        <span
                                            class="rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-700"
                                        >
                                            {{ item.status_pembelian }}
                                        </span>

                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ item.jenis_transaksi }}
                                    </td>

                                </tr>

                                <tr v-if="pembelian.length === 0">

                                    <td
                                        colspan="7"
                                        class="px-4 py-12 text-center text-gray-400"
                                    >
                                        Belum ada data pembelian.
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>
</template>`    