<nav class="bg-black shadow-lg">
    <div class="mx-8 px-3">
        <div class="flex justify-between">
               <div class="flex space-x-7">
                   <!--Website Logo-->
                    <a href="#" class="flex text-xl font-extrabold text-primary-red items-center py-2 px-2">
                        Sidecar
                    </a>
               </div>
               <div class="hidden md:flex items-center space-x-1">
                    <a href="#" class="{{ (request()->is('reupload')) ? 'border-b-4' : '' }} hover:border-b-4 py-2 px-2 text-white border-secondary-blue font-semibold">
                        Upload
                    </a>
                    <a href="#" class="{{ (request()->is('editdata')) ? 'border-b-4' : '' }} hover:border-b-4 py-2 px-2 text-white border-secondary-blue font-semibold">
                        Edit
                    </a>

                </div>

                <div class="md:hidden flex items-center">
                    <button class="outline-none menu-button">
                        <svg class="w-6 h-6 text-white" x-show="! showMenu" fill="none" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" viewBox="0 00 24 24" stroke="currentColor"><path d="m4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <div class="hidden mobile-menu">
                    <ul class="text-white">
                        <li class="active"> 
                            <a href="#" class="block px-2 py-4 font-semibold">
                                Editing
                            </a>
                        </li>
                        <li class="active"> 
                            <a href="#" class="block px-2 py-4 font-semibold">
                                Reupload
                            </a>
                        </li>
                    </ul>
                </div>

        </div>

    </div>
</nav>

<script>
    const btn = document.querySelector('button.menu-button');
    const menu = document.querySelector(".mobile-menu");
    btn.addEventListener("click", () => {
        menu.classList.toggle("hidden");
    })
</script>



