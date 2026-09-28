<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

defineProps<{
    statistik: {
        penjualanHariIni: number | string
        jumlahTransaksi: number
        produkTerjual: number
        jumlahPelanggan: number
    }

    grafikPenjualan: Array<{
        tanggal: string
        total: number | string
    }>

    transaksiTerbaru: Array<{
        id_penjualan: number
        tanggal_penjualan: string
        total_faktur: number | string
        total_bayar: number | string
        status_pembayaran: string | null
        cara_bayar: string | null
        pelanggan?: {
            nama_pelanggan?: string
        } | null
        user?: {
            nama_lengkap?: string
        } | null
    }>
}>()

const formatRupiah = (nilai: number | string) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(nilai))
}

const formatTanggal = (tanggal: string) => {
    return new Date(tanggal).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
    })
}
</script>

<template>
    <Head title="Dashboard POS Sekolah" />

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
                    class="mb-2 flex items-center gap-3 rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium"
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

                <!-- PEMBELIAN -->
                <a
                    href="/pembelian"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>📦</span>
                    Pembelian
                </a>

                <!-- PRODUK -->
                <a
                    href="#"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>🏷️</span>
                    Produk
                </a>

                <!-- PELANGGAN -->
                <a
                    href="#"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>👥</span>
                    Pelanggan
                </a>

                <!-- SUPPLIER -->
                <a
                    href="#"
                    class="mb-2 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800"
                >
                    <span>🚚</span>
                    Supplier
                </a>

                <!-- USER -->
                <a
                    href="#"
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
                        Dashboard
                    </h2>

                    <p class="text-sm text-gray-500">
                        Ringkasan aktivitas kasir
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

            <!-- DASHBOARD BODY -->
            <div class="p-6 md:p-8">

                <!-- WELCOME -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Selamat datang 👋
                    </h1>

                    <p class="mt-1 text-gray-500">
                        Berikut ringkasan aktivitas sistem kasir hari ini.
                    </p>
                </div>

                <!-- STAT CARDS -->
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">

                    <!-- PENJUALAN -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Penjualan Hari Ini
                                </p>

                                <p class="mt-2 text-2xl font-bold text-gray-800">
                                    {{ formatRupiah(statistik.penjualanHariIni) }}
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-xl">
                                💰
                            </div>

                        </div>

                        <p class="mt-4 text-xs text-gray-400">
                            Total penjualan hari ini
                        </p>

                    </div>

                    <!-- TRANSAKSI -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Total Transaksi
                                </p>

                                <p class="mt-2 text-2xl font-bold text-gray-800">
                                    {{ statistik.jumlahTransaksi }}
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-xl">
                                🛒
                            </div>

                        </div>

                        <p class="mt-4 text-xs text-gray-400">
                            Semua transaksi penjualan
                        </p>

                    </div>

                    <!-- PRODUK -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Produk Terjual
                                </p>

                                <p class="mt-2 text-2xl font-bold text-gray-800">
                                    {{ statistik.produkTerjual }}
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-xl">
                                📦
                            </div>

                        </div>

                        <p class="mt-4 text-xs text-gray-400">
                            Total jumlah barang terjual
                        </p>

                    </div>

                    <!-- PELANGGAN -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Pelanggan
                                </p>

                                <p class="mt-2 text-2xl font-bold text-gray-800">
                                    {{ statistik.jumlahPelanggan }}
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-xl">
                                👥
                            </div>

                        </div>

                        <p class="mt-4 text-xs text-gray-400">
                            Total pelanggan
                        </p>

                    </div>

                </div>

                <!-- CONTENT 2 KOLOM -->
                <div class="mt-8 grid gap-6 xl:grid-cols-2">

                    <!-- GRAFIK -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <div class="mb-6 flex items-center justify-between">

                            <div>
                                <h2 class="text-lg font-bold text-gray-800">
                                    Penjualan 7 Hari Terakhir
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Berdasarkan transaksi penjualan
                                </p>
                            </div>

                            <span class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-medium text-blue-600">
                                7 Hari
                            </span>

                        </div>

                        <div
                            v-if="grafikPenjualan.length === 0"
                            class="flex h-64 items-center justify-center text-gray-400"
                        >
                            Belum ada data penjualan
                        </div>

                        <div
                            v-else
                            class="flex h-64 items-end gap-3 border-b border-gray-100 px-2"
                        >

                            <div
                                v-for="item in grafikPenjualan"
                                :key="item.tanggal"
                                class="flex h-full flex-1 flex-col items-center justify-end gap-2"
                            >

                                <span class="text-[10px] text-gray-500">
                                    {{ formatRupiah(item.total) }}
                                </span>

                                <div
                                    class="w-full max-w-12 rounded-t-lg bg-blue-500 transition-all hover:bg-blue-600"
                                    :style="{
                                        height: `${
                                            Math.max(
                                                (Number(item.total) /
                                                    Math.max(
                                                        ...grafikPenjualan.map(
                                                            data => Number(data.total)
                                                        ),
                                                        1
                                                    )) * 100,
                                                3
                                            )
                                        }%`
                                    }"
                                ></div>

                                <span class="text-xs text-gray-500">
                                    {{ formatTanggal(item.tanggal) }}
                                </span>

                            </div>

                        </div>

                    </div>

                    <!-- TRANSAKSI -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <div class="mb-6 flex items-center justify-between">

                            <div>
                                <h2 class="text-lg font-bold text-gray-800">
                                    Transaksi Terbaru
                                </h2>

                                <p class="text-sm text-gray-500">
                                    5 transaksi terakhir
                                </p>
                            </div>

                            <span class="rounded-lg bg-gray-100 px-3 py-2 text-xs text-gray-500">
                                Terbaru
                            </span>

                        </div>

                        <div
                            v-if="transaksiTerbaru.length === 0"
                            class="flex h-64 items-center justify-center text-gray-400"
                        >
                            Belum ada transaksi
                        </div>

                        <div
                            v-else
                            class="divide-y"
                        >

                            <div
                                v-for="transaksi in transaksiTerbaru"
                                :key="transaksi.id_penjualan"
                                class="flex items-center justify-between gap-4 py-4"
                            >

                                <div class="min-w-0">

                                    <p class="truncate font-semibold text-gray-800">
                                        {{
                                            transaksi.pelanggan?.nama_pelanggan
                                            || 'Pelanggan Umum'
                                        }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ formatTanggal(transaksi.tanggal_penjualan) }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Kasir:
                                        {{
                                            transaksi.user?.nama_lengkap
                                            || '-'
                                        }}
                                    </p>

                                </div>

                                <div class="shrink-0 text-right">

                                    <p class="font-bold text-gray-800">
                                        {{ formatRupiah(transaksi.total_faktur) }}
                                    </p>

                                    <span
                                        class="mt-1 inline-block rounded-full px-2 py-1 text-[10px] font-medium"
                                        :class="
                                            transaksi.status_pembayaran === 'sudah bayar'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-orange-100 text-orange-700'
                                        "
                                    >
                                        {{
                                            transaksi.status_pembayaran
                                            || 'Transaksi'
                                        }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- MENU CEPAT -->
                <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-gray-800">
                        Menu Cepat
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Akses menu utama sistem kasir.
                    </p>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                        <!-- KASIR -->
                        <a
                            href="/kasir"
                            class="rounded-xl border p-4 transition hover:border-blue-400 hover:bg-blue-50"
                        >

                            <div class="text-2xl">
                                🛒
                            </div>

                            <p class="mt-3 font-semibold text-gray-800">
                                Kasir
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Buat transaksi penjualan
                            </p>

                        </a>

                        <!-- PRODUK -->
                        <a
                            href="/barang"
                            class="rounded-xl border p-4 transition hover:border-blue-400 hover:bg-blue-50"
                        >

                            <div class="text-2xl">
                                📦
                            </div>

                            <p class="mt-3 font-semibold text-gray-800">
                                Produk
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Kelola data barang
                            </p>

                        </a>

                        <!-- PELANGGAN -->
                        <a
                            href="/pelanggan"
                            class="rounded-xl border p-4 transition hover:border-blue-400 hover:bg-blue-50"
                        >

                            <div class="text-2xl">
                                👥
                            </div>

                            <p class="mt-3 font-semibold text-gray-800">
                                Pelanggan
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Kelola pelanggan
                            </p>

                        </a>

                        <!-- LAPORAN -->
                        <a
                            href="#"
                            class="rounded-xl border p-4 transition hover:border-blue-400 hover:bg-blue-50"
                        >

                            <div class="text-2xl">
                                📈
                            </div>

                            <p class="mt-3 font-semibold text-gray-800">
                                Laporan
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Lihat laporan penjualan
                            </p>

                        </a>

                    </div>

                </div>

            </div>

        </main>

    </div>
</template>