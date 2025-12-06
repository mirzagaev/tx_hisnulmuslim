/**
 * Hisnul Muslim Ajax Handler - Pure JSON Version
 * HTML wird komplett im JavaScript aufgebaut
 */

// beim Laden
if (localStorage.getItem('theme') === 'dark') {
  document.documentElement.classList.add('dark');
}

// beim Umschalten
function toggleDarkMode() {
  document.documentElement.classList.toggle('dark');
  localStorage.setItem('theme',
    document.documentElement.classList.contains('dark') ? 'dark' : 'light'
  );
}

$(document).ready(function() {
    // Bestehende Such-Funktionalität
    $("#search_open").click(function() {
        $("#pageheadline").hide();
        $("#mobilemenu_opener").hide();
        $("#search_close").show();
        $("#searchfield").show();
        $("#searchfield").focus();
    });
    
    $("#search_close").click(function() {
        $("#pageheadline").show();
        $("#mobilemenu_opener").show();
        $("#search_close").hide();
        $("#searchfield").hide();
    });

    // AJAX für Kapitel-Links - Pure JSON Version
    $(".chapterLinks").on("click", function(e) {
        e.preventDefault();
        
        const $link = $(this);
        const chapterTitle = $link.data('chapter-title');
        const ajaxUrl = $link.data('ajax-url');
        
        // Titel im Drawer setzen
        $("#drawer-title").text(chapterTitle);
        
        // WICHTIG: Content sofort leeren beim Öffnen eines neuen Chapters
        // Verhindert, dass alte Daten sichtbar bleiben
        $("#drawer-content").html('<div class="w-full animate-pulse">'+
            '<div class="w-full h-full flex flex-col items-start">'+
            '<div class="h-8 w-full bg-gray-300 mb-3 xl:w-4/5"></div>'+
            '<div class="h-10 w-full bg-gray-300 mb-3"></div>'+
            '<div class="h-5 w-full bg-gray-300 xl:w-3/5"></div>'+
            '</div>'+
        '</div>');
        
        // Drawer öffnen
        $("#cart-drawer").removeClass("translate-x-full");
        $("#overlay").removeClass("hidden");
        
        // Ajax-Request
        $.ajax({
            url: ajaxUrl,
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                if (data.success && data.chapter && data.duas) {
                    // HTML aus JSON-Daten generieren
                    const html = buildChapterHTML(data);
                    
                    // Alten Content komplett ersetzen
                    $("#drawer-content")
                        .empty()  // Sicher ist sicher - nochmal leeren
                        .html(html);
                    
                    // Scroll zum Anfang
                    $("#drawer-content").scrollTop(0);
                } else {
                    showError('Fehler beim Laden der Daten.');
                }
            },
            error: function(xhr, status, error) {
                // console.error('Ajax-Fehler:', error, xhr.responseText);
                showError('Fehler beim Laden der Bittgebete: ' + error);
            }
        });
        
        return false;
    });

    // Drawer schließen - KEIN Leeren hier!
    $("#overlay, #closeDrawer").on("click", function() {
        $("#cart-drawer").addClass("translate-x-full");
        $("#overlay").addClass("hidden");
        // Content bleibt erhalten für schnelles Wiederöffnen
        return false;
    });
    
    // ESC-Taste zum Schließen
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && !$("#cart-drawer").hasClass('translate-x-full')) {
            $("#cart-drawer").addClass("translate-x-full");
            $("#overlay").addClass("hidden");
        }
    });
});

/**
 * Baut das HTML für den Drawer aus den JSON-Daten
 */
function buildChapterHTML(data) {
    let html = '';
    
    console.log('Building HTML for:', data.duas.length, 'duas'); // Debug
    
    // Chapter Header (optional)
    if (data.chapter.categoryTitle) {
        html += '<div class="mb-6">';
        html += '  <div class="text-sm text-neutral-500 flex items-center gap-2">';
        html += '    <span>' + escapeHtml(data.chapter.categoryTitle) + '</span>';
        html += '    <svg stroke-width="3" fill="none" class="size-3 stroke-current"><use xlink:href="#icon-right"></use></svg>';
        html += '  </div>';
        html += '</div>';
    }
    
    // Duas durchlaufen
    if (data.duas && data.duas.length > 0) {
        data.duas.forEach(function(dua, duaIndex) {
            html += '<article class="tx-hisnulmuslim-app-subcat" data-dua-index="' + duaIndex + '">';
            
            // Nummerierung
            html += '  <div class="nummerierung">';
            html += escapeHtml(data.chapter.chapterId) + ' / ' + escapeHtml(dua.duaId);
            html += '  </div>';
            
            // Items Liste
            html += '  <ul class="px-10 py-4 font-normal text-sm">';
            
            // Prüfen ob items ein Array ist
            if (Array.isArray(dua.items) && dua.items.length > 0) {
                dua.items.forEach(function(item, itemIndex) {
                    html += '    <li data-dua-item-uid="' + item.uid + '" ';
                    html += '        data-dua-item-type="' + escapeHtml(item.type) + '" ';
                    html += '        data-item-index="' + itemIndex + '">';
                    html += item.content; // Content ist bereits HTML
                    html += '    </li>';
                });
            } else {
                // Debug: Wenn items kein Array ist
                html += '    <li class="text-red-500">Fehler: Items ist kein Array für Dua ' + dua.uid + '</li>';
                console.error('Items ist kein Array:', dua.items);
            }
            
            html += '  </ul>';
            html += '</article>';
        });
    } else {
        // Falls keine Duas vorhanden
        html = '<div class="text-center py-20 text-neutral-500">';
        html += '  <p>Keine Bittgebete in diesem Kapitel gefunden.</p>';
        html += '</div>';
    }
    
    return html;
}

/**
 * Zeigt eine Fehlermeldung im Drawer
 */
function showError(message) {
    $("#drawer-content")
        .empty()
        .html(
            '<div class="text-center py-20 bg-red-100">' +
            '  <p class="text-red-500 font-semibold mb-2">Fehler beim Laden der Bittgebete. Bitte kontaktieren Sie den Support unter der E-Mail-Adresse info@hisnulmuslim.de.</p>' +
            '  <p class="text-sm text-neutral-500">' + escapeHtml(message) + '</p>' +
            '</div>'
        );
}

/**
 * Escaped HTML für sichere Ausgabe
 */
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}