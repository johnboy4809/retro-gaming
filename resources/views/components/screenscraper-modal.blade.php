@props(['apiRoutePrefix' => '/admin', 'systemId' => '4'])

<!-- ScreenScraper Metadata Modal -->
<div id="ss-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="glass-card max-w-4xl w-full rounded-2xl border border-retro-cyan overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-retro-card border-b border-retro-border flex justify-between items-center">
            <div>
                <span class="text-xs font-tech text-retro-cyan uppercase tracking-widest">ScreenScraper.fr Metadata</span>
                <h3 id="ss-rom-title" class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider"></h3>
            </div>
            <button type="button" onclick="closeSs()" class="text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
            <div id="ss-loading" class="text-center py-16">
                <i class="fa-solid fa-satellite-dish text-4xl text-retro-cyan animate-pulse mb-3 block"></i>
                <p class="font-tech text-gray-400 text-sm">Querying ScreenScraper Database...</p>
            </div>
            
            <div id="ss-error" class="text-center py-16 hidden">
                <i class="fa-solid fa-triangle-exclamation text-4xl text-retro-red mb-3 block"></i>
                <p id="ss-error-message" class="font-tech text-retro-red text-sm"></p>
                <p class="font-tech text-gray-400 text-xs mt-2">Make sure you have added your ScreenScraper credentials to your .env file.</p>
            </div>

            <div id="ss-content" class="space-y-5 hidden">
                <!-- Basic Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-retro-bg p-4 rounded-xl border border-retro-border border-opacity-35">
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Title</span>
                        <span id="ss-title" class="text-xs text-white font-semibold block truncate"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Developer</span>
                        <span id="ss-developer" class="text-xs text-white font-semibold block truncate"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Publisher</span>
                        <span id="ss-publisher" class="text-xs text-white font-semibold block truncate"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Year</span>
                        <span id="ss-year" class="text-xs text-white font-semibold block truncate"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Genre</span>
                        <span id="ss-genre" class="text-xs text-white font-semibold block truncate"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Players</span>
                        <span id="ss-players" class="text-xs text-white font-semibold block"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Region</span>
                        <span id="ss-region" class="text-xs text-white font-semibold block"></span>
                    </div>
                </div>

                <!-- Media Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="ss-media-layout">
                    <!-- Left: Image Section -->
                    <div class="space-y-4 hidden" id="ss-img-group">
                        <h4 class="text-xs font-tech text-retro-cyan uppercase tracking-wider">Game Artwork</h4>
                        <div class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-center items-center h-64 overflow-hidden relative group">
                            <img id="ss-img-main" src="" alt="Artwork" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                        </div>
                    </div>
                    <!-- Right: Video Section -->
                    <div class="space-y-4 hidden" id="ss-video-group">
                        <h4 class="text-xs font-tech text-retro-cyan uppercase tracking-wider">Gameplay Video</h4>
                        <div class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex justify-center items-center h-64 overflow-hidden relative">
                            <video id="ss-video-main" controls class="max-h-full max-w-full" src=""></video>
                        </div>
                    </div>
                </div>

                <!-- Synopsis / Description Text -->
                <div id="ss-history-wrapper" class="space-y-2">
                    <h4 class="text-xs font-tech text-retro-cyan uppercase tracking-wider">Synopsis</h4>
                    <div id="ss-history" class="bg-black bg-opacity-45 p-4 rounded-xl border border-retro-border border-opacity-35 text-xs text-gray-300 leading-relaxed font-sans max-h-48 overflow-y-auto whitespace-pre-wrap select-text"></div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-retro-card border-t border-retro-border flex justify-between items-center">
            <div></div>
            <button onclick="closeSs()" class="px-5 py-2 bg-retro-cyan hover:bg-opacity-80 text-black font-tech text-sm uppercase tracking-wider rounded-lg transition font-bold">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    const systemIdConfig = "{{ $systemId }}";

    function fetchScreenScraper(rom, crc32 = '') {
        document.getElementById('ss-rom-title').innerText = rom;
        
        // Show loading, hide others
        document.getElementById('ss-loading').classList.remove('hidden');
        document.getElementById('ss-error').classList.add('hidden');
        document.getElementById('ss-content').classList.add('hidden');
        
        document.getElementById('ss-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Use systemId=4 (SNES) by default, or pass via config
        const sysId = window.activeSystemId || systemIdConfig;

        let url = `{{ $apiRoutePrefix }}/screenscraper/${sysId}/${rom}`;
        if (crc32) {
            url += `?crc32=${encodeURIComponent(crc32)}`;
        }

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('API request failed or ROM not found.');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('ss-loading').classList.add('hidden');
                document.getElementById('ss-content').classList.remove('hidden');
                
                // data might be GameSearchResultsData
                let game = {};
                if (data.jeu) {
                    game = data.jeu;
                } else if (Array.isArray(data) && data.length > 0) {
                    game = data[0];
                } else {
                    game = data;
                }
                
                // Assuming object mapping based on typical scraper returns
                // We will gracefully handle whatever Spatie Data dumps
                const title = game.noms && game.noms[0] ? game.noms[0].text : game.nom || '—';
                const developer = game.developpeur || game.developer || '—';
                const publisher = game.editeur || game.publisher || '—';
                const year = game.dates && game.dates[0] ? game.dates[0].text : game.date || '—';
                const genre = game.genres && game.genres[0] ? game.genres[0].nom : game.genre || '—';
                const players = game.joueurs || game.players || '—';
                const region = game.regions && game.regions[0] ? game.regions[0].nom : game.region || '—';
                
                document.getElementById('ss-title').innerText = title;
                document.getElementById('ss-developer').innerText = developer;
                document.getElementById('ss-publisher').innerText = publisher;
                document.getElementById('ss-year').innerText = year;
                document.getElementById('ss-genre').innerText = genre;
                document.getElementById('ss-players').innerText = players;
                document.getElementById('ss-region').innerText = region;

                // Media
                document.getElementById('ss-img-group').classList.add('hidden');
                document.getElementById('ss-video-group').classList.add('hidden');
                document.getElementById('ss-img-main').src = "";
                document.getElementById('ss-video-main').src = "";

                if (Array.isArray(game.medias)) {
                    // Try to find a video
                    const videoMedia = game.medias.find(m => m.type === 'video');
                    if (videoMedia) {
                        document.getElementById('ss-video-main').src = videoMedia.url;
                        document.getElementById('ss-video-group').classList.remove('hidden');
                    }
                    
                    // Try to find a good image (prioritize mixed/composited images, then 3d box, then 2d box, then screenshot)
                    const imgPriority = ['mixrbv2', 'mixrbv1', 'box-3D', 'box-2D', 'screenshot', 'screenmarquee', 'flyer'];
                    let imgMedia = null;
                    for (const type of imgPriority) {
                        // try to find english/wor/eu/ss region first if available, or just take the first match
                        imgMedia = game.medias.find(m => m.type === type && (m.region === 'us' || m.region === 'wor' || m.region === 'eu' || m.region === 'ss'));
                        if (!imgMedia) imgMedia = game.medias.find(m => m.type === type);
                        if (imgMedia) break;
                    }
                    
                    // Fallback to any image if priorities didn't match
                    if (!imgMedia) {
                        imgMedia = game.medias.find(m => m.format === 'png' || m.format === 'jpg');
                    }
                    
                    if (imgMedia) {
                        document.getElementById('ss-img-main').src = imgMedia.url;
                        document.getElementById('ss-img-group').classList.remove('hidden');
                    }
                }

                // Synopsis
                const synopsisList = game.synopsis || [];
                let synopsisText = 'No synopsis available.';
                if (Array.isArray(synopsisList) && synopsisList.length > 0) {
                    // Try to find English or French
                    const enSyn = synopsisList.find(s => s.langue === 'en');
                    if (enSyn) synopsisText = enSyn.text;
                    else synopsisText = synopsisList[0].text;
                } else if (typeof game.synopsis === 'string') {
                    synopsisText = game.synopsis;
                }
                
                document.getElementById('ss-history').innerText = synopsisText;
            })
            .catch(error => {
                document.getElementById('ss-loading').classList.add('hidden');
                document.getElementById('ss-error').classList.remove('hidden');
                document.getElementById('ss-error-message').innerText = error.message;
            });
    }

    function closeSs() {
        document.getElementById('ss-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        
        // Stop video playback when closing
        const video = document.getElementById('ss-video-main');
        if (video) {
            video.pause();
            video.src = "";
        }
    }

    // Modal Events
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('ss-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeSs();
            }
        }
    });

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('ss-modal');
        if (event.target === modal) {
            closeSs();
        }
    });
</script>
