@props(['apiRoutePrefix' => '/admin'])

<!-- ADB Metadata Scraper Modal -->
<div id="adb-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="glass-card max-w-4xl w-full rounded-2xl border border-retro-cyan overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-retro-card border-b border-retro-border flex justify-between items-center">
            <div>
                <span class="text-xs font-tech text-retro-cyan uppercase tracking-widest">ArcadeItalia Scraper Metadata</span>
                <h3 id="adb-rom-title" class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider"></h3>
            </div>
            <button type="button" onclick="closeAdb()" class="text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
            <div id="adb-loading" class="text-center py-16">
                <i class="fa-solid fa-satellite-dish text-4xl text-retro-cyan animate-pulse mb-3 block"></i>
                <p class="font-tech text-gray-400 text-sm">Querying ArcadeItalia Database API...</p>
            </div>
            
            <div id="adb-error" class="text-center py-16 hidden">
                <i class="fa-solid fa-triangle-exclamation text-4xl text-retro-red mb-3 block"></i>
                <p id="adb-error-message" class="font-tech text-retro-red text-sm"></p>
            </div>

            <div id="adb-content" class="space-y-5 hidden">
                <!-- Basic Stats from API -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-retro-bg p-4 rounded-xl border border-retro-border border-opacity-35">
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Title</span>
                        <span id="adb-title" class="text-xs text-white font-semibold block truncate"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Manufacturer</span>
                        <span id="adb-manufacturer" class="text-xs text-white font-semibold block truncate"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Year</span>
                        <span id="adb-year" class="text-xs text-white font-semibold block truncate"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Genre</span>
                        <span id="adb-genre" class="text-xs text-white font-semibold block truncate"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Players</span>
                        <span id="adb-players" class="text-xs text-white font-semibold block"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Cabinet Status</span>
                        <span id="adb-status" class="text-xs text-white font-semibold block"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Controls</span>
                        <span id="adb-controls" class="text-xs text-white font-semibold block"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Resolution</span>
                        <span id="adb-resolution" class="text-xs text-white font-semibold block"></span>
                    </div>
                </div>

                <!-- Images Section -->
                <div id="adb-images-section">
                    <h4 class="text-xs font-tech text-retro-cyan uppercase tracking-wider mb-3">Cabinet & Game Artwork</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <!-- Title -->
                        <div id="adb-img-wrapper-title" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                            <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">Title Screen</span>
                            <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                <img id="adb-img-title" src="" alt="Title Screen" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                            </div>
                        </div>
                        <!-- In-Game -->
                        <div id="adb-img-wrapper-ingame" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                            <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">In-Game Play</span>
                            <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                <img id="adb-img-ingame" src="" alt="In-Game Play" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                            </div>
                        </div>
                        <!-- Cabinet -->
                        <div id="adb-img-wrapper-cabinet" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                            <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">Cabinet</span>
                            <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                <img id="adb-img-cabinet" src="" alt="Cabinet" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                            </div>
                        </div>
                        <!-- Marquee -->
                        <div id="adb-img-wrapper-marquee" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                            <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">Marquee</span>
                            <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                <img id="adb-img-marquee" src="" alt="Marquee" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                            </div>
                        </div>
                        <!-- Flyer -->
                        <div id="adb-img-wrapper-flyer" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                            <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">Flyer Poster</span>
                            <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                <img id="adb-img-flyer" src="" alt="Flyer" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Section -->
                <div id="adb-video-wrapper" class="space-y-2 hidden">
                    <h4 class="text-xs font-tech text-retro-magenta uppercase tracking-wider flex items-center justify-between">
                        <span>Gameplay Video Preview</span>
                        <button type="button" onclick="stopAdbVideo()" class="text-gray-500 hover:text-retro-magenta transition text-[10px]">
                            <i class="fa-solid fa-stop"></i> Stop Video
                        </button>
                    </h4>
                    <div class="bg-black rounded-xl border border-retro-border border-opacity-40 overflow-hidden flex justify-center">
                        <video id="adb-video" controls class="max-h-80 w-full" src=""></video>
                    </div>
                </div>

                <!-- History / Description Text -->
                <div id="adb-history-wrapper" class="space-y-2">
                    <h4 class="text-xs font-tech text-retro-cyan uppercase tracking-wider">Game History & Trivia</h4>
                    <div id="adb-history" class="bg-black bg-opacity-45 p-4 rounded-xl border border-retro-border border-opacity-35 text-xs text-gray-300 leading-relaxed font-sans max-h-48 overflow-y-auto whitespace-pre-wrap select-text"></div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-retro-card border-t border-retro-border flex justify-between items-center">
            <div>
                <button id="adb-btn-video" type="button" onclick="playAdbVideo()" class="px-4 py-2 bg-retro-magenta hover:bg-opacity-80 text-black font-tech text-xs uppercase tracking-wider rounded-lg transition font-bold hidden flex items-center space-x-1.5 shadow-md">
                    <i class="fa-solid fa-play text-black text-[10px]"></i>
                    <span>Play Video</span>
                </button>
            </div>
            <button onclick="closeAdb()" class="px-5 py-2 bg-retro-cyan hover:bg-opacity-80 text-black font-tech text-sm uppercase tracking-wider rounded-lg transition font-bold">
                Close ADB Window
            </button>
        </div>
    </div>
