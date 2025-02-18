<x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Projects Timeline') }}
                </h2>
                <div id="new"></div>
            </div>
        </x-slot>

    <div class="h-[calc(100vh-138px)] bg-gray-100 dark:bg-gray-900">
        <div class="h-full mx-auto bg-gray-100 dark:bg-gray-900">
            <div class="h-full text-gray-900 dark:text-gray-100" x-data="indexComponent()">
                <div class="flex h-full p-4 gap-4">
                    <!-- Projects List - Fixed height -->
                    <div class="w-1/4 bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col">
                        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Projects</h2>
                        
                        <!-- Search input - Fixed -->
                        <div class="mb-4 flex-shrink-0">
                            <input type="text"
                                x-model="searchQuery"
                                placeholder="Search projects..."
                                class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-900 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Projects list - Scrollable -->
                        <div class="overflow-y-auto flex-1 hide-scrollbar">
                            <div class="space-y-2">
                                <template x-for="project in filteredProjects" :key="project.id">
                                    <div class="p-3 rounded cursor-move bg-gray-100 dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-800"
                                        draggable="true"
                                        @dragstart="draggedProject = project">
                                        <div class="flex flex-col justify-between">
                                            <div x-text="project.name"></div>
                                            <div x-text="getFromattedDate(project.installation_date)"></div>
                                            <div x-text="project.duration + ' days'"></div>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- Add empty state for no results -->
                                <div x-show="!filteredProjects.length" 
                                    class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">
                                    No projects found
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Calendar Timeline - Fixed height with scrollable content -->
                    <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col">
                        <!-- Month navigation - Fixed -->
                        <div class="flex justify-between items-center mb-4 flex-shrink-0">
                            <button @click="previousMonth" 
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-md flex items-center">
                                <svg class="w-4 h-4 mr-1 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                <span class="mr-1">Previous</span>
                            </button>
                            <h2 class="text-xl font-bold" x-text="currentMonth.toLocaleString('default', { month: 'long', year: 'numeric' })"></h2>
                            <button @click="nextMonth" 
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-md flex items-center">
                                <span class="mr-1">Next</span>
                                <svg class="w-4 h-4 ml-1 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Calendar content -->
                        <div class="flex-1 overflow-hidden">
                            <!-- Days header - Fixed -->
                            <div class="flex border-b border-gray-200 dark:border-gray-700">
                                <template x-for="day in getDaysInMonth()" :key="day">
                                    <div class="w-[40px] shrink-0 border border-gray-200 dark:border-gray-600"
                                        :class="{
                                            'bg-gray-50 dark:bg-gray-900': isFriday(day),
                                            'bg-red-50 dark:bg-red-900 dark:bg-opacity-30': isToday(day)
                                        }">
                                        <div class="text-center py-2">
                                            <div class="font-bold" x-text="day"></div>
                                            <div class="text-xs" 
                                                x-text="getWeekDay(day)">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Scrollable rows container -->
                            <div class="overflow-y-auto h-[calc(100%-3rem)] hide-scrollbar">
                                <div class="relative">
                                    <template x-for="i in rowsCount" :key="i">
                                        <div 
                                            class="flex h-[80px] select-none"
                                            @dragover.prevent="currentRow = i"
                                            @drop.prevent="
                                                const rect = $el.getBoundingClientRect();
                                                const day = Math.ceil(($event.clientX - rect.left) / 40);
                                                handleDrop(day, currentRow);"
                                        >
                                            <template x-for="day in getDaysInMonth()" :key="day">
                                                <div 
                                                    class="w-[40px] shrink-0 border h-full border-gray-200 dark:border-gray-600"
                                                    :class="{
                                                        'bg-gray-50 dark:bg-gray-900': isFriday(day),
                                                        'bg-red-50 dark:text-red-500 dark:bg-red-900 dark:bg-opacity-30': isToday(day)
                                                    }"
                                                    @dragover.prevent
                                                >
                                                    <template 
                                                        x-for="project in scheduledProjects.filter(p => {
                                                            const projectStart = new Date(p.start_date);
                                                            const projectEnd = new Date(p.end_date);
                                                            const monthStart = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);
                                                            const monthEnd = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0);
                                                            return p.row === i && !(projectEnd < monthStart || projectStart > monthEnd);
                                                        })" 
                                                        :key="project.id"
                                                    >
                                                        <div
                                                            :class="[project.color]"
                                                            class="absolute bg-indigo-100 dark:bg-indigo-900 h-[70px] m-1 p-2 rounded-lg overflow-hidden text-sm group cursor-move"
                                                            @dblclick.stop="editingProject = {...project}"
                                                            draggable="true"
                                                            @dragstart.stop="
                                                                event.stopPropagation();
                                                                draggedProject = {...project, isScheduled: true};
                                                                scheduledProjects = scheduledProjects.filter(p => p.id !== project.id);
                                                            "
                                                            @mousedown="startMove($event, project)"
                                                            :style="`
                                                                left: ${Math.max(0, (new Date(project.start_date).getMonth() === currentMonth.getMonth() 
                                                                    ? new Date(project.start_date).getDate() - 1 
                                                                    : 0)) * 40}px; 
                                                                width: ${(Math.min(
                                                                    new Date(project.end_date).getMonth() === currentMonth.getMonth() 
                                                                        ? new Date(project.end_date).getDate()
                                                                        : new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0).getDate(),
                                                                    getDaysInMonth().length
                                                                ) - Math.max(
                                                                    new Date(project.start_date).getMonth() === currentMonth.getMonth()
                                                                        ? new Date(project.start_date).getDate() - 1
                                                                        : 0,
                                                                    0
                                                                )) * 40 - 8}px;
                                                                `"
                                                        >
                                                            <div class="flex flex-col h-full">
                                                                <div class="flex justify-between items-start">
                                                                    <span class="font-medium truncate" x-text="project.project.name"></span>
                                                                    <button @click.stop="deleteSchedule(project.id)"
                                                                            class="text-gray-400 hover:text-red-500 p-1 -mt-1 -mr-1">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                                    <span x-text="`${new Date(project.start_date).getDate()}/${new Date(project.start_date).getMonth() + 1} - ${new Date(project.end_date).getDate()}/${new Date(project.end_date).getMonth() + 1}`"></span>
                                                                </div>
                                                                <div class="text-xs mt-auto text-gray-600 dark:text-gray-400">
                                                                    <span x-text="`${project.project.duration} days`"></span>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="absolute right-0 top-0 bottom-0 w-[4px] cursor-e-resize bg-gray-300 bg-opacity-50 hover:bg-opacity-100"
                                                                        x-show="new Date(project.end_date).getMonth() === currentMonth.getMonth()"
                                                                @mousedown.prevent.stop="startResize($event, project)"
                                                            ></div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function indexComponent(){
        return {

            projects: [],
            scheduledProjects: [],
            currentMonth: new Date(),
            draggedProject: null,
            currentRow: null,
            rowsCount: 30,
            resizingSchedule: null,
            startResizeX: 0,
            originalEndDate: null,
            movingSchedule: null,
            startMoveX: 0,
            startMoveY: 0,
            rowHeight: 80, // Height of each row
            editingProject: null,
            searchQuery: '',

            init(){
                this.fetchData();
            },

            deleteSchedule(id){
                axios.delete('/schedules/'+id)
                .then(response => {
                    this.fetchData();
                });
            },

            getFromattedDate(date){
                return new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            },

            fetchData(){

                const month = this.currentMonth.getMonth() + 1; // Add 1 since getMonth() is 0-based
                const year = this.currentMonth.getFullYear();
                const monthStr = month.toString().padStart(2, '0');
                const yearStr = year.toString();

                axios.get('/schedules/'+monthStr+'/'+yearStr)
                .then(response => {
                    this.projects = response.data.projects;
                    this.scheduledProjects = response.data.scheduledProjects;
                }).catch(error => {
                    console.error('Error fetching data:', error);
                });
            },
            
            getDaysInMonth() {
                const year = this.currentMonth.getFullYear();
                const month = this.currentMonth.getMonth();
                const days = new Date(year, month + 1, 0).getDate();
                return Array.from({length: days}, (_, i) => i + 1);
            },

            isFriday(day) {
                const date = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth(), day);
                return date.getDay() === 5;
            },

            getWeekDay(day) {
                const date = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth(), day);
                return date.toLocaleDateString('en-US', {weekday: 'short'});
            },

            handleDrop(day, row) {
                if (!day || day > this.getDaysInMonth().length) return;
                
                if (this.draggedProject) {
                    const startDate = new Date(
                        this.currentMonth.getFullYear(),
                        this.currentMonth.getMonth(),
                        day,
                        12
                    );
                    
                    let duration = this.draggedProject.duration;
                    if (this.draggedProject.isScheduled) {
                        const oldStart = new Date(this.draggedProject.start_date);
                        const oldEnd = new Date(this.draggedProject.end_date);
                        duration = Math.round((oldEnd - oldStart) / (1000 * 60 * 60 * 24)) + 1;
                    }
                    
                    const endDate = new Date(startDate);
                    endDate.setDate(endDate.getDate() + duration - 1);
                    
                    if (!this.isInCurrentMonth(endDate)) return;
                    
                    if (this.checkOverlap(startDate, endDate, row)) {
                        alert('Cannot place project here - overlaps with existing project');
                        return;
                    }
                    this.scheduledProjects.push({
                        ...this.draggedProject,
                        color: this.draggedProject.color || 'bg-blue-200',
                        start_date: startDate.toISOString().slice(0, 10),
                        end_date: endDate.toISOString().slice(0, 10),
                        row: row
                    });

                    axios.post(`/schedules/${this.draggedProject.id}`, {
                        row: row,
                        color: this.draggedProject.color,
                        start_date: startDate.toISOString().slice(0, 10),
                        end_date: endDate.toISOString().slice(0, 10),
                        duration: duration
                    }).then(response => {
                        this.fetchData();
                    });
                }

                this.draggedProject = null;
                this.currentRow = null;
            },

            previousMonth() {
                this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() - 1);
                this.fetchData();
            },
            nextMonth() {
                this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() + 1);
                this.fetchData();
            },

            get filteredProjects() {
                const query = this.searchQuery.toLowerCase().trim();
                const unscheduledProjects = this.projects.filter(p => 
                    !this.scheduledProjects.some(sp => sp.id === p.id)
                );
                
                if (!query) return unscheduledProjects;
                
                return unscheduledProjects.filter(project => 
                    project.name.toLowerCase().includes(query)
                );
            },

            startResize(event, schedule) {
                event.stopPropagation();
                this.resizingSchedule = schedule;
                this.startResizeX = event.clientX;
                this.originalEndDate = new Date(schedule.end_date);
                const startDate = new Date(schedule.start_date);
                const newEndDate = new Date(schedule.end_date);
                
                const handleMouseMove = (e) => {
                    if (!this.resizingSchedule) return;
                    e.preventDefault();
                    
                    const deltaX = e.clientX - this.startResizeX;
                    const daysDelta = Math.round(deltaX / 40);
                    
                    newEndDate.setDate(this.originalEndDate.getDate() + daysDelta);
                    
                    // Prevent resizing outside current month
                    if (!this.isInCurrentMonth(newEndDate)) return;
                    
                    if (newEndDate >= startDate) {
                        // Check for overlaps before updating
                        if (!this.checkOverlap(
                            startDate,
                            newEndDate,
                            schedule.row,
                            schedule.id
                        )) {
                            const scheduleIndex = this.scheduledProjects.findIndex(p => p.id === schedule.id);
                            if (scheduleIndex !== -1) {
                                const updatedSchedule = {...this.scheduledProjects[scheduleIndex]};
                                updatedSchedule.end_date = newEndDate.toISOString().slice(0, 10);
                                // Calculate duration including both start and end dates
                                const duration = Math.round((newEndDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                                updatedSchedule.project.duration = duration;
                                this.scheduledProjects[scheduleIndex] = updatedSchedule;
                            }
                        }
                    }
                };
                
                const handleMouseUp = () => {
                    if (this.resizingSchedule) {
                        const duration = Math.round((newEndDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                        
                        axios.put(`/schedules/${this.resizingSchedule.id}`, {
                            start_date: this.resizingSchedule.start_date,
                            end_date: newEndDate.toISOString().slice(0, 10),
                            row: this.resizingSchedule.row,
                            duration: duration
                        }).then(response => {
                            this.fetchData();
                        }).catch(error => {
                            console.error('Error updating schedule:', error);
                            this.fetchData(); // Refresh data on error to ensure consistency
                        });
                    }
                    
                    this.resizingSchedule = null;
                    this.startResizeX = 0;
                    this.originalEndDate = null;
                    document.removeEventListener('mousemove', handleMouseMove);
                    document.removeEventListener('mouseup', handleMouseUp);
                };
                
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
            },

            checkOverlap(startDate, endDate, row, excludeScheduleId = null) {
                return this.scheduledProjects.some(schedule => {
                    if (excludeScheduleId && schedule.id === excludeScheduleId) return false;
                    if (schedule.row !== row) return false;
                    
                    const scheduleStart = new Date(schedule.start_date);
                    const scheduleEnd = new Date(schedule.end_date);
                    
                    return (
                        (startDate <= scheduleEnd && startDate >= scheduleStart) ||
                        (endDate >= scheduleStart && endDate <= scheduleEnd) ||
                        (startDate <= scheduleStart && endDate >= scheduleEnd)
                    );
                });
            },

            startMove(event, schedule) {
                if (event.target.classList.contains('resize-handle') || 
                    event.target.classList.contains('invisible')) return;
                
                event.preventDefault();
                this.movingSchedule = schedule;
                this.startMoveX = event.clientX;
                this.startMoveY = event.clientY;
                this.originalStartDate = new Date(schedule.start_date);
                this.originalEndDate = new Date(schedule.end_date);
                
                const handleMouseMove = (e) => {
                    if (!this.movingSchedule) return;
                    
                    const deltaX = e.clientX - this.startMoveX;
                    const deltaY = e.clientY - this.startMoveY;
                    
                    const daysDelta = Math.round(deltaX / 40);
                    const rowDelta = Math.round(deltaY / this.rowHeight);
                    
                    const newStartDate = new Date(this.originalStartDate);
                    newStartDate.setDate(this.originalStartDate.getDate() + daysDelta);
                    
                    const newEndDate = new Date(this.originalEndDate);
                    newEndDate.setDate(this.originalEndDate.getDate() + daysDelta);
                    
                    // Prevent moving outside current month
                    if (!this.isInCurrentMonth(newStartDate) || !this.isInCurrentMonth(newEndDate)) return;
                    
                    const newRow = Math.max(1, Math.min(this.rowsCount, schedule.row + rowDelta));
                    
                    // Check for overlaps before updating
                    if (!this.checkOverlap(
                        newStartDate,
                        newEndDate,
                        newRow,
                        schedule.id
                    )) {
                        const projectIndex = this.scheduledProjects.findIndex(p => p.id === schedule.id);
                        if (projectIndex !== -1) {
                            const updatedSchedule = {...this.scheduledProjects[projectIndex]};
                            updatedSchedule.start_date = newStartDate.toISOString().slice(0, 10);
                            updatedSchedule.end_date = newEndDate.toISOString().slice(0, 10);
                            updatedSchedule.row = newRow;
                            this.scheduledProjects[projectIndex] = updatedSchedule;
                        }
                    }
                };
                
                const handleMouseUp = () => {
                    if (this.movingSchedule) {
                        const projectIndex = this.scheduledProjects.findIndex(p => p.id === this.movingSchedule.id);
                        if (projectIndex !== -1) {
                            const updatedSchedule = this.scheduledProjects[projectIndex];
                            const duration = Math.round(
                                (new Date(updatedSchedule.end_date) - new Date(updatedSchedule.start_date)) / (1000 * 60 * 60 * 24)
                            ) + 1;
                            
                            // Send update to server
                            axios.put(`/schedules/${this.movingSchedule.id}`, {
                                start_date: updatedSchedule.start_date,
                                end_date: updatedSchedule.end_date,
                                row: updatedSchedule.row,
                                duration: duration
                            }).catch(error => {
                                console.error('Error updating schedule:', error);
                                // Revert changes on error
                                this.scheduledProjects[projectIndex] = {...this.movingSchedule};
                            });
                        }
                    }
                    
                    this.movingSchedule = null;
                    this.startMoveX = 0;
                    this.startMoveY = 0;
                    this.originalStartDate = null;
                    this.originalEndDate = null;
                    document.removeEventListener('mousemove', handleMouseMove);
                    document.removeEventListener('mouseup', handleMouseUp);
                };
                
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
            },

            isInCurrentMonth(date) {
                return date.getFullYear() === this.currentMonth.getFullYear() && 
                    date.getMonth() === this.currentMonth.getMonth();
            },

            isToday(day) {
                const today = new Date();
                return today.getDate() === day && 
                    today.getMonth() === this.currentMonth.getMonth() && 
                    today.getFullYear() === this.currentMonth.getFullYear();
            },

            getNextMonthEnd() {
                return new Date(
                    this.currentMonth.getFullYear(),
                    this.currentMonth.getMonth() + 2,
                    0
                ).toISOString().slice(0, 10);
            },

            handleEditSave() {
                const projectIndex = this.scheduledProjects.findIndex(p => p.id === this.editingProject.id);
                if (projectIndex !== -1) {
                    const endDate = new Date(this.editingProject.endDate);
                    
                    if (!this.checkOverlap(
                        new Date(this.editingProject.startDate),
                        endDate,
                        this.editingProject.row,
                        this.editingProject.id
                    )) {
                        this.scheduledProjects[projectIndex] = {...this.editingProject};
                        this.editingProject = null;
                    } else {
                        alert('Cannot update - overlaps with existing project');
                    }
                }
            },
        }
    }
</script>

<style>
    /* Hide scrollbars while maintaining scroll functionality */
    .hide-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;            /* Chrome, Safari and Opera */
    }
</style>




