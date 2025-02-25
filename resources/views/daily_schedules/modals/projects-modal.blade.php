<x-modal name="projects-modal" :show="false">
    <div class="p-6 h-dvh min-w-fit overflow-hidden flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Add Project to Schedule</h2>
            <button @click="$dispatch('close-modal', 'projects-modal')" 
                    class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#ac7909] rounded-full p-1">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="mb-6 relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text"
                x-model="projectSearch"
                placeholder="Search projects..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-lg focus:ring-[#ac7909] focus:border-[#ac7909] transition-colors">
        </div>

        <div class="space-y-3 overflow-y-auto hide-scrollbar flex-1">
            <template x-for="project in filteredProjects" :key="project.id">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors"
                    @click="toggleProject(project)"
                    :class="{'border-2 border-[#ac7909]': isProjectSelected(project.id)}">
                    <div class="flex justify-between items-start">
                        <div class="flex flex-col">
                            <div class="font-medium text-gray-900 dark:text-gray-100" x-text="project.name"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-text="project.quotation_number"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400" x-text="project.location"></div>
                        </div>
                        <div class="text-[#ac7909] dark:text-[#ac7909]" x-show="isProjectSelected(project.id)">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-modal> 