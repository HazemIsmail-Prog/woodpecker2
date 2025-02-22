<!-- Schedule Modal -->
<x-modal name="schedule-modal" :show="false">
    <div class="p-6 dark:bg-gray-800">
        <h2 class="text-lg font-medium text-[#ac7909] dark:text-[#ac7909]">Add Schedule</h2>
        <form @submit.prevent="saveSchedule" class="mt-6">
            <!-- Project Details Section -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-[#ac7909] dark:border-[#ac7909]"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white dark:bg-gray-800 px-3 text-sm text-[#ac7909] dark:text-[#ac7909]">
                        <i class="fas fa-info-circle mr-1"></i>Schedule Details
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project Name</label>
                    <p class="mt-1 text-[#ac7909] dark:text-[#ac7909]" x-text="currentProject?.name"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration</label>
                    <input id="duration" ref="duration" type="number" x-model="scheduleForm.duration" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select x-model="scheduleForm.status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="site_on_hold">Site On Hold</option>
                        <option value="site_not_ready">Site Not Ready</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea x-model="scheduleForm.notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50"></textarea>
                </div>
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