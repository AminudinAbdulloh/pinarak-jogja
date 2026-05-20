<div class="layar-penuh">
    <header id="home">
        <div class="slider">
            <div class="slides">
                <?php foreach ($setting['banner'] as $banner): ?>
                    <img src="<?= BASEURL . '/' . $banner ?>" alt="Banner">
                <?php endforeach; ?>
            </div>
            <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="next" onclick="moveSlide(1)">&#10095;</button>
        </div>
    </header>
    <section class="container">
        <h2 class="section-title">EVENT TERBARU</h2>
        <div class="main-content">
            <?php if ($highlight_event): ?>
                <a href="<?= BASEURL . '/events/detail/' . urlencode($highlight_event['id']) ?>" class="highlight-event highlight-event-link" id="highlight-event">
                    <img src="<?= $highlight_event['image'] ?: 'assets/images/events/default.png' ?>"
                        alt="<?= htmlspecialchars($highlight_event['title']) ?>" class="main-image" id="highlight-image">
                    <div class="highlight-text">
                        <h3 id="highlight-title"><?= htmlspecialchars($highlight_event['title']) ?></h3>
                        <div class="event-meta">
                            <p class="event-date meta-item" id="highlight-date" data-datetime="<?= $highlight_event['start_time'] ?>">
                                <i class="far fa-calendar"></i>
                                <?= formatDateIndonesia($highlight_event['start_time']) ?>
                            </p>
                            <p class="event-location meta-item" id="highlight-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($highlight_event['location']) ?>
                            </p>
                        </div>
                        <p class="description" id="highlight-description">
                            <?= truncateText(strip_tags(html_entity_decode($highlight_event['description'])), 200); ?>
                        </p>
                        <p id="countdown" class="countdown"></p>
                    </div>
                </a>
            <?php else: ?>
                <div class="highlight-event" id="highlight-event">
                    <img src="assets/images/events/default.png" alt="No Event" class="main-image" id="highlight-image">
                    <div class="highlight-text">
                        <h3 id="highlight-title">Tidak Ada Event Mendatang</h3>
                        <p class="description" id="highlight-description">Belum ada event yang dijadwalkan. Pantau terus untuk update terbaru!</p>
                        <p id="countdown" class="countdown"></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="events-sidebar">
                <div class="event-search-box">
                    <form id="event-search-form" class="event-search-form" role="search" autocomplete="off">
                        <label for="event-search-input" class="sr-only">Cari event</label>
                        <div class="event-search-input-wrap">
                            <i class="fas fa-search event-search-icon" aria-hidden="true"></i>
                            <input
                                type="search"
                                id="event-search-input"
                                class="event-search-input"
                                placeholder="Cari event (judul, kategori, lokasi)..."
                                aria-label="Cari event">
                            <button type="button" id="event-search-clear" class="event-search-clear" hidden aria-label="Hapus pencarian">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </form>
                    <p id="event-search-status" class="event-search-status" hidden></p>
                </div>

            <div class="event-list" id="event-list">
                <?php if (!empty($all_events) && count($all_events) > 1): ?>
                    <?php foreach ($all_events as $index => $event): ?>
                        <?php if ($index === 0) continue; ?>
                        <a href="<?= BASEURL . '/events/detail/' . urlencode($event['id']) ?>"
                            class="event-item clickable-event"
                            data-event-index="<?= $index ?>">
                            <img src="<?= $event['image'] ?: 'assets/images/events/default.png' ?>"
                                alt="<?= htmlspecialchars($event['title']) ?>">
                            <div class="event-info">
                                <h4><?= htmlspecialchars($event['title']) ?></h4>
                                <small class="meta-item">
                                    <i class="far fa-calendar"></i>
                                    <?= formatDateIndonesia($event['start_time']) ?>
                                </small>
                                <small class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($event['location']) ?>
                                </small>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="event-item">
                        <div class="event-info">
                            <h4>Tidak ada event lainnya</h4>
                            <small>Pantau terus untuk update terbaru!</small>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="event-list-footer">
                    <a href="<?= BASEURL . '/events' ?>">
                        Lihat Event Lainnya →
                    </a>
                </div>
            </div>
            </div>
        </div>
    </section>
</div>

