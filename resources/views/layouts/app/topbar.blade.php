  <div
      class="flex gap-5 items-center h-20 bg-[#FAFAFA] dark:text-white dark:bg-[#171717] dark:border-[#3E3E3E] w-full p-5 hidden border-b border-[#DEDEDE] lg:flex">
      <div class="flex items-center gap-2">
          <p>Année scolaire:</p>
          <select name="" id=""
              class="p-2 border focus:outline-none border-[#E5E5E5
] dark:border-[#3E3E3E] h-10 rounded-md w-40">
              <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">2026/2027
              </option>
              <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">2024/2025
              </option>
              <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">2023/2024
              </option>
          </select>

      </div>

      <div class="flex items-center gap-2">
          <p>Date du jour:</p>
          <div class="flex items-center gap-2">
              <p>{{ Str::ucfirst(now()->isoFormat('dddd DD-MM-YYYY HH:mm')) }}</p>
              <span class="text-gray-300 dark:text-gray-600">|</span>
              <p class="text-gray-500 dark:text-gray-400">
                  {{ Str::ucfirst(\IntlDateFormatter::create('fr_FR@calendar=islamic', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, \IntlDateFormatter::TRADITIONAL, 'd MMMM y G')->format(now()->timestamp)) }}
              </p>
          </div>
      </div>


  </div>
