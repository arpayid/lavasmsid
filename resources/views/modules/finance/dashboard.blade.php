<x-admin-layout heading="Finance Dashboard">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Finance Dashboard</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Total Invoices</h3>
                            <p class="text-3xl font-bold">{{ number_format($data['totalInvoices'] ?? 0) }}</p>
                        </div>

                        <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Total Billed</h3>
                            <p class="text-3xl font-bold">Rp {{ number_format($data['totalBilled'] ?? 0, 0, ',', '.') }}</p>
                        </div>

                        <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Total Paid</h3>
                            <p class="text-3xl font-bold">Rp {{ number_format($data['totalPaid'] ?? 0, 0, ',', '.') }}</p>
                        </div>

                        <div class="bg-red-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Outstanding</h3>
                            <p class="text-3xl font-bold">Rp {{ number_format($data['outstanding'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