<!-- Letakkan di bagian bawah file src/views/public/home/index.php -->
<script>
// Pass PHP variables to JavaScript
const BASEURL = "<?= BASEURL ?>";
const allEventsData = <?= json_encode($all_events ?? []) ?>;
const MAX_OTHER_EVENTS = 5;
let searchDebounceTimer = null;

const initialHighlightHtml = document.getElementById('highlight-event')?.outerHTML ?? '';
const initialEventListHtml = document.getElementById('event-list')?.innerHTML ?? '';

// Pass highlight event data for countdown
<?php if ($highlight_event): ?>
const highlightEventData = {
    start_time: "<?= $highlight_event['start_time'] ?>",
    title: "<?= htmlspecialchars($highlight_event['title'], ENT_QUOTES) ?>"
};
<?php else: ?>
const highlightEventData = null;
<?php endif; ?>

// Banner Slider
let currentIndex = 0;
const slides = document.querySelector('.slides');
const totalSlides = slides ? slides.children.length : 0;

function moveSlide(step) {
    currentIndex = (currentIndex + step + totalSlides) % totalSlides;
    const translateX = -currentIndex * 100;
    slides.style.transform = `translateX(${translateX}%)`;
}

// Helper function to get correct image path
function getImagePath(imagePath) {
    if (!imagePath) {
        return `${BASEURL}/assets/images/events/default.png`;
    }
    
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
        return imagePath;
    }
    
    if (imagePath.startsWith('uploads/')) {
        return `${BASEURL}/${imagePath}`;
    }
    
    return `${BASEURL}/${imagePath}`;
}

document.addEventListener('DOMContentLoaded', function() {
    initializeCountdown();
    initializeEventSearch();
});

function initializeEventSearch() {
    const form = document.getElementById('event-search-form');
    const input = document.getElementById('event-search-input');
    const clearBtn = document.getElementById('event-search-clear');

    if (!form || !input) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        runEventSearch(input.value.trim());
    });

    input.addEventListener('input', function() {
        const value = input.value.trim();
        clearBtn.hidden = value.length === 0;

        clearTimeout(searchDebounceTimer);
        if (value.length === 0) {
            resetEventSearch();
            return;
        }
        if (value.length < 2) return;

        searchDebounceTimer = setTimeout(() => runEventSearch(value), 400);
    });

    clearBtn.addEventListener('click', function() {
        input.value = '';
        clearBtn.hidden = true;
        resetEventSearch();
        input.focus();
    });
}

async function runEventSearch(query) {
    const statusEl = document.getElementById('event-search-status');
    const clearBtn = document.getElementById('event-search-clear');

    if (!query) {
        resetEventSearch();
        return;
    }

    clearBtn.hidden = false;
    if (statusEl) {
        statusEl.hidden = false;
        statusEl.textContent = 'Mencari event...';
        statusEl.className = 'event-search-status is-loading';
    }

    try {
        const url = `${BASEURL}/home/search?q=${encodeURIComponent(query)}&n=${MAX_OTHER_EVENTS + 1}`;
        const response = await fetch(url);
        const data = await response.json();

        if (!data.success || !Array.isArray(data.results)) {
            throw new Error(data.message || 'Pencarian gagal');
        }

        if (data.results.length === 0) {
            showSearchNoResults(query);
            return;
        }

        updateHighlight(data.results[0]);
        renderEventList(data.results.slice(1, MAX_OTHER_EVENTS + 1), query);

        if (statusEl) {
            statusEl.className = 'event-search-status is-success';
            statusEl.textContent = `Menampilkan ${data.results.length} hasil untuk "${query}"`;
        }
    } catch (err) {
        const fallback = filterEventsLocal(query);
        if (fallback.length > 0) {
            updateHighlight(fallback[0]);
            renderEventList(fallback.slice(1, MAX_OTHER_EVENTS + 1), query);
            if (statusEl) {
                statusEl.className = 'event-search-status is-success';
                statusEl.textContent = `Menampilkan ${fallback.length} hasil untuk "${query}"`;
            }
        } else {
            showSearchNoResults(query);
        }
    }
}

function filterEventsLocal(query) {
    const q = query.toLowerCase();
    return allEventsData
        .filter(ev => {
            const haystack = [ev.title, ev.category, ev.location, ev.description]
                .map(v => stripHtml(String(v || '')).toLowerCase())
                .join(' ');
            return haystack.includes(q);
        })
        .sort((a, b) => relevanceScore(b, q) - relevanceScore(a, q));
}

