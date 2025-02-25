<x-modal name="employee-modal" :show="false">
    <div class="p-6 h-dvh min-w-fit overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                <template x-if="selectedProject">
                    <span>Assign Team for: <span class="text-[#ac7909]" x-text="selectedProject.name"></span></span>
                </template>
                <template x-if="!selectedProject">
                    <span>Select a project first</span>
                </template>
            </h3>
            <button @click="$dispatch('close-modal', 'employee-modal')" 
                    class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#ac7909] rounded-full p-1">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Content -->

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

        <!-- Employee List -->
         <template x-if="selectedProject">
        <div class="space-y-3 overflow-y-auto hide-scrollbar flex-1">
            <template x-for="employee in searchedEmployees" :key="employee.id">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors"
                    @click="toggleEmployee(employee)"
                    :class="{'border-2 border-[#ac7909]': isEmployeeSelected(employee.id)}">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col items-start">
                            <div class="font-medium text-gray-900 dark:text-gray-100" x-text="employee.name"></div>
                            <div class=" text-xs text-gray-700 dark:text-gray-300" x-text="employee.type"></div>
                        </div>
                        <div class="text-[#ac7909] dark:text-[#ac7909]" x-show="isEmployeeSelected(employee.id)">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        </template>
        <template x-if="!selectedProject">
            <div class="flex justify-center items-center h-full">
                <span class="text-gray-400">Select a project first</span>
            </div>
        </template>
    </div>
</x-modal> 