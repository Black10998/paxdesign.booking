<?php
/**
 * Booking Widget Template
 * Minimal, Professional, No Animations
 */

if (!defined('ABSPATH')) {
    exit;
}

$team_members = PAXdesign_Booking::get_instance()->get_team_members();
?>

<!-- Floating Clock Button -->
<div class="paxdesign-booking-button" role="button" tabindex="0" aria-label="Termin buchen">
  <section class="working">
    <div class="clock">
      <div class="clock-top">
        <div class="sec-min-container">
          <div class="min-container">
            <div class="min-pointer"></div>
          </div>
          <div class="sec-container">
            <div class="sec-pointer"></div>
          </div>
        </div>
        <div class="hours-container">
          <div class="hours-pointer"></div>
        </div>
      </div>
      <div class="clock-center">
        <span class="number number-1">01</span>
        <span class="number number-2">02</span>
        <span class="number number-3">03</span>
        <span class="number number-4">04</span>
        <span class="number number-5">05</span>
        <span class="number number-6">06</span>
        <span class="number number-7">07</span>
        <span class="number number-8">08</span>
        <span class="number number-9">09</span>
        <span class="number number-10">10</span>
        <span class="number number-11">11</span>
        <span class="number number-12">12</span>
      </div>
    </div>
  </section>
</div>

