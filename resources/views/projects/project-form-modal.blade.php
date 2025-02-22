<!-- Project Modal -->
<x-modal name="project-modal" :show="false">
    <div class="p-6 dark:bg-gray-800">
        <h2 class="text-lg font-medium text-[#ac7909] dark:text-[#ac7909]" x-text="editingProject ? 'Edit Project' : 'Add New Project'"></h2>
        <form @submit.prevent="saveProject" class="mt-6">
            <!-- Update all form dividers -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-[#ac7909] dark:border-[#ac7909]"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white dark:bg-gray-800 px-3 text-sm text-[#ac7909] dark:text-[#ac7909]">
                        <i class="fas fa-info-circle mr-1"></i>Contact Details
                    </span>
                </div>
            </div>

            <!-- First Row: Name and Quotation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" 
                            x-model="currentProject.name" 
                            :class="{'border-red-500': errors.name}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                    <p x-show="errors.name" x-text="errors.name" class="mt-1 text-sm text-red-600"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quotation Number</label>
                    <input type="text" 
                            x-model="currentProject.quotation_number" 
                            :class="{'border-red-500': errors.quotation_number}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                    <p x-show="errors.quotation_number" x-text="errors.quotation_number" class="mt-1 text-sm text-red-600"></p>
                </div>
            </div>

            <!-- Second Row: Phone and Location -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="tel" 
                            x-model="currentProject.phone"
                            :class="{'border-red-500': errors.phone}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                    <p x-show="errors.phone" x-text="errors.phone" class="mt-1 text-sm text-red-600"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                    <input type="text" 
                            x-model="currentProject.location"
                            :class="{'border-red-500': errors.location}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                    <p x-show="errors.location" x-text="errors.location" class="mt-1 text-sm text-red-600"></p>
                </div>
            </div>

            <!-- Update the Dates section divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-[#ac7909] dark:border-[#ac7909]"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white dark:bg-gray-800 px-3 text-sm text-[#ac7909] dark:text-[#ac7909]">
                        <i class="fas fa-calendar mr-1"></i>Dates
                    </span>
                </div>
            </div>

            <!-- Third Row: Dates -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contract Date</label>
                    <input type="date" 
                            x-model="currentProject.contract_date"
                            @change="setDeliveryDate"
                            :class="{'border-red-500': errors.contract_date}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                    <p x-show="errors.contract_date" x-text="errors.contract_date" class="mt-1 text-sm text-red-600"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Date</label>
                    <input type="date" 
                            x-model="currentProject.delivery_date"
                            @change="setInstallationDate"
                            :class="{'border-red-500': errors.delivery_date}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                    <p x-show="errors.delivery_date" x-text="errors.delivery_date" class="mt-1 text-sm text-red-600"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Installation Date</label>
                    <input type="date" 
                            x-model="currentProject.installation_date"
                            :class="{'border-red-500': errors.installation_date}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                    <p x-show="errors.installation_date" x-text="errors.installation_date" class="mt-1 text-sm text-red-600"></p>
                </div>
            </div>

            <!-- Update the Work Details section divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-[#ac7909] dark:border-[#ac7909]"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white dark:bg-gray-800 px-3 text-sm text-[#ac7909] dark:text-[#ac7909]">
                        <i class="fas fa-cog mr-1"></i>Work Details
                    </span>
                </div>
            </div>

            <!-- Fourth Row: Type of Work and Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type of Work</label>
                <input type="text" 
                        x-model="currentProject.type_of_work"
                        :class="{'border-red-500': errors.type_of_work}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                <p x-show="errors.type_of_work" x-text="errors.type_of_work" class="mt-1 text-sm text-red-600"></p>
            </div>

            <!-- Update the Status & Value section divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-[#ac7909] dark:border-[#ac7909]"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white dark:bg-gray-800 px-3 text-sm text-[#ac7909] dark:text-[#ac7909]">
                        <i class="fas fa-chart-line mr-1"></i>Status & Value
                    </span>
                </div>
            </div>

            <!-- Fifth Row: Value and Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Value (KD)</label>
                    <input type="number" 
                            x-model="currentProject.value"
                            step="0.01"
                            :class="{'border-red-500': errors.value}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                    <p x-show="errors.value" x-text="errors.value" class="mt-1 text-sm text-red-600"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select x-model="currentProject.status" 
                            :class="{'border-red-500': errors.status}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                        <option value="" class="dark:bg-gray-700">Select Status</option>
                        <option value="pending" class="dark:bg-gray-700">Pending</option>
                        <option value="in_progress" class="dark:bg-gray-700">In Progress</option>
                        <option value="completed" class="dark:bg-gray-700">Completed</option>
                        <option value="site_on_hold" class="dark:bg-gray-700">Site On Hold</option>
                        <option value="site_not_ready" class="dark:bg-gray-700">Site Not Ready</option>
                        <option value="cancelled" class="dark:bg-gray-700">Cancelled</option>
                    </select>
                    <p x-show="errors.status" x-text="errors.status" class="mt-1 text-sm text-red-600"></p>

                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea x-model="currentProject.notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" @click="$dispatch('close')" 
                        class="bg-white dark:bg-gray-700 dark:text-gray-300 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-[#ac7909] hover:bg-[#8e6407] py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white">
                    Save
                </button>
            </div>
        </form>
    </div>
</x-modal>