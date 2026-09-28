<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'

const barang = ref<any[]>([])
const loading = ref(true)

const keranjang = ref<any[]>([])
const pencarian = ref('')

const diskonTipe = ref('nominal')
const diskonNilai = ref(0)
const jumlahBayar = ref(0)

const tambahKeKeranjang = (item: any) => {
    if (Number(item.stok) <= 0) {
        return
    }

    const sudahAda = keranjang.value.find(
        (produk) => produk.id_barang === item.id_barang
    )

    if (sudahAda) {
        if (sudahAda.jumlah < Number(item.stok)) {
            sudahAda.jumlah++
        }
    } else {
        keranjang.value.push({
            ...item,
            jumlah: 1,
        })
    }
}

const tambahJumlah = (item: any) => {
    if (item.jumlah < Number(item.stok)) {
        item.jumlah++
    }
}

const kurangiJumlah = (item: any) => {
    if (item.jumlah > 1) {
        item.jumlah--
    }
}

const hapusDariKeranjang = (idBarang: number) => {
    keranjang.value = keranjang.value.filter(
        (item) => item.id_barang !== idBarang
    )
}

const bersihkanKeranjang = () => {
    keranjang.value = []
}

const bayar = async () => {
    if (keranjang.value.length === 0) {
        alert('Keranjang masih kosong')
        return
    }

    if (jumlahBayar.value < total.value) {
    alert('Uang pembayaran kurang')
    return
}

    const token = localStorage.getItem('token')

    try {
        // 1. Buat transaksi penjualan
        const responsePenjualan = await fetch('/api/penjualan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({
                total_bayar: jumlahBayar.value,
            }),
        })

        if (!responsePenjualan.ok) {
            const error = await responsePenjualan.json()
            console.error(error)
            alert('Gagal membuat transaksi')
            return
        }

        const penjualan = await responsePenjualan.json()

        // 2. Simpan setiap barang ke detail penjualan
        for (const item of keranjang.value) {
            const responseDetail = await fetch('/api/detail-penjualan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    Authorization: `Bearer ${token}`,
                },
                body: JSON.stringify({
                    id_penjualan: penjualan.id_penjualan,
                    id_barang: item.id_barang,
                    jumlah_barang: item.jumlah,
                    harga_beli: item.harga_beli,
                    harga_jual: item.harga_jual,
                    diskon_tipe: diskonTipe.value,
                    diskon_nilai: diskonNilai.value,
                }),
            })

            if (!responseDetail.ok) {
                const error = await responseDetail.json()
                console.error(error)
                alert('Transaksi dibuat, tetapi detail barang gagal disimpan')
                return
            }
        }

        alert('Pembayaran berhasil')

        console.log('Penjualan:', penjualan)

        // Ambil stok terbaru dari database
        await ambilBarang()

        // Kosongkan keranjang setelah berhasil
        keranjang.value = []

        // Kosongkan keranjang setelah berhasil
        keranjang.value = []
        diskonNilai.value = 0
        diskonTipe.value = 'nominal'

    } catch (error) {
        console.error(error)
        alert('Terjadi kesalahan saat memproses pembayaran')
    }
}

const subtotal = computed(() => {
    return keranjang.value.reduce((total, item) => {
        return total + Number(item.harga_jual) * item.jumlah
    }, 0)
})

const total = computed(() => {
    let potongan = 0

    if (diskonTipe.value === 'persen') {
        potongan = subtotal.value * (diskonNilai.value / 100)
    } else {
        potongan = diskonNilai.value
    }

    if (potongan > subtotal.value) {
        potongan = subtotal.value
    }

    return subtotal.value - potongan
})

const barangTerfilter = computed(() => {
    const keyword = pencarian.value.toLowerCase().trim()

    if (!keyword) {
        return barang.value
    }

    return barang.value.filter((item) => {
        return (
            String(item.nama ?? '')
                .toLowerCase()
                .includes(keyword) ||
            String(item.barcode ?? '')
                .toLowerCase()
                .includes(keyword)
        )
    })
})

const formatRupiah = (nilai: number | string) => {
    return Number(nilai).toLocaleString('id-ID')
}

const ambilBarang = async () => {
    const token = localStorage.getItem('token')

    const response = await fetch('/api/barang', {
        headers: {
            Accept: 'application/json',
            Authorization: `Bearer ${token}`,
        },
    })

    if (response.ok) {
        barang.value = await response.json()
    }

    loading.value = false
}

