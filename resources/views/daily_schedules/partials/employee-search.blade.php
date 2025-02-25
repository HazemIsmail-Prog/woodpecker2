<!-- Search and Filter -->
<div class="p-6 border-b border-gray-200 dark:border-gray-700">
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Search input -->
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text"
                x-model="employeeSearch"
                placeholder="Search employees..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-lg focus:ring-[#ac7909] focus:border-[#ac7909] transition-colors">
        </div>

        <!-- Type filter -->
        <select x-model="employeeTypeFilter"
            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-lg focus:ring-[#ac7909] focus:border-[#ac7909] transition-colors">
            <option value="">All Types</option>
            <option value="supervisor">Team Leaders</option>
            <option value="technician">Labors</option>
            <option value="engineer">Engineers</option>
        </select>
    </div>
</div> 