function relevanceScore(event, q) {
    const title = String(event.title || '').toLowerCase();
    const category = String(event.category || '').toLowerCase();
    const location = String(event.location || '').toLowerCase();
    const description = stripHtml(String(event.description || '')).toLowerCase();

    if (title.includes(q)) return 4;
    if (category.includes(q)) return 3;
    if (location.includes(q)) return 2;
    if (description.includes(q)) return 1;
    return 0;
}

function stripHtml(html) {
    const div = document.createElement('div');
    div.innerHTML = html;
    return div.textContent || div.innerText || '';
}

function showSearchNoResults(query) {
    updateHighlightEmpty(`Tidak ada event untuk "${query}"`, 'Coba kata kunci lain atau lihat semua event.');
    renderEventList([], query);
    const statusEl = document.getElementById('event-search-status');
    if (statusEl) {
        statusEl.className = 'event-search-status is-empty';
        statusEl.textContent = `Tidak ditemukan hasil untuk "${query}"`;
    }
}

function resetEventSearch() {
    const highlightEl = document.getElementById('highlight-event');
    const listEl = document.getElementById('event-list');
    const statusEl = document.getElementById('event-search-status');
    const clearBtn = document.getElementById('event-search-clear');

    if (highlightEl && initialHighlightHtml) {
        highlightEl.outerHTML = initialHighlightHtml;
    }
    if (listEl && initialEventListHtml) {
        listEl.innerHTML = initialEventListHtml;
    }
    if (statusEl) statusEl.hidden = true;
    if (clearBtn) clearBtn.hidden = true;

    initializeCountdown();
}

function updateHighlight(event) {
    const container = document.getElementById('highlight-event');
    if (!container) return;

    const imageSrc = getImagePath(event.image);
    const description = truncateText(stripHtml(String(event.description || '')), 200);
    const detailUrl = `${BASEURL}/events/detail/${encodeURIComponent(event.id)}`;

    container.outerHTML = `
        <a href="${detailUrl}" class="highlight-event highlight-event-link" id="highlight-event">
            <img src="${imageSrc}" alt="${escapeHtml(event.title)}" class="main-image" id="highlight-image">
            <div class="highlight-text">
                <h3 id="highlight-title">${escapeHtml(event.title)}</h3>
                <div class="event-meta">
                    <p class="event-date meta-item" id="highlight-date" data-datetime="${event.start_time || ''}">
                        <i class="far fa-calendar"></i>
                        ${formatDateIndonesia(event.start_time)}
                    </p>
                    <p class="event-location meta-item" id="highlight-location">
                        <i class="fas fa-map-marker-alt"></i>
                        ${escapeHtml(event.location || '-')}
                    </p>
                </div>
                <p class="description" id="highlight-description">${escapeHtml(description)}</p>
                <p id="countdown" class="countdown"></p>
            </div>
        </a>
    `;
    initializeCountdown();
}

function updateHighlightEmpty(title, description) {
    const container = document.getElementById('highlight-event');
    if (!container) return;

    container.outerHTML = `
        <div class="highlight-event" id="highlight-event">
            <div class="highlight-text">
                <h3 id="highlight-title">${escapeHtml(title)}</h3>
                <p class="description" id="highlight-description">${escapeHtml(description)}</p>
                <p id="countdown" class="countdown"></p>
            </div>
        </div>
    `;
    currentEventDate = null;
    const countdownEl = document.getElementById('countdown');
    if (countdownEl) countdownEl.innerText = '';
}

function renderEventList(events, query) {
    const listEl = document.getElementById('event-list');
    if (!listEl) return;

    const footerHtml = `
        <div class="event-list-footer">
            <a href="${BASEURL}/events">Lihat Event Lainnya →</a>
        </div>
    `;

    if (!events.length) {
        listEl.innerHTML = `
            <div class="event-item event-item-static">
                <div class="event-info">
                    <h4>Tidak ada event lainnya</h4>
                    <small>${query ? 'Coba kata kunci berbeda.' : 'Pantau terus untuk update terbaru!'}</small>
                </div>
            </div>
            ${footerHtml}
        `;
        return;
    }

    listEl.innerHTML = events.map((event, index) => {
        const imageSrc = getImagePath(event.image);
        return `
            <a href="${BASEURL}/events/detail/${encodeURIComponent(event.id)}"
                class="event-item clickable-event" data-event-index="${index + 1}">
                <img src="${imageSrc}" alt="${escapeHtml(event.title)}">
                <div class="event-info">
                    <h4>${escapeHtml(event.title)}</h4>
                    <small class="meta-item"><i class="far fa-calendar"></i> ${formatDateIndonesia(event.start_time)}</small>
                    <small class="meta-item"><i class="fas fa-map-marker-alt"></i> ${escapeHtml(event.location || '-')}</small>
                </div>
            </a>
        `;
    }).join('') + footerHtml;
}