onMounted(() => {
    ambilBarang()
})
</script>

<template>
    <Head title="Kasir / Transaksi" />

    <div class="min-h-screen bg-gray-100">

        <!-- ================= HEADER ================= -->
        <header
            class="fixed left-0 right-0 top-0 z-30 h-16 border-b bg-white"
        >
            <div class="flex h-full items-center">

                <!-- LOGO -->
            <div
                class="flex h-full w-64 items-center gap-3 border-r border-slate-800 bg-slate-900 px-5"
            >
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-lg text-white"
                >
                    🏪
                </div>

                <div>
                    <p class="text-sm font-bold text-white">
                        GS Mart
                    </p>

                    <p class="text-[10px] text-slate-400">
                        Sistem Kasir
                    </p>
                </div>
            </div>

                <!-- HEADER KANAN -->
                <div
                    class="flex flex-1 items-center justify-between px-6"
                >

                    <div class="flex items-center gap-4">
                        <button
                            class="text-xl text-gray-600 hover:text-gray-900"
                        >
                            ☰
                        </button>

                        <div class="h-6 w-px bg-gray-200"></div>

                        
                    </div>

                    <div class="flex items-center gap-5">

                        <button class="text-lg text-gray-600">
                            🔔
                        </button>

                        <div class="h-7 w-px bg-gray-200"></div>

                        <div class="flex items-center gap-2">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200"
                            >
                                👤
                            </div>

                            <span class="text-sm font-medium text-gray-700">
                                Admin
                            </span>

                            <span class="text-xs text-gray-400">
                                ▼
                            </span>
                        </div>

                    </div>

                </div>

            </div>
        </header>

        <!-- ================= SIDEBAR ================= -->
        <aside
            class="fixed bottom-0 left-0 top-16 z-20 hidden w-64 border-r border-slate-800 bg-slate-900 lg:block"
        >
            <nav class="p-3">

                <p
                    class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-wide text-gray-400"
                >
                    Menu Utama
                </p>

                <a
                    href="/dashboard"
                    class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-gray-600 hover:bg-gray-100"
                >
                    <span>⌂</span>
                    Dashboard
                </a>
                <a
                    href="/kasir"
                    class="mb-1 flex items-center gap-3 rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium text-white"
                >
                
                    <span>▣</span>
                    Kasir / Transaksi
                </a>

                <a
                    href="/pembelian"
                    class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-gray-600 hover:bg-gray-100"
                >
                    <span>🛒</span>
                    Pembelian
                </a>

                <a
                    href="#"
                    class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-gray-600 hover:bg-gray-100"
                >
                    <span>📦</span>
                    Produk
                </a>

                <a
                    href="#"
                    class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-gray-600 hover:bg-gray-100"
                >
                    <span>👥</span>
                    Pelanggan
                </a>

                <a
                    href="#"
                    class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-gray-600 hover:bg-gray-100"
                >
                    <span>🚚</span>
                    Supplier
                </a>

                <a
                    href="#"
                    class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-gray-600 hover:bg-gray-100"
                >
                    <span>👤</span>
                    User
                </a>

                <a
                    href="#"
                    class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-gray-600 hover:bg-gray-100"
                >
                    <span>📄</span>
                    Laporan
                </a>

                <a
                    href="#"
                    class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-gray-600 hover:bg-gray-100"
                >
                    <span>⚙</span>
                    Pengaturan
                </a>

            </nav>
        </aside>

        <!-- ================= CONTENT ================= -->
        <main class="pt-16 lg:ml-64">

            <div class="p-5 md:p-7">

                <!-- TAB -->
                <div class="mb-5 flex gap-7 border-b">

                    <button
                        class="border-b-2 border-gray-900 px-1 pb-3 text-sm font-semibold text-gray-900"
                    >
                        Transaksi Kasir
                    </button>

                    <button
                        class="px-1 pb-3 text-sm text-gray-500 hover:text-gray-900"
                    >
                        Data Pelanggan
                    </button>

                </div>

                <!-- GRID UTAMA -->
                <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_300px]">

                    <!-- ================= TRANSAKSI ================= -->
                    <div>

                        <!-- SEARCH PRODUK -->
                        <div
                            class="mb-4 flex items-center rounded-lg border bg-white px-4 shadow-sm"
                        >
                            <span class="mr-3 text-gray-400">
                                🔍
                            </span>

                            <input
                                v-model="pencarian"
                                type="text"
                                placeholder="Cari produk (barcode / nama)..."
                                class="w-full border-0 py-3 text-sm outline-none focus:ring-0"
                            />

                            <button
                                class="ml-3 rounded-md border px-3 py-2 text-sm text-gray-600"
                            >
                                ▣
                            </button>
                        </div>

                        <!-- DAFTAR PRODUK -->
                        <div class="rounded-lg border bg-white shadow-sm">

                            <div
                                class="border-b px-4 py-3"
                            >
                                <h2 class="text-sm font-bold text-gray-800">
                                    Daftar Produk
                                </h2>
                            </div>

                            <div
                                v-if="loading"
                                class="p-8 text-center text-sm text-gray-500"
                            >
                                Memuat produk...
                            </div>

                            <div
                                v-else
                                class="max-h-[420px] overflow-y-auto"
                            >

                                <div
                                    v-if="barangTerfilter.length === 0"
                                    class="p-8 text-center text-sm text-gray-500"
                                >
                                    Produk tidak ditemukan.
                                </div>

                                <div
                                    v-for="item in barangTerfilter"
                                    :key="item.id_barang"
                                    class="grid cursor-pointer grid-cols-[40px_minmax(0,1fr)_90px_55px_100px_40px] items-center gap-2 border-b px-4 py-3 text-sm hover:bg-gray-50"
                                    @click="tambahKeKeranjang(item)"
                                >

                                    <span class="text-gray-400">
                                        {{ item.id_barang }}
                                    </span>

                                    <div>
                                        <p class="font-medium text-gray-800">
                                            {{ item.nama }}
                                        </p>

                                        <p
                                            v-if="item.barcode"
                                            class="text-[11px] text-gray-400"
                                        >
                                            {{ item.barcode }}
                                        </p>
                                    </div>

                                    <span class="text-gray-700">
                                        Rp {{ formatRupiah(item.harga_jual) }}
                                    </span>

                                    <span class="text-center">
                                        {{ item.stok }}
                                    </span>

                                    <span
                                        class="text-gray-700"
                                    >
                                        Rp {{
                                            formatRupiah(
                                                Number(item.harga_jual)
                                            )
                                        }}
                                    </span>

                                    <span class="text-gray-400">
                                        +
                                    </span>

                                </div>

                            </div>

                        </div>

                        <!-- RINGKASAN -->
                        <div
                            class="mt-4 rounded-lg border bg-white p-5 shadow-sm"
                        >

                            <div
                                class="mb-4 flex items-center justify-between border-b pb-3"
                            >
                                <span class="text-sm text-gray-600">
                                    Subtotal
                                </span>

                                <span class="font-semibold text-gray-800">
                                    Rp {{ subtotal.toLocaleString('id-ID') }}
                                </span>
                            </div>

                            <div
                                class="mb-4 flex items-center justify-between border-b pb-3"
                            >
                                <span class="text-sm text-gray-600">
                                    Diskon
                                </span>

                                <div class="flex gap-2">
                                    <select 
                                        v-model="diskonTipe"
                                        class="rounded-md border px-3 py-2 text-sm"
                                    >
                                        <option value="nominal">Nominal</option>
                                        <option value="persen">Persen</option>
                                    </select>

                                    <input 
                                        v-model.number="diskonNilai"
                                        type="number"
                                        min="0"
                                        class="w-24 rounded-md border px-3 py-2 text-right text-sm"
                                    />
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between"
                            >
                                <span class="text-base font-bold text-gray-800">
                                    Total
                                </span>

                                <span class="text-xl font-bold text-gray-900">
                                    Rp {{ total.toLocaleString('id-ID') }}
                                </span>
                            </div>

                        </div>

                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-sm text-gray-600">
                                Kembalian
                            </span>

                            <span class="text-lg font-bold text-green-600">
                                Rp {{ formatRupiah(Math.max(0, jumlahBayar - total)) }}
                            </span>
                        </div>

                                            <!-- JUMLAH BAYAR -->
                    <div class="mt-4 flex items-center justify-between border-t pt-4">
                        <span class="text-sm text-gray-600">
                            Bayar
                        </span>

                        <input
                            v-model.number="jumlahBayar"
                            type="number"
                            min="0"
                            class="w-32 rounded-md border px-3 py-2 text-right text-sm"
                            placeholder="0"
                        />
                    </div>

                                     <!-- TOMBOL -->
                    <div class="mt-4 flex justify-end gap-3">

                        <button
                            type="button"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            @click="bersihkanKeranjang"
                        >
                            🗑 Bersihkan
                        </button>

                        <button
                            type="button"
                            class="rounded-lg bg-gray-900 px-7 py-3 text-sm font-semibold text-white hover:bg-gray-800"
                            @click="bayar"
                        >
                            ▣ Bayar
                        </button>

                    </div>
                    </div>

                    <!-- ================= PELANGGAN ================= -->
                    <div>

                        <div
                            class="rounded-lg border bg-white p-4 shadow-sm"
                        >

                            <div
                                class="mb-4 flex items-center justify-between"
                            >
                                <h2 class="text-sm font-bold text-gray-800">
                                    Pelanggan (Opsional)
                                </h2>
                            </div>

                            <div class="flex gap-2">

                                <div
                                    class="flex flex-1 items-center rounded-md border px-3"
                                >
                                    <span class="mr-2 text-gray-400">
                                        🔍
                                    </span>

                                    <input
                                        type="text"
                                        placeholder="Cari pelanggan..."
                                        class="w-full border-0 py-2 text-sm outline-none focus:ring-0"
                                    />
                                </div>

                                <button
                                    class="rounded-md bg-gray-800 px-3 text-white"
                                >
                                    +
                                </button>

                            </div>

                            <div class="mt-4 space-y-2 text-sm">

                                <div class="flex justify-between">
                                    <span class="text-gray-500">
                                        Nama
                                    </span>

                                    <span class="font-medium text-gray-800">
                                        -
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500">
                                        No. HP
                                    </span>

                                    <span class="font-medium text-gray-800">
                                        -
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500">
                                        Poin
                                    </span>

                                    <span class="font-medium text-gray-800">
                                        -
                                    </span>
                                </div>

                            </div>

                            <button
                                class="mt-4 w-full rounded-md bg-gray-800 py-2.5 text-sm font-semibold text-white"
                            >
                                Pilih Pelanggan
                            </button>

                        </div>

                        <!-- PELANGGAN TERAKHIR -->
                        <div
                            class="mt-4 rounded-lg border bg-white p-4 shadow-sm"
                        >

                            <h2 class="mb-3 text-sm font-bold text-gray-800">
                                Pelanggan Terakhir
                            </h2>

                            <p class="text-sm text-gray-500">
                                Belum ada pelanggan.
                            </p>

                        </div>

                        <!-- KERANJANG -->
                        <div
                            class="mt-4 rounded-lg border bg-white p-4 shadow-sm"
                        >

                            <h2 class="mb-4 text-sm font-bold text-gray-800">
                                Keranjang
                            </h2>

                            <div
                                v-if="keranjang.length === 0"
                                class="py-5 text-center text-sm text-gray-400"
                            >
                                Belum ada produk.
                            </div>

                            <div
                                v-else
                                class="space-y-4"
                            >

                                <div
                                    v-for="item in keranjang"
                                    :key="item.id_barang"
                                    class="border-b pb-4"
                                >

                                    <div
                                        class="flex items-start justify-between gap-2"
                                    >

                                        <div>
                                            <p
                                                class="text-sm font-semibold text-gray-800"
                                            >
                                                {{ item.nama }}
                                            </p>

                                            <p
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Rp {{
                                                    formatRupiah(
                                                        item.harga_jual
                                                    )
                                                }}
                                            </p>
                                        </div>

                                        <button
                                            type="button"
                                            class="text-gray-400 hover:text-red-600"
                                            @click="
                                                hapusDariKeranjang(
                                                    item.id_barang
                                                )
                                            "
                                        >
                                            🗑
                                        </button>

                                    </div>

                                    <div
                                        class="mt-3 flex items-center justify-between"
                                    >

                                        <div class="flex items-center gap-2">

                                            <button
                                                type="button"
                                                class="h-7 w-7 rounded border text-sm hover:bg-gray-100"
                                                @click="kurangiJumlah(item)"
                                            >
                                                −
                                            </button>

                                            <span
                                                class="w-6 text-center text-sm font-semibold"
                                            >
                                                {{ item.jumlah }}
                                            </span>

                                            <button
                                                type="button"
                                                class="h-7 w-7 rounded border text-sm hover:bg-gray-100"
                                                @click="tambahJumlah(item)"
                                            >
                                                +
                                            </button>

                                        </div>

                                        <span
                                            class="text-sm font-semibold text-gray-800"
                                        >
                                            Rp {{
                                                formatRupiah(
                                                    Number(item.harga_jual) *
                                                    item.jumlah
                                                )
                                            }}
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>
</template>