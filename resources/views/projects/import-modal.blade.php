<!-- Add this at the bottom of your file, after other modals -->
<x-modal name="import-modal" :show="false">
    <div class="p-6 dark:bg-gray-800">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Import Projects</h2>
        
        <form @submit.prevent="importProjects" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Excel File (xlsx, xls, csv)
                </label>
                <input type="file" 
                       @change="handleFileSelect"
                       accept=".xlsx,.xls,.csv"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md focus:outline-none focus:ring-[#ac7909] focus:border-[#ac7909]">
            </div>

            <div class="text-sm text-gray-500 dark:text-gray-400">
                <p class="font-medium mb-2">Required columns:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>name (required)</li>
                    <li>contract_date (optional, format: YYYY-MM-DD)</li>
                    <li>phone (optional)</li>
                    <li>location (optional)</li>
                    <li>quotation_number (optional)</li>
                    <li>delivery_date (optional, format: YYYY-MM-DD)</li>
                    <li>installation_date (optional, format: YYYY-MM-DD)</li>
                    <li>type_of_work (optional)</li>
                    <li>value (optional, numeric)</li>
                    <li>status (optional, one of: pending, in_progress, completed, cancelled, site_on_hold, site_not_ready)</li>
                    <li>notes (optional)</li>
                </ul>
            </div>

            <div class="mt-4">
                <a href="{{ route('projects.template') }}" 
                   class="text-[#ac7909] hover:text-[#8e6407] dark:text-[#ac7909] dark:hover:text-[#8e6407]">
                    <i class="fas fa-download mr-1"></i>Download Template
                </a>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" 
                        @click="$dispatch('close-modal', 'import-modal')"
                        class="bg-white dark:bg-gray-700 dark:text-gray-300 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ac7909]">
                    Cancel
                </button>
                <button type="submit" 
                        :disabled="!hasFile"
                        :class="{'opacity-50 cursor-not-allowed': !hasFile}"
                        class="bg-[#ac7909] hover:bg-[#8e6407] text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ac7909]">
                    Import
                </button>
            </div>
        </form>
    </div>
</x-modal>