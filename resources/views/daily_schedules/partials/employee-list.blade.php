<!-- Employee List -->
<div class="flex-1 overflow-y-auto p-6">
    <div class="space-y-3">
        <template x-for="employee in searchedEmployees" :key="employee.id">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors"
                @click="toggleEmployee(employee)"
                :class="{'border-2 border-[#ac7909]': isEmployeeSelected(employee.id)}">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="font-medium text-gray-900 dark:text-gray-100" x-text="employee.name"></span>
                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300" x-text="employee.type"></span>
                    </div>
                    <div class="text-[#ac7909] dark:text-[#ac7909]" x-show="isEmployeeSelected(employee.id)">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div> 