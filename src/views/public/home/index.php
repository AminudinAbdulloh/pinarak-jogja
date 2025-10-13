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
            <div class="highlight-event" id="highlight-event">
                <?php if ($highlight_event): ?>
                    <img src="<?= $highlight_event['image'] ?: 'assets/images/events/default.png' ?>"
                        alt="<?= htmlspecialchars($highlight_event['title']) ?>" class="main-image" id="highlight-image">
                    <div class="highlight-text">
                        <h3 id="highlight-title"><?= htmlspecialchars($highlight_event['title']) ?></h3>
                        <p class="event-date" id="highlight-date" data-datetime="<?= $highlight_event['start_time'] ?>">
                            📅 <?= formatDateIndonesia($highlight_event['start_time']) ?>
                        </p>
                        <p class="event-location" id="highlight-location">
                            📍 <?= htmlspecialchars($highlight_event['location']) ?>
                        </p>
                        <p class="description" id="highlight-description">
                            <?= nl2br(htmlspecialchars(truncateText($highlight_event['description'], 200))) ?>
                        </p>
                        <p id="countdown" class="countdown"></p>
                    </div>
                <?php else: ?>
                    <img src="assets/images/events/default.png" alt="No Event" class="main-image" id="highlight-image">
                    <div class="highlight-text">
                        <h3 id="highlight-title">Tidak Ada Event Mendatang</h3>
                        <p class="description" id="highlight-description">Belum ada event yang dijadwalkan. Pantau terus untuk update terbaru!</p>
                        <p id="countdown" class="countdown"></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="event-list">
                <?php if (!empty($all_events)): ?>
                    <?php foreach ($all_events as $index => $event): ?>
                        <div class="event-item clickable-event" data-event-index="<?= $index + 1 ?>">
                            <img src="<?= $event['image'] ?: 'assets/images/events/default.png' ?>"
                                alt="<?= htmlspecialchars($event['title']) ?>">
                            <div class="event-info">
                                <h4><?= htmlspecialchars($event['title']) ?></h4>
                                <small>📅 <?= formatDateIndonesia($event['start_time']) ?></small>
                                <small>📍 <?= htmlspecialchars($event['location']) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="event-item">
                        <div class="event-info">
                            <h4>Tidak ada event lainnya</h4>
                            <small>Pantau terus untuk update terbaru!</small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- Letakkan di bagian bawah file src/views/public/home/index.php -->
<script>
// Pass PHP variables to JavaScript
const BASEURL = "<?= BASEURL ?>";
const allEventsData = <?= json_encode($all_events ?? []) ?>;
let currentHighlightIndex = 0;

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

// Event Click Functionality
document.addEventListener('DOMContentLoaded', function() {
    attachEventListeners();
    initializeCountdown();
});

function attachEventListeners() {
    const clickableEvents = document.querySelectorAll('.clickable-event');
    
    clickableEvents.forEach(eventItem => {
        const newEventItem = eventItem.cloneNode(true);
        eventItem.parentNode.replaceChild(newEventItem, eventItem);
    });
    
    const freshClickableEvents = document.querySelectorAll('.clickable-event');
    
    freshClickableEvents.forEach(eventItem => {
        eventItem.addEventListener('click', function() {
            const eventIndex = parseInt(this.getAttribute('data-event-index'));
            switchHighlightEvent(eventIndex);
        });
        
        eventItem.style.cursor = 'pointer';
        eventItem.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        eventItem.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + '...';
}

function switchHighlightEvent(newIndex) {
    if (!allEventsData || newIndex >= allEventsData.length) return;
    
    const oldHighlightEvent = allEventsData[currentHighlightIndex];
    const selectedEvent = allEventsData[newIndex];
    
    allEventsData[currentHighlightIndex] = selectedEvent;
    allEventsData[newIndex] = oldHighlightEvent;
    
    updateHighlightDisplay(selectedEvent);
    updateEventListDisplay();
    updateEventCountdown(selectedEvent.start_time);
    
    const highlightSection = document.getElementById('highlight-event');
    if (highlightSection) {
        highlightSection.style.opacity = '0.7';
        setTimeout(() => {
            highlightSection.style.opacity = '1';
        }, 200);
    }
}

function updateHighlightDisplay(event) {
    const highlightImage = document.getElementById('highlight-image');
    const highlightTitle = document.getElementById('highlight-title');
    const highlightDate = document.getElementById('highlight-date');
    const highlightLocation = document.getElementById('highlight-location');
    const highlightDescription = document.getElementById('highlight-description');
    
    if (highlightImage) {
        highlightImage.src = getImagePath(event.image);
        highlightImage.alt = event.title;
    }
    
    if (highlightTitle) {
        highlightTitle.textContent = event.title;
    }
    
    if (highlightDate) {
        highlightDate.textContent = `📅 ${formatDateIndonesia(event.start_time)}`;
        highlightDate.setAttribute('data-datetime', event.start_time);
    }
    
    if (highlightLocation) {
        highlightLocation.textContent = `📍 ${event.location}`;
    }
    
    if (highlightDescription) {
        highlightDescription.textContent = truncateText(event.description, 200);
    }
}

function formatDateIndonesia(datetime) {
    const months = {
        1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April',
        5: 'Mei', 6: 'Juni', 7: 'Juli', 8: 'Agustus',
        9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember'
    };
    
    const date = new Date(datetime);
    const day = date.getDate();
    const month = months[date.getMonth() + 1];
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${day} ${month} ${year} ${hours}:${minutes}`;
}

function updateEventListDisplay() {
    const eventList = document.querySelector('.event-list');
    if (!eventList) return;
    
    const oldClickableEvents = eventList.querySelectorAll('.clickable-event');
    oldClickableEvents.forEach(item => item.remove());
    
    for (let i = 1; i < allEventsData.length; i++) {
        const event = allEventsData[i];
        const eventItem = createEventListItem(event, i);
        eventList.appendChild(eventItem);
    }
    
    attachEventListeners();
}

function createEventListItem(event, index) {
    const eventItem = document.createElement('div');
    eventItem.className = 'event-item clickable-event';
    eventItem.setAttribute('data-event-index', index);
    
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

function updateEventCountdown(newDateTime) {
    const date = new Date(newDateTime);
    currentEventDate = date.getTime();
    
    if (countdownInterval) {
        clearInterval(countdownInterval);
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