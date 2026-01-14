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

let currentDuaURL = "";
let isDrawerOpen = false; // Flag to track if the main chapter/dua drawer is open

$(document).ready(function() {
    // Bestehende Such-Funktionalität
    $("#search_open").click(function() {
        $("#hmlogotop").hide();
        $("#mobilemenu_opener").hide();
        $("#search_close").show();
        $("#searchfield").show();
        $("#searchfield").focus();
    });
    
    $("#search_close").click(function() {
        $("#hmlogotop").show();
        $("#mobilemenu_opener").show();
        $("#search_close").hide();
        $("#searchfield").hide();
    });

    $(document).on("click", ".open_transliteration", function() {
      $("#transliteration-drawer").removeClass("-translate-x-full");
      $("#transliteration-overlay").removeClass("hidden");
      return false;
    });

    $(document).on("click", "#transliteration-overlay, #closeDrawer-transliteration", function() {
      $("#transliteration-drawer").addClass("-translate-x-full");
      $("#transliteration-overlay").addClass("hidden");
      return false;
    });

    // AJAX für Kapitel-Links - Pure JSON Version
    $(".chapterLinks").on("click", function(e) {
        e.preventDefault();
        
        const $link = $(this);
        const chapterTitle = $link.data('chapter-title');
        const ajaxUrl = $link.data('ajax-url');
        currentDuaURL = $link.attr('href');
        $("#kapitel-url").val(currentDuaURL);
        
        // Titel im Drawer setzen
        $("#drawer-title").text(chapterTitle);
        
        // WICHTIG: Content sofort leeren beim Öffnen eines neuen Chapters
        // Verhindert, dass alte Daten sichtbar bleiben
        $("#drawer-content").html('<div class="bittgebeteloading">'+
            '<div>'+
                '<div class="h-8 mb-3 lg:w-4/5"></div>'+
                '<div class="h-10 mb-3"></div>'+
                '<div class="h-5 lg:w-3/5"></div>'+
            '</div>'+
        '</div>');
        
        // Drawer öffnen
        $("#cart-drawer").removeClass("translate-x-full");
        $("#cart-overlay").removeClass("hidden");
        
        // Push a new state to the browser history when the drawer opens.
        // This allows the back button to close the drawer.
        history.pushState({ drawerOpen: true, duaUrl: currentDuaURL }, '', currentDuaURL);
        isDrawerOpen = true; // Update internal state
        
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
    $("#cart-overlay, #closeDrawer").on("click", function() {
        $("#cart-drawer").addClass("translate-x-full");
        $("#cart-overlay").addClass("hidden");
        
        // Wenn der Drawer über einen Link geöffnet wurde (History State gepusht),
        // gehen wir in der History zurück, um den State zu bereinigen.
        if (isDrawerOpen) {
            isDrawerOpen = false;
            history.back();
        }
        return false;
    });
    
    // ESC-Taste zum Schließen
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && !$("#cart-drawer").hasClass('translate-x-full')) {
            $("#cart-drawer").addClass("translate-x-full");
            $("#cart-overlay").addClass("hidden");
            
            if (isDrawerOpen) {
                isDrawerOpen = false;
                history.back();
            }
        }
    });

    // Handle browser back/forward button clicks (z.B. Wischgeste am Smartphone)
    $(window).on('popstate', function(event) {
        const drawerIsVisuallyOpen = !$("#cart-drawer").hasClass('translate-x-full');

        // Wenn der Drawer offen ist UND der neue State sagt "nicht offen" (oder null ist)
        if (drawerIsVisuallyOpen && (!event.originalEvent.state || !event.originalEvent.state.drawerOpen)) {
            $("#cart-drawer").addClass("translate-x-full");
            $("#cart-overlay").addClass("hidden");
            isDrawerOpen = false;
        }
    });

    $(document).on("click", ".shareDua", function(e) {
        e.preventDefault();
        openShareModal();
        // navigator.clipboard.writeText(currentDuaURL);
        // alert("Dua URL in die Zwischenablage kopiert.")
        return false;
    });

    $(document).on("click", '.close-share-modal', function(e) {
        e.preventDefault();
        $("#share-modal").addClass("hidden");
        $("#share-modal").removeClass("flex");
        $("#share-modal").attr("aria-hidden", "true");
        return false;
    });

    $(".copyToClipboardBtns button").click(function() {
      $(this).find("span:first-child").hide();
      $(this).find(".hidden").show();
    });
});

/**
 * Baut das HTML für den Drawer aus den JSON-Daten
 */
function buildChapterHTML(data) {
    let html = '';
    
    // console.log('Building HTML for:', data.duas.length, 'duas'); // Debug
    
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

            html += '  <div class="w-full relative my-3">';
            html += '       <div class="w-fit mx-auto">';
            html += '           <svg class="size-10 duaicon"><use xlink:href="#icon-logo"></use></svg>';
            html += '       </div>';
            html += '  </div>';
            
            html += '<article class="tx-hisnulmuslim-app-dua" data-dua-index="' + duaIndex + '">';
            // Items Liste
            html += ' <div class="w-full">';
            html += '  <ul>';
            
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
            html += '  <div class="w-full py-5 flex items-center justify-center">';
            html += '       <ul class="text-sm flex">';
            html += '           <li class="m-0 mr-2 p-0"><button class="shareDua cursor-pointer" type="button"><svg class="size-5"><use xlink:href="#icon-share"></use></svg></button></li>';
            html += '           <li class="m-0 mr-2 p-0"><a href="#" class="open_transliteration"><svg class="ml-4 size-5 fill-primary"><use xlink:href="#icon-transkript"></use></svg></a></li>';
            html += '       </ul>';
            html += '       <div class="ml-auto text-xs text-neutral-400 flex gap-5">';
            // html += '           <div>Hisnul Muslim</div>';
            html += '           <div>Kapitel '+escapeHtml(data.chapter.chapterId)+'</div>';
            html += '           <div>Bittgebet '+escapeHtml(dua.duaId)+'</div>';
            html += '       </div>';
            html += '  </div>';
            html += ' </div>';
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

// Funktion zum Öffnen des Share-Modals
function openShareModal() {
    const modal = document.getElementById('share-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
    }
}