</div>

<script>
    let activeVideoUrl = "";

    function playAdbVideo() {
        if (activeVideoUrl) {
            const video = document.getElementById('adb-video');
            video.src = activeVideoUrl;
            document.getElementById('adb-video-wrapper').classList.remove('hidden');
            video.play();
            document.getElementById('adb-video-wrapper').scrollIntoView({ behavior: 'smooth', block: 'end' });
        }
    }

    function stopAdbVideo() {
        const video = document.getElementById('adb-video');
        if (video) {
            video.pause();
            video.src = "";
        }
        document.getElementById('adb-video-wrapper').classList.add('hidden');
    }

    function fetchArcadeItalia(rom) {
        document.getElementById('adb-rom-title').innerText = rom;
        
        // Show loading, hide others
        document.getElementById('adb-loading').classList.remove('hidden');
        document.getElementById('adb-error').classList.add('hidden');
        document.getElementById('adb-content').classList.add('hidden');
        
        document.getElementById('adb-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        fetch(`{{ $apiRoutePrefix }}/arcade-italia/${rom}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('API request failed or ROM not found.');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('adb-loading').classList.add('hidden');
                document.getElementById('adb-content').classList.remove('hidden');
                
                // ADB scraper response has the game info inside 'result' or root
                let game = {};
                if (Array.isArray(data)) {
                    game = data[0] || {};
                } else if (data.result && Array.isArray(data.result)) {
                    game = data.result[0] || {};
                } else {
                    game = data;
                }
                
                document.getElementById('adb-title').innerText = game.title || game.description || '—';
                document.getElementById('adb-manufacturer').innerText = game.manufacturer || '—';
                document.getElementById('adb-year').innerText = game.year || '—';
                document.getElementById('adb-genre').innerText = game.genre || '—';
                document.getElementById('adb-players').innerText = game.nplayers || game.players || '—';
                document.getElementById('adb-status').innerText = game.status || '—';
                document.getElementById('adb-controls').innerText = game.input_controls || '—';
                document.getElementById('adb-resolution').innerText = game.screen_resolution || '—';

                // Set game history/trivia
                const historyWrapper = document.getElementById('adb-history-wrapper');
                const historyEl = document.getElementById('adb-history');
                if (game.history && game.history.trim() !== '') {
                    historyEl.innerText = game.history;
                    historyWrapper.classList.remove('hidden');
                } else {
                    historyWrapper.classList.add('hidden');
                }

                // Set game artwork images
                setAdbImage('adb-img-title', 'adb-img-wrapper-title', game.url_image_title);
                setAdbImage('adb-img-ingame', 'adb-img-wrapper-ingame', game.url_image_ingame);
                setAdbImage('adb-img-cabinet', 'adb-img-wrapper-cabinet', game.url_image_cabinet);
                setAdbImage('adb-img-marquee', 'adb-img-wrapper-marquee', game.url_image_marquee);
                setAdbImage('adb-img-flyer', 'adb-img-wrapper-flyer', game.url_image_flyer);

                // Check video preview URL
                const videoBtn = document.getElementById('adb-btn-video');
                const videoUrl = game.url_video_shortplay_hd || game.url_video_shortplay;
                if (videoUrl && videoUrl.trim() !== '') {
                    activeVideoUrl = videoUrl;
                    videoBtn.classList.remove('hidden');
                } else {
                    activeVideoUrl = "";
                    videoBtn.classList.add('hidden');
                }
            })
            .catch(error => {
                document.getElementById('adb-loading').classList.add('hidden');
                document.getElementById('adb-error').classList.remove('hidden');
                document.getElementById('adb-error-message').innerText = error.message;
            });
    }

    function setAdbImage(imgId, wrapperId, url) {
        const img = document.getElementById(imgId);
        const wrapper = document.getElementById(wrapperId);
        
        if (url && url.trim() !== "") {
            img.src = url;
            wrapper.classList.remove('hidden');
            
            // If it fails to load, hide the wrapper dynamically
            img.onerror = function() {
                wrapper.classList.add('hidden');
            };
        } else {
            img.src = "";
            wrapper.classList.add('hidden');
        }
    }

    function closeAdb() {
        stopAdbVideo();
        document.getElementById('adb-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Modal Events
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('adb-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeAdb();
            }
        }
    });

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('adb-modal');
        if (event.target === modal) {
            closeAdb();
        }
    });
</script>