function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + '...';
}

function formatDateIndonesia(datetime) {
    if (!datetime) return '-';
    const months = {
        1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April',
        5: 'Mei', 6: 'Juni', 7: 'Juli', 8: 'Agustus',
        9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember'
    };
    
    const date = new Date(datetime);
    if (isNaN(date.getTime())) return '-';
    const day = date.getDate();
    const month = months[date.getMonth() + 1];
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${day} ${month} ${year} ${hours}:${minutes}`;
}

function createEventListItem(event, index) {
    const eventItem = document.createElement('a');
    eventItem.className = 'event-item clickable-event';
    eventItem.setAttribute('data-event-index', index);
    eventItem.href = `${BASEURL}/events/detail/${encodeURIComponent(event.id)}`;
    
    const imageSrc = getImagePath(event.image);
    
    eventItem.innerHTML = `
        <img src="${imageSrc}" alt="${escapeHtml(event.title)}">
        <div class="event-info">
            <h4>${escapeHtml(event.title)}</h4>
            <small>📅 ${formatDateIndonesia(event.start_time)}</small>
            <small>📍 ${escapeHtml(event.location)}</small>
        </div>
    `;
    
    return eventItem;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Event Countdown Functionality
const monthMap = {
    januari: "January", februari: "February", maret: "March", april: "April",
    mei: "May", juni: "June", juli: "July", agustus: "August",
    september: "September", oktober: "October", november: "November", desember: "December"
};

let currentEventDate = null;
let countdownInterval = null;

function initializeCountdown() {
    const eventDateElement = document.querySelector(".event-date");
    if (eventDateElement) {
        const dateTimeAttr = eventDateElement.getAttribute('data-datetime');
        if (dateTimeAttr) {
            currentEventDate = new Date(dateTimeAttr).getTime();
        } else {
            const eventDateText = eventDateElement.textContent;
            const dateMatch = eventDateText?.match(/(\d{1,2})\s+(\w+)\s+(\d{4})(?:\s+(\d{1,2}):(\d{2}))?/i);
            
            if (dateMatch) {
                let day = dateMatch[1];
                let month = dateMatch[2].toLowerCase();
                let year = dateMatch[3];
                let hour = dateMatch[4] || "00";
                let minute = dateMatch[5] || "00";

                const englishMonth = monthMap[month];
                if (englishMonth) {
                    currentEventDate = new Date(`${englishMonth} ${day}, ${year} ${hour}:${minute}:00`).getTime();
                }
            }
        }
    }
    
    startCountdown();
}

function startCountdown() {
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
    
    countdownInterval = setInterval(updateCountdown, 1000);
    updateCountdown();
}

function updateCountdown() {
    const countdownEl = document.getElementById("countdown");
    
    if (!countdownEl) return;
    
    if (!currentEventDate) {
        countdownEl.innerText = "Tanggal acara tidak valid.";
        return;
    }

    const now = new Date();
    const distance = currentEventDate - now.getTime();

    const eventStart = new Date(currentEventDate);
    const isSameDay = 
        now.getFullYear() === eventStart.getFullYear() &&
        now.getMonth() === eventStart.getMonth() &&
        now.getDate() === eventStart.getDate();

    if (isSameDay && now < eventStart) {
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownEl.innerText = `Hitung Mundur: ${hours}j : ${minutes}m : ${seconds}d`;
        return;
    }

    if (isSameDay && now >= eventStart) {
        countdownEl.innerText = "Acara sedang berlangsung.";
        return;
    }

    if (now > eventStart) {
        countdownEl.innerText = "Acara telah selesai.";
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    countdownEl.innerText = `Hitung Mundur: ${days}h : ${hours}j : ${minutes}m : ${seconds}d`;
}
</script>