<!-- Chat-Style Widget -->
<div class="paxdesign-booking-widget">
  <div class="paxdesign-booking-container">
    
    <!-- Chat Header -->
    <div class="paxdesign-booking-header">
      <div class="paxdesign-booking-header-content">
        <h3>Termin buchen</h3>
        <p>PAXdesign Team</p>
      </div>
      <button class="paxdesign-booking-close" aria-label="Schließen">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>
    
    <!-- Body -->
    <div class="paxdesign-booking-body">
      
      <!-- Step Indicator -->
      <div class="paxdesign-booking-steps">
        <div class="paxdesign-booking-step-dot active"></div>
        <div class="paxdesign-booking-step-dot"></div>
        <div class="paxdesign-booking-step-dot"></div>
        <div class="paxdesign-booking-step-dot"></div>
      </div>
      
      <!-- Step 1: Team Selection -->
      <div class="paxdesign-booking-content active" data-step="1">
        <h3 style="text-align: center; margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #666;">Wählen Sie Ihren Ansprechpartner</h3>
        <div class="paxdesign-booking-team-grid">
          <?php foreach ($team_members as $key => $member) : ?>
          <div class="paxdesign-booking-team-card" data-member="<?php echo esc_attr($key); ?>" data-has-services="<?php echo isset($member['has_services']) && $member['has_services'] ? 'true' : 'false'; ?>">
            <div class="paxdesign-booking-team-avatar">
              <img src="<?php echo esc_url($member['image']); ?>" alt="<?php echo esc_attr($member['name']); ?>">
            </div>
            <div class="paxdesign-booking-team-info">
              <h3><?php echo esc_html($member['name']); ?></h3>
              <p><?php echo esc_html($member['role']); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      
      <!-- Step 1.5: Service Selection (for Adam only) -->
      <div class="paxdesign-booking-content" data-step="1.5">
        <h3 style="text-align: center; margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #666;">Wählen Sie Ihren gewünschten Service</h3>
        <div class="paxdesign-booking-services-grid" id="paxdesignServicesGrid">
          <!-- Services will be populated by JavaScript -->
        </div>
      </div>
      
      <!-- Step 2: Date & Time -->
      <div class="paxdesign-booking-content" data-step="2">
        <h3 style="text-align: center; margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #666;">Datum und Uhrzeit wählen</h3>
        <div class="paxdesign-booking-calendar-container">
          
          <!-- Calendar -->
          <div class="paxdesign-booking-calendar">
            <div class="paxdesign-booking-calendar-header">
              <button class="paxdesign-booking-calendar-nav prev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="15 18 9 12 15 6"/>
                </svg>
              </button>
              <span class="paxdesign-booking-calendar-title" id="paxdesignCalendarTitle"></span>
              <button class="paxdesign-booking-calendar-nav next">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="9 18 15 12 9 6"/>
                </svg>
              </button>
            </div>
            
            <div class="paxdesign-booking-calendar-weekdays">
              <span>Mo</span>
              <span>Di</span>
              <span>Mi</span>
              <span>Do</span>
              <span>Fr</span>
              <span>Sa</span>
              <span>So</span>
            </div>
            
            <div class="paxdesign-booking-calendar-days" id="paxdesignCalendarDays"></div>
          </div>
          
          <!-- Time Slots -->
          <div class="paxdesign-booking-timeslots">
            <h3>Verfügbare Zeiten</h3>
            <p class="paxdesign-booking-timeslots-date" id="paxdesignSelectedDateDisplay">Bitte wählen Sie ein Datum</p>
            <div class="paxdesign-booking-timeslots-grid" id="paxdesignTimeslotsGrid"></div>
          </div>
          
        </div>
        
        <div class="paxdesign-booking-actions">
          <button class="paxdesign-booking-btn paxdesign-booking-btn-back">Zurück</button>
          <button class="paxdesign-booking-btn paxdesign-booking-btn-next" disabled id="paxdesignNextToDetailsBtn">Weiter</button>
        </div>
      </div>
      
      <!-- Step 3: Details -->
      <div class="paxdesign-booking-content" data-step="3">
        <h3 style="text-align: center; margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #666;">Ihre Kontaktdaten</h3>
        
        <!-- Summary -->
        <div class="paxdesign-booking-summary">
          <div class="paxdesign-booking-summary-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            <div>
              <span class="paxdesign-booking-summary-label">Ansprechpartner</span>
              <span class="paxdesign-booking-summary-value" id="paxdesignSummaryMember"></span>
            </div>
          </div>
          
          <div class="paxdesign-booking-summary-item" id="paxdesignSummaryServiceItem" style="display: none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
            <div>
              <span class="paxdesign-booking-summary-label">Service</span>
              <span class="paxdesign-booking-summary-value" id="paxdesignSummaryService"></span>
            </div>
          </div>
          
          <div class="paxdesign-booking-summary-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <div>
              <span class="paxdesign-booking-summary-label">Datum & Uhrzeit</span>
              <span class="paxdesign-booking-summary-value" id="paxdesignSummaryDateTime"></span>
            </div>
          </div>
        </div>
        
        <!-- Form -->
        <form class="paxdesign-booking-form" id="paxdesignBookingDetailsForm">
          <div class="paxdesign-booking-form-group">
            <label for="paxdesignBookingName">Ihr Name *</label>
            <input type="text" id="paxdesignBookingName" required placeholder="Max Mustermann">
          </div>
          
          <div class="paxdesign-booking-form-group">
            <label for="paxdesignBookingEmail">E-Mail-Adresse *</label>
            <input type="email" id="paxdesignBookingEmail" required placeholder="max@beispiel.com">
          </div>
          
          <div class="paxdesign-booking-form-group">
            <label for="paxdesignBookingPhone">Telefonnummer</label>
            <input type="tel" id="paxdesignBookingPhone" placeholder="+43 123 456789">
          </div>
          
          <div class="paxdesign-booking-form-group">
            <label for="paxdesignBookingPurpose">Zweck des Termins *</label>
            <select id="paxdesignBookingPurpose" required>
              <option value="">Bitte wählen...</option>
              <option value="beratung">Beratungsgespräch</option>
              <option value="projekt">Projektbesprechung</option>
              <option value="support">Technischer Support</option>
              <option value="demo">Produkt-Demo</option>
              <option value="angebot">Angebotserstellung</option>
              <option value="sonstiges">Sonstiges</option>
            </select>
          </div>
          
          <div class="paxdesign-booking-form-group">
            <label for="paxdesignBookingMessage">Nachricht (optional)</label>
            <textarea id="paxdesignBookingMessage" rows="4" placeholder="Teilen Sie uns mit, worum es in dem Termin gehen soll..."></textarea>
          </div>
          
          <div class="paxdesign-booking-checkbox">
            <input type="checkbox" id="paxdesignBookingPrivacy" required>
            <label for="paxdesignBookingPrivacy">
              Ich akzeptiere die <a href="<?php echo esc_url(home_url('/datenschutz')); ?>" target="_blank">Datenschutzerklärung</a> 
              und stimme der Verarbeitung meiner Daten zu. *
            </label>
          </div>
        </form>
        
        <div class="paxdesign-booking-actions">
          <button class="paxdesign-booking-btn paxdesign-booking-btn-back">Zurück</button>
          <button class="paxdesign-booking-btn paxdesign-booking-btn-submit">Termin buchen</button>
        </div>
      </div>
      
      <!-- Success -->
      <div class="paxdesign-booking-success" id="paxdesignBookingSuccess">
        <div class="paxdesign-booking-success-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
        <h2>Terminanfrage erfolgreich gesendet!</h2>
        <p>Wir haben Ihre Anfrage erhalten und werden uns in Kürze bei Ihnen melden. Eine Bestätigung wurde an Ihre E-Mail-Adresse gesendet.</p>
        <div class="paxdesign-booking-success-details" id="paxdesignSuccessDetails"></div>
        <button class="paxdesign-booking-btn paxdesign-booking-btn-submit paxdesign-booking-close">Schließen</button>
      </div>
      
    </div>
  </div>
</div>
