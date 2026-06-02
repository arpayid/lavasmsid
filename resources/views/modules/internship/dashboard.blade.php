<x-admin-layout heading="PKL / Internship Dashboard">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">PKL / Internship Dashboard</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                        <div class="bg-indigo-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Total Partners</h3>
                            <p class="text-3xl font-bold">{{ $totalPartners }}</p>
                        </div>
                        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Total PKL</h3>
                            <p class="text-3xl font-bold">{{ $totalInternships }}</p>
                        </div>
                        <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Planned</h3>
                            <p class="text-3xl font-bold">{{ $plannedInternships }}</p>
                        </div>
                        <div class="bg-emerald-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Ongoing</h3>
                            <p class="text-3xl font-bold">{{ $ongoingInternships }}</p>
                        </div>
                        <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Completed</h3>
                            <p class="text-3xl font-bold">{{ $completedInternships }}</p>
                        </div>
                    </div>

                    <h2 class="text-xl font-semibold mb-4">Recent Internships</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="border-b bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Student</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Partner</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentInternships as $internship)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $internship->student->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $internship->industryPartner->name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <x-admin.badge :label="ucfirst($internship->status)" :variant="$internship->status == 'completed' ? 'success' : ($internship->status == 'ongoing' ? 'primary' : 'warning')" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-slate-500">Belum ada data PKL.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
