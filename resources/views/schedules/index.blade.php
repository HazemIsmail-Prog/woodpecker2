<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Projects Timeline') }}
            </h2>
            <div id="statistics"></div>
        </div>
    </x-slot>

    <div class="h-[calc(100vh-145px)] bg-gray-100 dark:bg-gray-900" x-data="indexComponent()">

        <template x-teleport="#statistics">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Unplaced Schedules Card -->
                <div class="flex items-center">
                    <div class="px-3.5 py-2 rounded-full bg-[#ac7909] bg-opacity-20">
                        <i class="fas fa-calendar text-[#ac7909] text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Unplaced Schedules</p>
                        <h3 class="text-xl font-semibold text-[#ac7909]" x-text="unPlacedSchedules.length"></h3>
                    </div>
                </div>
                <!-- Monthly Schedules Card -->
                <div class="flex items-center">
                    <div class="px-3.5 py-2 rounded-full bg-[#ac7909] bg-opacity-20">
                        <i class="fas fa-calendar text-[#ac7909] text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="currentMonth.toLocaleString('default', { month: 'long', year: 'numeric' }) + ' Schedules'"></p>
                        <h3 class="text-xl font-semibold text-[#ac7909]" x-text="schedules.length"></h3>
                    </div>
                </div>



                <!-- Today's Running Schedules Card -->
                <div class="flex items-center">
                    <div class="px-3.5 py-2 rounded-full bg-[#ac7909] bg-opacity-20">
                            <i class="fas fa-clock text-[#ac7909] text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Today's Running Schedules</p>
                            <!-- display count of schedules where start date is less than or equal to today and end date is greater than or equal to today -->
                            <h3 class="text-xl font-semibold text-[#ac7909]" x-text="schedules.filter(p => p.start_date <= new Date().toISOString().split('T')[0] && p.end_date >= new Date().toISOString().split('T')[0]).length"></h3>
                        </div>
                    </div>
                </div>

                
            </div>
        </template>


        <div class="h-full mx-auto bg-gray-100 dark:bg-gray-900">
            <div class="h-full text-gray-900 dark:text-gray-100">
                <div class="flex h-full p-4 gap-4">
                    <!-- Projects List - Fixed height -->
                    <div class="w-1/4 bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col">
                        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Projects</h2>
                        
                        <!-- Search input - Fixed -->
                        <div class="mb-4 flex-shrink-0">
                            <input type="text"
                                x-model="searchQuery"
                                @input="searchProjects"
                                placeholder="Search projects..."
                                class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-900 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ac7909]">
                        </div>
                        
                        <!-- Projects list - Scrollable -->
                        <div class="overflow-y-auto flex-1 hide-scrollbar">
                            <div class="space-y-2">
                                <template x-for="schedule in filteredUnPlacedSchedules" :key="schedule.id">
                                    <div
                                    x-on:dblclick.stop="openModal(schedule)"

                                    :class="[schedule.color ? schedule.color + ' bg-opacity-50' : 'bg-[#ac7909]/50 dark:bg-[#ac7909]/60', schedule.notes ? 'animate-pulse hover:animate-none' : '']"
                                    class="p-3 rounded-lg cursor-move "
                                        draggable="true"
                                        @dragstart="draggedSchedule = schedule">
                                        <div class="flex flex-col justify-between">
                                            <div x-text="schedule.project.name"></div>
                                            <div class="text-xs text-white" x-text="schedule.project.quotation_number"></div>
                                            <div class="text-xs text-white" x-show="schedule.project.installation_date" x-text="getFromattedDate(schedule.project.installation_date)"></div>
                                            <div class="text-xs text-white" x-text="schedule.duration + ' days'"></div>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- Add empty state for no results -->
                                <div x-show="!filteredUnPlacedSchedules.length" 
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
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-900 hover:bg-[#ac7909] hover:text-white dark:hover:bg-[#ac7909] rounded-md flex items-center transition-colors">
                                <svg class="w-4 h-4 mr-1 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                <span class="mr-1">Previous</span>
                            </button>
                            <h2 class="text-xl font-bold" x-text="currentMonth.toLocaleString('default', { month: 'long', year: 'numeric' })"></h2>
                            <button @click="nextMonth" 
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-900 hover:bg-[#ac7909] hover:text-white dark:hover:bg-[#ac7909] rounded-md flex items-center transition-colors">
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
                                <div  class="w-[40px] shrink-0 border border-gray-200 dark:border-gray-600"></div>
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
                                        <div class="flex h-[100px] select-none relative">
                                            <!-- Days cells -->
                                             <div class="w-[40px] text-xs flex items-center justify-center shrink-0 border h-full border-gray-200 dark:border-gray-600" x-text="i"></div>
                                            <template x-for="day in getDaysInMonth()" :key="day">
                                                <div 
                                                    class="w-[40px] shrink-0 border h-full border-gray-200 dark:border-gray-600"
                                                    :class="{
                                                        'bg-gray-50 dark:bg-gray-900': isFriday(day),
                                                        'bg-red-50 dark:bg-red-900 dark:bg-opacity-30': isToday(day),
                                                    }"
                                                    @dragover.prevent="$event.target.classList.add('bg-[#ac7909]/20', 'dark:bg-[#ac7909]/30')"
                                                    @dragleave="$event.target.classList.remove('bg-[#ac7909]/20', 'dark:bg-[#ac7909]/30')"
                                                    @drop="handleDropDay(day,i); $event.target.classList.remove('bg-[#ac7909]/20', 'dark:bg-[#ac7909]/30')"
                                                ></div>
                                            </template>

                                            <!-- Scheduled projects for this row -->
                                            <template x-for="schedule in schedules.filter(p => p.row === i)" :key="schedule.id">
                                                <div 
                                                    :title="schedule.project.name"
                                                    :class="[schedule.color ? schedule.color + ' bg-opacity-50' : 'bg-[#ac7909]/50 dark:bg-[#ac7909]/60', schedule.notes ? 'animate-pulse hover:animate-none' : '']"
                                                    class="absolute h-[90px] m-1 p-2 rounded-lg overflow-hidden text-sm group cursor-move"
                                                    x-on:dblclick.stop="openModal(schedule)"
                                                    draggable="true"
                                                    @dragstart.stop="draggedSchedule = schedule"
                                                    :style="`
                                                        left: ${Math.max(0, (new Date(schedule.start_date).getMonth() === currentMonth.getMonth() 
                                                            ? new Date(schedule.start_date).getDate() - 1 
                                                            : 0)) * 40 + 40}px; 
                                                        width: ${(Math.min(
                                                            new Date(schedule.end_date).getMonth() === currentMonth.getMonth() 
                                                                ? new Date(schedule.end_date).getDate()
                                                                : new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0).getDate(),
                                                            getDaysInMonth().length
                                                        ) - Math.max(
                                                            new Date(schedule.start_date).getMonth() === currentMonth.getMonth()
                                                                ? new Date(schedule.start_date).getDate() - 1
                                                                : 0,
                                                            0
                                                        )) * 40 - 8}px;
                                                    `"
                                                >
                                                    <div class="flex flex-col h-full">
                                                        <div class="flex justify-between items-start">
                                                            <span class="font-medium truncate text-gray-900 dark:text-gray-100" x-text="schedule.project?.name"></span>
                                                            <button @click.stop="setScheduleToUnPlaced(schedule)"
                                                                    class="text-gray-400 hover:text-red-500 p-1 -mt-1 -mr-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs text-gray-600 dark:text-gray-200" x-text="schedule.project.quotation_number"></span>
                                                        </div>
                                                        <div class="text-xs text-gray-600 dark:text-gray-200 mt-1">
                                                            <span x-text="`${new Date(schedule.start_date).getDate()}/${new Date(schedule.start_date).getMonth() + 1} - ${new Date(schedule.end_date).getDate()}/${new Date(schedule.end_date).getMonth() + 1}`"></span>
                                                        </div>
                                                        <div class="text-xs mt-auto text-gray-600 dark:text-gray-200">
                                                            <span x-text="`${schedule.duration} days`"></span>
                                                        </div>
                                                    </div>
                                                    <!-- Resize handle -->
                                                    <div
                                                        :class="[schedule.color ? schedule.color + ' bg-opacity-50' : 'bg-[#ac7909]/50 dark:bg-[#ac7909]/60']"
                                                        class="absolute right-0 top-0 bottom-0 w-1 cursor-e-resize opacity-0 group-hover:opacity-100 transition-opacity"
                                                        @mousedown.prevent="handleResize(schedule, $event)"
                                                    ></div>
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


        <!-- modal -->
        <x-modal name="schedule-modal" :show="false">
            <div class="p-6 dark:bg-gray-800 h-screen overflow-y-auto">
                <h2 class="text-lg font-semibold text-[#ac7909] dark:text-[#ac7909] mb-4">Edit Schedule</h2>
                <form @submit.prevent="saveSchedule">
                    <!-- Project Details Section -->
                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-[#ac7909] dark:border-[#ac7909]"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white dark:bg-gray-800 px-4 text-sm font-medium text-[#ac7909] dark:text-[#ac7909]">
                                    <i class="fas fa-building mr-2"></i>Project Details
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project Name</label>
                                <p class="text-[#ac7909] dark:text-[#ac7909] font-medium" x-text="modalSchedule?.project?.name"></p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quotation Number</label>
                                <p class="text-[#ac7909] dark:text-[#ac7909] font-medium" x-text="modalSchedule?.project?.quotation_number"></p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contract Date</label>
                                <p class="text-[#ac7909] dark:text-[#ac7909] font-medium" x-text="getFromattedDate(modalSchedule?.project?.contract_date)"></p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Installation Date</label>
                                <p class="text-[#ac7909] dark:text-[#ac7909] font-medium" x-text="getFromattedDate(modalSchedule?.project?.installation_date)"></p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Date</label>
                                <p class="text-[#ac7909] dark:text-[#ac7909] font-medium" x-text="getFromattedDate(modalSchedule?.project?.delivery_date)"></p>
                            </div>


                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <p class="text-[#ac7909] dark:text-[#ac7909] font-medium" x-text="modalSchedule?.project?.status"></p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                <p class="text-[#ac7909] dark:text-[#ac7909] font-medium" x-text="modalSchedule?.project?.location"></p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                <p class="text-[#ac7909] dark:text-[#ac7909] font-medium" x-text="modalSchedule?.project?.phone"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Details Section -->
                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-[#ac7909] dark:border-[#ac7909]"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white dark:bg-gray-800 px-4 text-sm font-medium text-[#ac7909] dark:text-[#ac7909]">
                                    <i class="fas fa-calendar-alt mr-2"></i>Schedule Details
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                                <p class="text-[#ac7909] dark:text-[#ac7909] font-medium" x-text="modalSchedule?.start_date ? getFromattedDate(modalSchedule?.start_date) : '-'"></p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Days)</label>
                                <input type="number" 
                                       x-model="modalSchedule.duration"
                                       @change="updateEndDate"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                                <input type="date" 
                                       x-model="modalSchedule.end_date"
                                       @change="updateDuration"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select x-model="modalSchedule.status" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50">
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-span-2 space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea x-model="modalSchedule.notes" 
                                          rows="3"
                                          class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-[#ac7909] focus:ring focus:ring-[#ac7909] focus:ring-opacity-50"></textarea>
                            </div>
                        </div>

                        <!-- schedule color switcher -->
                        <div class="space-y-2 mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Schedule Color</label>
                            <div class="grid grid-cols-7 gap-2">
                                <!-- Whites & Grays -->
                                <button type="button" @click="modalSchedule.color = 'bg-white'"
                                    class="w-8 h-8 rounded-sm bg-white hover:ring-2 hover:ring-offset-2 hover:ring-white"
                                    :class="{'ring-2 ring-offset-2 ring-white': modalSchedule.color === 'bg-white'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-gray-500'"
                                    class="w-8 h-8 rounded-sm bg-gray-500 hover:ring-2 hover:ring-offset-2 hover:ring-gray-500"
                                    :class="{'ring-2 ring-offset-2 ring-gray-500': modalSchedule.color === 'bg-gray-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-slate-500'"
                                    class="w-8 h-8 rounded-sm bg-slate-500 hover:ring-2 hover:ring-offset-2 hover:ring-slate-500"
                                    :class="{'ring-2 ring-offset-2 ring-slate-500': modalSchedule.color === 'bg-slate-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-zinc-700'"
                                    class="w-8 h-8 rounded-sm bg-zinc-700 hover:ring-2 hover:ring-offset-2 hover:ring-zinc-700"
                                    :class="{'ring-2 ring-offset-2 ring-zinc-700': modalSchedule.color === 'bg-zinc-700'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-black'"
                                    class="w-8 h-8 rounded-sm bg-black hover:ring-2 hover:ring-offset-2 hover:ring-black"
                                    :class="{'ring-2 ring-offset-2 ring-black': modalSchedule.color === 'bg-black'}">
                                </button>

                                <!-- Reds -->
                                <button type="button" @click="modalSchedule.color = 'bg-red-500'"
                                    class="w-8 h-8 rounded-sm bg-red-500 hover:ring-2 hover:ring-offset-2 hover:ring-red-500"
                                    :class="{'ring-2 ring-offset-2 ring-red-500': modalSchedule.color === 'bg-red-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-red-800'"
                                    class="w-8 h-8 rounded-sm bg-red-800 hover:ring-2 hover:ring-offset-2 hover:ring-red-800"
                                    :class="{'ring-2 ring-offset-2 ring-red-800': modalSchedule.color === 'bg-red-800'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-rose-400'"
                                    class="w-8 h-8 rounded-sm bg-rose-400 hover:ring-2 hover:ring-offset-2 hover:ring-rose-400"
                                    :class="{'ring-2 ring-offset-2 ring-rose-400': modalSchedule.color === 'bg-rose-400'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-rose-900'"
                                    class="w-8 h-8 rounded-sm bg-rose-900 hover:ring-2 hover:ring-offset-2 hover:ring-rose-900"
                                    :class="{'ring-2 ring-offset-2 ring-rose-900': modalSchedule.color === 'bg-rose-900'}">
                                </button>

                                <!-- Oranges & Yellows -->
                                <button type="button" @click="modalSchedule.color = 'bg-orange-400'"
                                    class="w-8 h-8 rounded-sm bg-orange-400 hover:ring-2 hover:ring-offset-2 hover:ring-orange-400"
                                    :class="{'ring-2 ring-offset-2 ring-orange-400': modalSchedule.color === 'bg-orange-400'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-orange-700'"
                                    class="w-8 h-8 rounded-sm bg-orange-700 hover:ring-2 hover:ring-offset-2 hover:ring-orange-700"
                                    :class="{'ring-2 ring-offset-2 ring-orange-700': modalSchedule.color === 'bg-orange-700'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-amber-400'"
                                    class="w-8 h-8 rounded-sm bg-amber-400 hover:ring-2 hover:ring-offset-2 hover:ring-amber-400"
                                    :class="{'ring-2 ring-offset-2 ring-amber-400': modalSchedule.color === 'bg-amber-400'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-yellow-500'"
                                    class="w-8 h-8 rounded-sm bg-yellow-500 hover:ring-2 hover:ring-offset-2 hover:ring-yellow-500"
                                    :class="{'ring-2 ring-offset-2 ring-yellow-500': modalSchedule.color === 'bg-yellow-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-yellow-700'"
                                    class="w-8 h-8 rounded-sm bg-yellow-700 hover:ring-2 hover:ring-offset-2 hover:ring-yellow-700"
                                    :class="{'ring-2 ring-offset-2 ring-yellow-700': modalSchedule.color === 'bg-yellow-700'}">
                                </button>

                                <!-- Greens -->
                                <button type="button" @click="modalSchedule.color = 'bg-lime-500'"
                                    class="w-8 h-8 rounded-sm bg-lime-500 hover:ring-2 hover:ring-offset-2 hover:ring-lime-500"
                                    :class="{'ring-2 ring-offset-2 ring-lime-500': modalSchedule.color === 'bg-lime-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-green-500'"
                                    class="w-8 h-8 rounded-sm bg-green-500 hover:ring-2 hover:ring-offset-2 hover:ring-green-500"
                                    :class="{'ring-2 ring-offset-2 ring-green-500': modalSchedule.color === 'bg-green-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-green-600'"
                                    class="w-8 h-8 rounded-sm bg-green-600 hover:ring-2 hover:ring-offset-2 hover:ring-green-600"
                                    :class="{'ring-2 ring-offset-2 ring-green-600': modalSchedule.color === 'bg-green-600'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-green-700'"
                                    class="w-8 h-8 rounded-sm bg-green-700 hover:ring-2 hover:ring-offset-2 hover:ring-green-700"
                                    :class="{'ring-2 ring-offset-2 ring-green-700': modalSchedule.color === 'bg-green-700'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-emerald-800'"
                                    class="w-8 h-8 rounded-sm bg-emerald-800 hover:ring-2 hover:ring-offset-2 hover:ring-emerald-800"
                                    :class="{'ring-2 ring-offset-2 ring-emerald-800': modalSchedule.color === 'bg-emerald-800'}">
                                </button>

                                <!-- Blues -->
                                <button type="button" @click="modalSchedule.color = 'bg-cyan-400'"
                                    class="w-8 h-8 rounded-sm bg-cyan-400 hover:ring-2 hover:ring-offset-2 hover:ring-cyan-400"
                                    :class="{'ring-2 ring-offset-2 ring-cyan-400': modalSchedule.color === 'bg-cyan-400'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-cyan-800'"
                                    class="w-8 h-8 rounded-sm bg-cyan-800 hover:ring-2 hover:ring-offset-2 hover:ring-cyan-800"
                                    :class="{'ring-2 ring-offset-2 ring-cyan-800': modalSchedule.color === 'bg-cyan-800'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-sky-500'"
                                    class="w-8 h-8 rounded-sm bg-sky-500 hover:ring-2 hover:ring-offset-2 hover:ring-sky-500"
                                    :class="{'ring-2 ring-offset-2 ring-sky-500': modalSchedule.color === 'bg-sky-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-blue-500'"
                                    class="w-8 h-8 rounded-sm bg-blue-500 hover:ring-2 hover:ring-offset-2 hover:ring-blue-500"
                                    :class="{'ring-2 ring-offset-2 ring-blue-500': modalSchedule.color === 'bg-blue-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-blue-700'"
                                    class="w-8 h-8 rounded-sm bg-blue-700 hover:ring-2 hover:ring-offset-2 hover:ring-blue-700"
                                    :class="{'ring-2 ring-offset-2 ring-blue-700': modalSchedule.color === 'bg-blue-700'}">
                                </button>

                                <!-- Purples & Pinks -->
                                <button type="button" @click="modalSchedule.color = 'bg-indigo-500'"
                                    class="w-8 h-8 rounded-sm bg-indigo-500 hover:ring-2 hover:ring-offset-2 hover:ring-indigo-500"
                                    :class="{'ring-2 ring-offset-2 ring-indigo-500': modalSchedule.color === 'bg-indigo-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-violet-800'"
                                    class="w-8 h-8 rounded-sm bg-violet-800 hover:ring-2 hover:ring-offset-2 hover:ring-violet-800"
                                    :class="{'ring-2 ring-offset-2 ring-violet-800': modalSchedule.color === 'bg-violet-800'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-purple-500'"
                                    class="w-8 h-8 rounded-sm bg-purple-500 hover:ring-2 hover:ring-offset-2 hover:ring-purple-500"
                                    :class="{'ring-2 ring-offset-2 ring-purple-500': modalSchedule.color === 'bg-purple-500'}">
                                </button>
                                <button type="button" @click="modalSchedule.color = 'bg-pink-500'"
                                    class="w-8 h-8 rounded-sm bg-pink-500 hover:ring-2 hover:ring-offset-2 hover:ring-pink-500"
                                    :class="{'ring-2 ring-offset-2 ring-pink-500': modalSchedule.color === 'bg-pink-500'}">
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" @click="closeModal"
                                class="px-4 py-2 bg-white dark:bg-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ac7909]">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-[#ac7909] hover:bg-[#8e6407] border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ac7909]">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</x-app-layout>

<script>
    function indexComponent(){
        return {

            unPlacedSchedules: [],
            schedules: [],
            currentMonth: new Date(),
            draggedSchedule: null,
            rowsCount: 30,
            searchQuery: '',
            isModalOpen: false,
            modalSchedule: {},



            init(){
                this.fetchData();
            },

            openModal(schedule){
                this.$dispatch('open-modal', 'schedule-modal');
                this.modalSchedule = {...schedule};
                this.isModalOpen = true;
            },

            updateEndDate(){
                if (!this.modalSchedule.start_date || !this.modalSchedule.duration) {
                    return;
                }
                const startDate = new Date(this.modalSchedule.start_date);
                const newEndDate = new Date(startDate); // Create new date object to avoid modifying original
                newEndDate.setDate(startDate.getDate() + parseInt(this.modalSchedule.duration) - 1);
                this.modalSchedule.end_date = newEndDate.toISOString().split('T')[0];
            },

            updateDuration(){
                if (!this.modalSchedule.start_date || !this.modalSchedule.end_date) {
                    return;
                }
                const startDate = new Date(this.modalSchedule.start_date);
                const endDate = new Date(this.modalSchedule.end_date);  
                this.modalSchedule.duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
            },

            handleResize(schedule, $event){
                const startX = $event.clientX;
                const startWidth = $event.target.parentElement.offsetWidth;
                const dayWidth = 40;
                let lastCellCount = Math.round(startWidth / dayWidth);
                let newDuration;
                
                const handleMouseMove = (e) => {
                    const diff = e.clientX - startX;
                    const newCellCount = Math.round((startWidth + diff) / dayWidth);
                    
                    if (newCellCount !== lastCellCount) {
                        const additionalDays = newCellCount - lastCellCount;
                        const newEndDate = new Date(schedule.end_date);
                        newEndDate.setDate(newEndDate.getDate() + additionalDays);
                        newDuration = newEndDate.getDate() - new Date(schedule.start_date).getDate() + 1;
                        
                        if(!this.checkOverlap(new Date(schedule.start_date), newEndDate, schedule.row, schedule.id)) {
                            $event.target.parentElement.style.width = (newCellCount * dayWidth - 8) + 'px';
                            schedule.end_date = newEndDate.toISOString().slice(0, 10);
                            lastCellCount = newCellCount;
                        }
                    }
                };

                const handleMouseUp = () => {
                    document.removeEventListener('mousemove', handleMouseMove);
                    document.removeEventListener('mouseup', handleMouseUp);
                    // Save the new schedule dates
                    axios.put('/schedules/' + schedule.id, {
                        ...schedule,
                        duration: newDuration,
                    }).then(response => {
                        this.fetchData();
                    }).catch(error => {
                        console.error('Error updating schedule:', error);
                        this.fetchData(); // Revert on error
                    });
                };
                
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
            },

            setScheduleToUnPlaced(schedule){
                this.unPlacedSchedules.push(schedule);
                this.schedules = this.schedules.filter(s => s.id !== schedule.id);
                axios.put('/schedules/'+schedule.id, {
                    ...schedule,
                    start_date: null,
                    end_date: null,
                    row: null,
                }).then(response => {
                    this.fetchData();
                }).catch(error => {
                    console.error('Error updating schedule:', error);
                });
            },

            get filteredUnPlacedSchedules(){
                if(!this.searchQuery){
                    return this.unPlacedSchedules;
                }
                return this.unPlacedSchedules.filter(schedule => {
                    return schedule.project.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    schedule.project.quotation_number.toLowerCase().includes(this.searchQuery.toLowerCase());
                });
            },

            handleDropDay(day, i) {
                if (!this.draggedSchedule) return;
                
                const startDate = new Date(
                    this.currentMonth.getFullYear(),
                    this.currentMonth.getMonth(),
                    day,
                    12
                );
                const endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + this.draggedSchedule.duration - 1);
                // prevent overlap ignore draggedProject
                if(this.checkOverlap(startDate,endDate,i,this.draggedSchedule.id)){
                    return;
                }

                if(this.draggedSchedule.start_date){
                this.schedules.find(schedule => schedule.id === this.draggedSchedule.id).row = i;
                this.schedules.find(schedule => schedule.id === this.draggedSchedule.id).start_date = startDate.toISOString().slice(0, 10);
                    this.schedules.find(schedule => schedule.id === this.draggedSchedule.id).end_date = endDate.toISOString().slice(0, 10);
                }else{
                    this.schedules.push({
                        ...this.draggedSchedule,
                        row: i,
                        start_date: startDate.toISOString().slice(0, 10),
                        end_date: endDate.toISOString().slice(0, 10),
                    });
                    this.unPlacedSchedules = this.unPlacedSchedules.filter(schedule => schedule.id !== this.draggedSchedule.id);
                }
                    axios.put('/schedules/'+this.draggedSchedule.id, {
                        ...this.draggedSchedule,
                        row: i,
                    start_date: startDate.toISOString().slice(0, 10),
                    end_date: endDate.toISOString().slice(0, 10),
                    // change the schedule on frontend
                }).then(response => {
                    console.log('schedule updated');
                }).catch(error => {
                    alert('Error updating schedule:', error);
                    this.fetchData(); // Revert on error
                });

                this.draggedSchedule = null;
            },

            getFromattedDate(date){
                return new Date(date).toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).replace(/\//g, '/');
            },

            fetchData(){
                const month = this.currentMonth.getMonth() + 1; // Add 1 since getMonth() is 0-based
                const year = this.currentMonth.getFullYear();
                const monthStr = month.toString().padStart(2, '0');
                const yearStr = year.toString();
                axios.get('/schedules/'+monthStr+'/'+yearStr)
                .then(response => {
                    this.unPlacedSchedules = response.data.unPlacedSchedules;
                    this.schedules = response.data.schedules;
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

            previousMonth() {
                this.schedules = [];
                this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() - 1);
                this.fetchData();
            },
            nextMonth() {
                this.schedules = [];
                this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() + 1);
                this.fetchData();
            },

            checkOverlap(startDate, endDate, row, excludeScheduleId = null) {
                return this.schedules.some(schedule => {
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

            isToday(day) {
                const today = new Date();
                return today.getDate() === day && 
                    today.getMonth() === this.currentMonth.getMonth() && 
                    today.getFullYear() === this.currentMonth.getFullYear();
            },

            saveSchedule() {
                if (!this.modalSchedule) return;                
                axios.put('/schedules/' + this.modalSchedule.id, this.modalSchedule)
                    .then(response => {
                        this.fetchData();
                        this.closeModal();
                    })
                    .catch(error => {
                        alert('Error updating schedule:', error);
                    });
            },

            closeModal() {
                this.$dispatch('close-modal', 'schedule-modal');
            }
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




